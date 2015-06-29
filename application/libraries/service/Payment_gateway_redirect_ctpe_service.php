<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Ctpe/ctpe_integrator.php");

interface Payment_gateway_redirect_ctpe_interface
{
    /**************************************
     **  get array of $server, $path, $sender, $channel, $userid, $userpwd, $token, $transaction_mode, $transaction_response
     **
     **************************************/
    public function get_ctpe_integrator($currency = null, $card_type = null);

    public function get_customized_css();

    public function get_customized_js();

    public function get_query_server();

    public function get_verification_schedule_id();
}

abstract class Payment_gateway_redirect_ctpe_service extends Payment_gateway_redirect_service implements Payment_gateway_redirect_ctpe_interface
{
    protected $customized_css = null;
    protected $customized_javascript = null;
//  private $_pmgw_return_code;
    private $_ctpe_integrator = null;

    public function __construct($debug)
    {
        parent::__construct($debug);

        if ($this->get_customized_css())
            $this->customized_css = $this->get_customized_css();
        if ($this->get_customized_js())
            $this->customized_javascript = $this->get_customized_js();

        $this->initialization();
    }

    protected function initialization()
    {
        if (!$this->_ctpe_integrator) {
            $this->_ctpe_integrator = new Ctpe_integrator();
        }
    }

    public function prepare_get_url_request($payment_info = array(), &$request_data)
    {
        $this->setIntegratorDefaultValue(null, $payment_info["card_type"]);
        $bill_address = str_replace("|", ",", $this->so->get_bill_address());
        $bill_address = rtrim($bill_address, ',');
        $bill_firstname = $this->client->get_forename();
        $bill_surname = $this->client->get_surname();

        $this->_ctpe_integrator->setPaymentInformation($this->so->get_amount(), $this->so->get_so_no(), $this->so->get_client_id() . "-" . $this->so->get_so_no(), $this->so->get_currency_id());
        $telephone = $this->client->get_tel_1() . $this->client->get_tel_2() . $this->client->get_tel_3();
        $this->_ctpe_integrator->setCustomerContact($this->client->get_email(), $telephone, $telephone, $_SERVER["REMOTE_ADDR"]);
        $this->_ctpe_integrator->setCustomerAddress($bill_address, $this->so->get_bill_postcode(), $this->so->get_bill_city(), $this->so->get_bill_state(), $this->so->get_bill_country_id());
        $this->_ctpe_integrator->setCustomerName('', '', $bill_firstname, $bill_surname, $this->so->get_bill_company());
        $this->_ctpe_integrator->setWPFparams('true', 'false', 'DEFAULT', get_lang_id(), $this->get_payment_response_page());
//      $this->_ctpe_integrator->setPaymentCode("CC.DB");
        $this->_ctpe_integrator->setPaymentMethod($payment_info["card_type"]);

        $this->_ctpe_integrator->setUiCustomization($this->customized_css, $this->customized_javascript);
        $request_data = $this->array_implode('=', ',', $this->_ctpe_integrator->params);
        return $this->_ctpe_integrator->params;
    }

    public function setIntegratorDefaultValue($currency = null, $card_type = null)
    {
        $integrator_parameters = $this->get_ctpe_integrator($currency, $card_type);
        $this->_ctpe_integrator->set_ctpe_integrator($integrator_parameters["server"],
            $integrator_parameters["path"],
            $integrator_parameters["sender"],
            $integrator_parameters["channel"],
            $integrator_parameters["userid"],
            $integrator_parameters["userpwd"],
            $integrator_parameters["token"],
            $integrator_parameters["transaction_mode"],
            $integrator_parameters["transaction_response"]);
    }

    public function get_redirect_url($request_data, &$response_data)
    {
        $trycount = 0;
        do {
            $output = $this->_ctpe_integrator->commitPOSTPayment();
            $trycount++;
        } while (($trycount < 2) && ((!$output) || (empty($output))));
        $response_data = $this->array_implode('=', ',', $output);
        $processingResult = $output["POST.VALIDATION"];
        $redirectUrl = $output["FRONTEND.REDIRECT_URL"];
        if ($processingResult == "ACK") {
            if (strstr($redirectUrl, "http"))  // redirect url is returned ==> everything ok
            {
                return $redirectUrl;
            }
        }
        if (empty($processingResult) || ($processingResult == "")) {
//log the error
            $response_data = $this->array_implode('=', ',', $this->_ctpe_integrator->error) . " info:" . $this->array_implode('=', ',', $this->_ctpe_integrator->info);
            return $this->keep_error_message("request timeout, please try again");
        } else
            return $this->keep_error_message($processingResult);
    }

