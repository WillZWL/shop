<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Global_collect/global_collect_integrator.php");

class Payment_gateway_redirect_global_collect_service extends Payment_gateway_redirect_service
{
    const PAYMENT_CAPTURE = 800;
    const PAYMENT_CANCEL = 99999;

    private $_gc_integrator;

    public function __construct($debug = 0)
    {
        parent::__construct($debug);
        $this->_gc_integrator = new Global_collect_integrator($debug);
    }

    public function get_payment_gateway_name()
    {
        return "global_collect";
    }

    public function get_technical_support_email()
    {
        return "oswald-alert@eservicesgroup.com";
    }

    public function prepare_get_url_request($payment_info = array(), &$request_data)
    {
        $card_id = $payment_info["card_type"];
        $soiList = $this->get_so_srv()->get_soi_dao()->get_list(array("so_no" => $this->so->get_so_no()), array("limit" => -1));
        $request_data = $this->_gc_integrator->form_payment_xml($this->so, $soiList, $this->client, $card_id, $this->get_payment_response_page());
        return $request_data;
    }

    public function xml_response($vars)
    {
        $xml = simplexml_load_string($vars);
        switch ((string)$xml->REQUEST->ACTION)
        {
            case "INSERT_ORDERWITHPAYMENT":
                return $this->_extract_redirect_url($xml);
                break;
            case "GET_ORDERSTATUS":
                return $xml;
                break;
        }
    }

    private function _extract_redirect_url($xml)
    {
        return array("response_result" => (string) $xml->REQUEST->RESPONSE->RESULT
                    , "txn_id" => (string) $xml->REQUEST->RESPONSE->ROW->REF
                    , "mac_token" => (string) $xml->REQUEST->RESPONSE->ROW->RETURNMAC
                    , "redirectUrl" => (string) $xml->REQUEST->RESPONSE->ROW->FORMACTION);
    }

    public function get_redirect_url($request_data, &$response_data)
    {
        $trycount = 0;
        do
        {
            $output = $this->_gc_integrator->submitRequest($request_data);
            $trycount++;
        }while (($trycount < 2) && (!empty($output["error"])));

        $extractedData = $this->xml_response($output["result"]);
        $this->sops->set_mac_token($extractedData["mac_token"]);
        $this->so->set_txn_id($extractedData["txn_id"] );
        $this->get_so_srv()->get_dao()->update($this->so);

        if (($output != "") && ($output["error"] == ""))
            $response_data = $output["result"];
        else if($output["result"] != "OK")
        {
            $down_message = "response result:" . $output["result"] . $output["error"] . " " . $this->array_implode('=', ',', $output["info"]);
            mail($this->sitedown_email, $this->get_payment_gateway_name() . " payment issue", $down_message, 'From: website@valuebasket.com');
        }
        else
        {
            $response_data = $output["error"] . " " . $this->array_implode('=', ',', $output["info"]);
            $down_message = "Session: " . $session . "Please contact " . $this->get_payment_gateway_name() . ", IT please consider to switch payment gateway." . "O:" . $request_data . ", I:" . $response_data;
            mail($this->sitedown_email, $this->get_payment_gateway_name() . " payment issue", $down_message, 'From: website@valuebasket.com');
            return "ERROR::" . base_url() . "checkout_onepage/payment_result/0/{$this->so->get_so_no()}?type=sitedown";
        }
        return $extractedData["redirectUrl"];
    }

    public function get_pending_schedule_id()
    {
        return "GLOBAL_COLLECT_ORDERS_VERIFICATION";
    }

    public function update_pending_list()
    {
        include_once(APPPATH."libraries/dao/Schedule_job_dao.php");
        $scj_dao = new Schedule_job_dao();

        $schedule_id = $this->get_pending_schedule_id();
        $sjob_obj = $scj_dao->get(array("id" => $schedule_id, "status" => "1"));
        if ($sjob_obj)
        {
            $last_access = $sjob_obj->get_last_access_time();
//shift 30mins
            $timeShift = 60 * 30;
            $additionalShift = 60 * 90;
//we need the additionalShift=90mins because we need to query last 2 hours pending orders
            $start_time = strtotime($last_access) - $timeShift - $additionalShift;
            $end_time = date('Y-m-d H:i:s');
            $shiftedEndTime = date("Y-m-d H:i:s", (strtotime($end_time) - $timeShift));
            $sops_dao = $this->get_so_srv()->get_sops_dao();
            $sops_list = $sops_dao->get_list(array("payment_gateway_id" => $this->get_payment_gateway_name()
                                                , "payment_status" => "P"
                                                , "payment_status <> 'NA'" => null
                                                , "create_on >" => date("Y-m-d H:i:s", $start_time)
                                                , "create_on <=" => $shiftedEndTime)
                                        , array("limit" => -1));

//print $sops_dao->db->last_query();

            foreach($sops_list as $sops)
            {
                $this->query_payment_status_in_general($sops->get_so_no());
            }
            $sjob_obj->set_last_access_time($end_time);
            $scj_dao->update($sjob_obj);
        }
    }