    public function verify_orders($start_date = null, $end_date = null)
    {
        $this->setIntegratorDefaultValue();
        include_once(APPPATH . "libraries/dao/Schedule_job_dao.php");
        $scj_dao = new Schedule_job_dao();

        if (($start_date == null) || ($end_date == null)) {
            $schedule_id = $this->get_verification_schedule_id();
            $sjob_obj = $scj_dao->get(array("id" => $schedule_id, "status" => "1"));
            if ($sjob_obj) {
                $last_access = $sjob_obj->get_last_access_time();
                $start_time = $last_access;
                $end_time = date('Y-m-d H:i:s');
            }
        } else {
            $start_time = $start_date;
            $end_time = $end_date;
        }

//prepare to send the query
        $input_parameters['start_date'] = $start_time;
        $input_parameters['end_date'] = $end_time;
        $serverInfo = $this->get_query_server();
        $this->_ctpe_integrator->setServer($serverInfo["server"], $serverInfo["path"]);
        $request_xml = $this->_ctpe_integrator->form_xml_query($input_parameters, "confirmation");
//      var_Dump($request_xml);
        $trycount = 0;
        do {
            $output = $this->_ctpe_integrator->queryToCtpe($request_xml);
            $trycount++;
        } while (($trycount < 2) && ((!$output) || (empty($output))));

//      print $output;
//      exit;

        if (($start_date == null) || ($end_date == null)) {
            if ($sjob_obj) {
                $sjob_obj->set_last_access_time($end_time);
                $scj_dao->update($sjob_obj);
            }
        }
    }