    private function _process_by_get_order_status($so_number, &$sops, &$socc)
    {
        $requestXml = $this->_gc_integrator->form_order_status_xml($so_number);
        $this->get_sopql_service()->add_log($so_number, "O", $requestXml);
        $orderReuslt = $this->_gc_integrator->submitRequest($requestXml);
        $this->get_sopql_service()->add_log($so_number, "I", $orderReuslt["result"]);

        if ($orderReuslt["error"] != "")
        {
            mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " line:" . __LINE__ . " cannot get order status so_no:" . $so_number, $xml, 'From: website@valuebasket.com');
        }
        else
        {
            $xml = $this->xml_response($orderReuslt["result"]);

            $resp_result = (string)$xml->REQUEST->RESPONSE->RESULT;
            $resp_avsresult = (string)$xml->REQUEST->RESPONSE->STATUS->AVSRESULT;
            $resp_fraudresult = (string)$xml->REQUEST->RESPONSE->STATUS->FRAUDRESULT;
            $resp_statusid = (int)$xml->REQUEST->RESPONSE->STATUS->STATUSID;
            $resp_ccno = (string)$xml->REQUEST->RESPONSE->STATUS->CREDITCARDNUMBER;
            $resp_eci = (string)$xml->REQUEST->RESPONSE->STATUS->ECI;
            $resp_cavv = (string)$xml->REQUEST->RESPONSE->STATUS->CAVV;

            if ($resp_result == "OK")
            {
                if ($resp_avsresult != "")
                    $sops["risk_ref1"] = $resp_avsresult;
                if ($resp_fraudresult != "")
                    $sops["risk_ref2"] = $resp_fraudresult;
                if ($resp_eci != "")
                    $sops["risk_ref4"] = $resp_eci;
                if ($resp_cavv != "")
                    $sops["risk_ref3"] = $resp_cavv;
                if ($resp_ccno != "")
                    $socc["card_last4"] = ltrim($resp_ccno, "*");

                if (($resp_statusid >= self::PAYMENT_CAPTURE)
                    && ($resp_statusid != self::PAYMENT_CANCEL))
                {
//800
                    $sops["pending_action"] = "NA";
                    return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
                }
/*
                elseif ((!($resp_statusid == 50 || $resp_statusid == 650 || $resp_statusid == 20 || $resp_statusid == 25))
                    || (((mktime() - strtotime($this->so->get_order_create_date())) > 3600) && ($resp_statusid == 50 || $resp_statusid == 650 || $resp_statusid == 20 || $resp_statusid == 25)))
*/
                elseif (!($resp_statusid == 50 || $resp_statusid == 650 || $resp_statusid == 20 || $resp_statusid == 25))
                {
                    $sops["pending_action"] = "NA";
                    return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
                }
            }
            else
            {
                mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " line:" . __LINE__ . " cannot get order status so_no:" . $so_number, $xml, 'From: website@valuebasket.com');
            }
        }
        return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
    }

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
    {
        return $this->_process_by_get_order_status($this->so->get_so_no(), $sops_data, $socc_data);
    }

    public function process_payment_status($general_data = array(), $get_data = array(), &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
    {
        $txn_id = $get_data["REF"];
        $mac = $get_data["RETURNMAC"];
        $this->so = $this->get_so_srv()->get_dao()->get_so_w_pmgw(array("so.txn_id" => $txn_id, "sops.mac_token" => $mac), array("limit"=>1));

//      var_dump($get_data);
//      var_dump($general_data);
//      var_dump($this->get_so_srv()->get_dao()->db->last_query());

        $data_from_pmgw = $this->array_implode('=', ',', $get_data);
        if ($this->so)
        {
            $so_number = $this->so->get_so_no();
            return $this->_process_by_get_order_status($so_number, $sops_data, $socc_data);
        }
        else
        {
            $message = $data_from_pmgw;
            mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Cannot get so_no", $message, 'From: website@valuebasket.com');
            $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
        }

        return $payment_result;
    }

    public function process_failure_action()
    {
        header("Location:" . $this->_get_failure_page());
    }

    public function process_cancel_action()
    {
// no cancel button
//        header("Location:" . $this->_get_failure_page());
    }

    public function process_success_action()
    {
        $this->fire_success_event();
        header("Location:" . $this->_get_successful_page());
    }

    private function _get_successful_page($so_number = null)
    {
        return $this->get_successful_page_top($so_number);
    }

    public function _get_failure_page()
    {
        return $this->get_failure_page_top();
    }

    public function is_payment_need_credit_check($is_fraud = false)
    {
        return parent::is_payment_need_credit_check_3D($is_fraud);
    }

    public function is_need_dm_service($is_fraud = false)
    {
        return parent::is_payment_need_credit_check($is_fraud);
    }
}