    public function process_payment_status($general_data, $get_data, &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
    {
        $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
        $result = $general_data['PROCESSING_RESULT'];
        $user_cancel_payment = $general_data['FRONTEND_REQUEST_CANCELLED'];
        $transaction_id = $general_data['IDENTIFICATION_TRANSACTIONID'];
        $currency_id = $general_data['PRESENTATION_CURRENCY'];
        $transaction_arr = explode('-', $transaction_id);
        $so_number = $transaction_arr[1];
        $client_id = $transaction_arr[0];

        $this->_pmgw_return_code = $general_data['PROCESSING_RETURN_CODE'];
        $data_from_pmgw = $this->array_implode('=', ',', $general_data);
        $sor_data = null;

        if ($result) {
            $sops_data['risk_ref1'] = $general_data['PROCESSING_RISK_SCORE'];
            if (isset($general_data['AUTHENTICATION_RESULT_INDICATOR']))
                $sops_data['risk_ref4'] = $general_data['AUTHENTICATION_RESULT_INDICATOR'];
            $sops_data['remark'] = $general_data['PROCESSING_RETURN_CODE'] . "|" . $general_data['PROCESSING_CODE'];
            $sops_data['pay_date'] = $general_data['PROCESSING_TIMESTAMP'];
            $so_data['txn_id'] = $general_data['IDENTIFICATION_UNIQUEID'];

            if (($result == "NOK") && ($user_cancel_payment == "true")) {
                $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_CANCEL;
            } else if (strstr($result, "ACK")) {
                $data_to_pmgw = $this->_get_successful_page($so_number);
                $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
            } else {
                $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
            }
        }

        if (($payment_result == Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS)
            || ($payment_result == Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL)
        ) {
//          if ($this->_pmgw_return_code == "900.300.600")
            if (!$currency_id) {
//900.300.600 = user session timeout, when user session timeout, there is no currency_id information
                $so_srv = $this->get_so_srv();
                $this->so = $so_srv->get(array("so_no" => $so_number));
                $currency_id = $this->so->get_currency_id();
                if ($this->so->get_status() >= 2) {
                    mail($this->get_technical_support_email(), '[VB] ' . $this->get_payment_gateway_name() . ' user session timeout:' . $this->so->get_client_id() . '-' . $this->so->get_so_no(), $this->_pmgw_return_code, 'From: website@valuebasket.com');
                }
            }
            $payment_result = $this->query_transaction(array("transaction_id" => $transaction_id, "currency_id" => $currency_id)
                , $query_data_from_pmgw
                , $query_data_to_pmgw
                , $so_data
                , $socc_data
                , $sops_data
            );
            if ($query_data_to_pmgw)
                $this->get_sopql_service()->add_log($so_number, "O", $query_data_to_pmgw);
            if ($query_data_from_pmgw)
                $this->get_sopql_service()->add_log($so_number, "I", $query_data_from_pmgw);
        }
        return $payment_result;
    }

    private function _get_successful_page($so_number = null)
    {
//      return $this->get_successful_page($so_number);
        return $this->get_successful_page_top($so_number);
    }

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
    {
        if (isset($input_parameters["currency_id"]))
            $inputCurrency = $input_parameters["currency_id"];
        else
            $inputCurrency = null;
        $this->setIntegratorDefaultValue($inputCurrency);

        $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
        $transaction_id = $input_parameters['transaction_id'];
        $input_params = array("transaction_id" => $transaction_id);
        $serverInfo = $this->get_query_server();
        $this->_ctpe_integrator->setServer($serverInfo["server"], $serverInfo["path"]);
        $request_xml = $this->_ctpe_integrator->form_xml_query($input_params);

        $data_to_pmgw = $request_xml;
//      var_dump($request_xml);

        $trycount = 0;
        do {
            $output = $this->_ctpe_integrator->queryToCtpe($request_xml);
            $trycount++;
        } while (($trycount < 2) && ((!$output) || (empty($output))));

        $data_from_pmgw = $output;
        if ($output) {
            $simpleXml = new SimpleXMLElement($output);
        } else {
            $message = "error:" . $this->array_implode('=', ',', $this->_ctpe_integrator->error) . ", info:" . $this->array_implode('=', ',', $this->_ctpe_integrator->info);
            mail($this->get_technical_support_email(), '[VB] ' . $this->get_payment_gateway_name() . ' query fail:' . $input_parameters["transaction_id"], $message, 'From: website@valuebasket.com');
            return Payment_gateway_redirect_service::PAYMENT_STATUS_KEEP_PENDING;
        }
        $number_of_result = (string)$simpleXml->Result['count'];
        for ($i = 0; $i < $number_of_result; $i++) {
            if ((string)$simpleXml->Result->Transaction[$i]["source"] == "WPF") {
                $socc_data = array("card_last4" => ltrim((string)$simpleXml->Result->Transaction[$i]->Account->Number, "*")
                , "card_exp_month" => (string)$simpleXml->Result->Transaction[$i]->Account->Expiry['month']
                , "card_exp_year" => (string)$simpleXml->Result->Transaction[$i]->Account->Expiry['year']);
                $sops_data['remark'] = (string)$simpleXml->Result->Transaction[$i]->Processing->Return['code'] . "|" . (string)$simpleXml->Result->Transaction[$i]->Processing['code'];
                $sops_data['pay_date'] = (string)$simpleXml->Result->Transaction[$i]->RequestTimestamp;
                $so_data['txn_id'] = (string)$simpleXml->Result->Transaction[$i]->Identification->UniqueID;

                if (((string)$simpleXml->Result->Transaction[$i]->Processing->Result == "ACK")
                    &&
                    ((((string)$simpleXml->Result->Transaction[$i]->Processing->Status['code'] == "00")
                            && ((string)$simpleXml->Result->Transaction[$i]->Processing->Reason['code'] == "00"))
                        ||
                        (((string)$simpleXml->Result->Transaction[$i]->Processing->Status['code'] == "90")
                            && ((string)$simpleXml->Result->Transaction[$i]->Processing->Reason['code'] == "00")))
                ) {
//                  $this->_pmgw_return_code = (string) $simpleXml->Result->Transaction[$i]->Processing->Return["code"];
//input more data into dtabase, success payment
                    $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
                } else
                    $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
            } else if ((string)$simpleXml->Result->Transaction[$i]["source"] == "SYSTEM") {
                $processingCode = (string)$simpleXml->Result->Transaction[$i]->Processing["code"];
                if (substr($processingCode, 0, 5) == "RM.3D") {
                    $processingReturnCode = (string)$simpleXml->Result->Transaction[$i]->Processing->Return['code'] . "|" . (string)$simpleXml->Result->Transaction[$i]->Processing['code'];
                    $sops_data['risk_ref3'] = $processingReturnCode;
                }
            }
        }
        return $payment_result;
    }

    public function update_pending_list()
    {
        include_once(APPPATH . "libraries/dao/Schedule_job_dao.php");
        $scj_dao = new Schedule_job_dao();

        $schedule_id = $this->get_verification_schedule_id();
        $sjob_obj = $scj_dao->get(array("id" => $schedule_id, "status" => "1"));
        if ($sjob_obj) {
            $last_access = $sjob_obj->get_last_access_time();
//          $start_time = $last_access;
            $timeShift = 60 * 30;
            $start_time = strtotime($last_access) - $timeShift;
            $end_time = date('Y-m-d H:i:s');
            $shiftedEndTime = date("Y-m-d H:i:s", (strtotime($end_time) - $timeShift));
            $sops_dao = $this->get_so_srv()->get_sops_dao();
            $sops_list = $sops_dao->get_list(array("payment_gateway_id" => $this->get_payment_gateway_name()
                , "payment_status" => "P"
                , "create_on >" => date("Y-m-d H:i:s", $start_time)
                , "create_on <=" => $shiftedEndTime)
                , array("limit" => -1));

            foreach ($sops_list as $sops) {
                $this->query_payment_status_in_general($sops->get_so_no());
            }
            $sjob_obj->set_last_access_time($end_time);
            $scj_dao->update($sjob_obj);
        }
    }

    public function process_success_action()
    {
// send confirmation email
        $this->fire_success_event();
        print $this->_get_successful_page();
        return true;
    }

    public function process_failure_action()
    {
        print $this->get_failure_page_top();
        return true;
    }

    public function process_cancel_action()
    {
        print $this->get_cancel_page_top();
        return true;
    }
}



