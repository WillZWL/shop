<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_post_submit_service.php");
include_once(APPPATH . "libraries/service/Cybersource/Cybersource_integrator.php");
include_once(APPPATH . "libraries/service/Cybersource/Cybersource_soap.php");

class Payment_gateway_redirect_cybersource_service extends Payment_gateway_redirect_post_submit_service
{
    private $_cybersource_integrator;
    private $_payment_result;
    private $_so_dao;
    private $_domain;

    public function __construct($debug = 0)
    {
        parent::__construct($debug);
        $this->_cybersource_integrator = new Cybersource_integrator();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
    }

    public function get_payment_gateway_name()
    {
        return "cybersource";
    }

    public function get_form_action()
    {
        if ($this->debug == 1)
            return Cybersource_integrator::CYBER_SOURCE_PAYMENT_TEST_FORM;
        else
            return Cybersource_integrator::CYBER_SOURCE_PAYMENT_FORM;
    }

    public function get_form_data($vars)
    {
        $this->prepare_get_url_request($vars, $submit_data);
        return $submit_data;
    }

    public function prepare_get_url_request($payment_info = array(), &$request_data)
    {
        $this->_cybersource_integrator->cybersource_set_attribute(array("card_type" => $payment_info["card_type"],
            "payment_button" => "Make Payment"));
        $postInformation = array();
        $postInformation['orderId'] = $this->so->get_client_id() . "-" . $this->so->get_so_no();
        $postInformation['description'] = date("Y-m-d H:i:s");
        $postInformation['company'] = $this->so->get_bill_company();
        $postInformation['amount'] = $this->so->get_amount();
        $postInformation['currency'] = $this->so->get_currency_id();
        $postInformation['firstName'] = $this->client->get_forename();
        $postInformation['email'] = $this->client->get_email();
        $postInformation['lastName'] = $this->client->get_surname();
        if (($postInformation['lastName'] == "") || (is_null($postInformation['lastName']))) {
            $bill_name = explode(" ", $this->client->get_forename());
            $first_name = $bill_name[0];
            if (sizeof($bill_name) > 1)
                $last_name = $bill_name[1];
            else
                $last_name = "";
            $postInformation['firstName'] = $first_name;
            $postInformation['lastName'] = $last_name;
        }
        $postInformation['postalCode'] = $this->so->get_bill_postcode();
        $address = explode("|", $this->so->get_bill_address());
        $postInformation['address1'] = $address[0];
        $postInformation['address2'] = (sizeof($address) > 1) ? $address[1] : "";
        $postInformation['address2'] .= (sizeof($address) > 2) ? $address[2] : "";
        $postInformation['city'] = $this->so->get_bill_city();
        $postInformation['state'] = $this->so->get_bill_state();
        $postInformation['countryCode'] = $this->so->get_bill_country_id();
        $postInformation['tel'] = $this->client->get_tel_1() . $this->client->get_tel_2() . $this->client->get_tel_3();

        $postInformation['del_company'] = $this->so->get_delivery_company();
        if ($this->so->get_delivery_name()) {
            $del_name = explode(" ", $this->so->get_delivery_name());
            $first_name = $del_name[0];
            if (sizeof($del_name) > 1)
                $last_name = $del_name[1];
            else
                $last_name = "";
            $postInformation['del_firstName'] = $first_name;
            $postInformation['del_lastName'] = $last_name;
        }
        $postInformation['del_postalCode'] = $this->so->get_delivery_postcode();
        $del_address = explode("|", $this->so->get_delivery_address());
        $postInformation['del_address1'] = $del_address[0];
        $postInformation['del_address2'] = (sizeof($del_address) > 1) ? $del_address[1] : "";
        $postInformation['del_address2'] .= (sizeof($del_address) > 2) ? $del_address[2] : "";
        $postInformation['del_city'] = $this->so->get_delivery_city();
        $postInformation['del_state'] = $this->so->get_delivery_state();
        $postInformation['del_countryCode'] = $this->so->get_delivery_country_id();
        $postInformation['del_tel'] = $this->client->get_del_tel_1() . $this->client->get_del_tel_2() . $this->client->get_del_tel_3();

        $postInformation['domain'] = $_SERVER['HTTP_HOST'];

        $request_data = $this->_cybersource_integrator->form_payment_request_array($postInformation);
        return $request_data;
    }

    public function get_redirect_url($request_data, &$response_data)
    {
        if (in_array($request_data["card_type"], $this->_cybersource_integrator->payment_card_type)) {
            return base_url() . "checkout_redirect_method/sumbit_to_payment_gateway/cybersource/" . $this->so->get_so_no() . "/" . $request_data["card_type"] . (($this->debug == 1) ? "?debug=1" : "");
        } else
            return null;
    }

    public function process_payment_status($general_data, $get_data, &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
    {
        $payment_result = $general_data["decision"];
        $reason_code = $general_data["reasonCode"];
        $avs_code = $general_data["ccAuthReply_avsCode"];
        $cv_code = $general_data["ccAuthReply_cvCode"];
        $this->_domain = $general_data["domain"];

        $transaction_arr = explode('-', $general_data["orderNumber"]);
        $so_number = $transaction_arr[1];

        $data_from_pmgw = $this->array_implode('=', ',', $general_data);
        $data_to_pmgw = null;

        $this->_payment_result = $payment_result;
        $sops_data = array("pay_date" => date("Y-m-d H:i:s"));
        $sor_data = array("risk_requested" => 0,
            "risk_var1" => ($payment_result . "|" . $reason_code),
            "risk_var2" => $avs_code,
            "risk_var3" => $cv_code);

        if (($payment_result == "ACCEPT")
            || ($payment_result == "REVIEW")
        ) {
            return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
        } else if ($payment_result == "REJECT") {
            return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
        } else if ($payment_result == "CANCEL") {
            return Payment_gateway_redirect_service::PAYMENT_STATUS_CANCEL;
        }
        return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
    }

    public function is_need_dm_service($is_fraud = false)
    {
        return $this->is_payment_need_credit_check($is_fraud);
    }

    public function is_payment_need_credit_check($is_fraud = false)
    {
        return TRUE;
//      return parent::is_payment_need_credit_check();
    }

    public function payment_notification($input_data)
    {
        /*
                $fp = fopen("/var/www/html/valuebasket.com/public_html/notification2.txt", 'w');
                fwrite($fp, $input_data);
                fclose($fp);
        */
        $general_data = array();
        $elems_ar = explode("&", $input_data);
        for ($i = 0; $i < count($elems_ar); $i++) {
            list($key, $val) = explode('=', $elems_ar[$i]);
            $general_data[urldecode($key)] = urldecode($val);     // store to indexed array
        }

        $transaction_arr = explode('-', $general_data["orderNumber"]);
        $so_no = $transaction_arr[1];

        $so_srv = $this->get_so_srv();
//      $process_success = false;

        if ($input_data)
            $this->get_sopl_srv()->add_log($so_no, "I", $input_data);

        if ($this->so = $so_srv->get(array("so_no" => $so_no))) {
            $this->_payment_result = $payment_result = $general_data["decision"];
            $reason_code = $general_data["reasonCode"];

            $card_type = $general_data["card_cardType"];
            $card_bin = $general_data["ccAuthReply_cardBIN"];
            $avs_code = $general_data["ccAuthReply_avsCode"];
            $cv_code = $general_data["ccAuthReply_cvCode"];
            $card_last_digits = substr($general_data["card_accountNumber"], 12, 4);
            if (($payment_result == "ACCEPT")
                || ($payment_result == "REVIEW")
            ) {
                $sops_dao = $this->get_so_srv()->get_sops_dao();
                $this->sops = $sops_dao->get(array("so_no" => $so_no));
                $sops_data = array("pay_date" => date("Y-m-d H:i:s"));
                $socc_data = array("card_type" => $card_type,
                    "card_last4" => $card_last_digits,
                    "card_bin" => $card_bin);
                $sor_data = array("risk_requested" => 0,
                    "risk_var1" => ($payment_result . "|" . $reason_code),
                    "risk_var2" => $avs_code,
                    "risk_var3" => $cv_code
                );
                $this->payment_success_operation(array(), $sops_data, $socc_data, $sor_data);
                $this->fire_success_event();
//              $process_success = true;
            } else if (($payment_result == "CANCEL")
                || ($payment_result == "REJECT")
                || ($payment_result == "ERROR")
            ) {
                $sops_dao = $this->get_so_srv()->get_sops_dao();
                $this->sops = $sops_dao->get(array("so_no" => $so_no));

                $holdAuto = "";
//check if payment is ACCEPTED before
                if (($this->sops->get_payment_status() == "S")
                    || ($this->sops->get_payment_status() == "D")
                ) {
//change status if the record is not on hold by CS/logistic before
                    if ((($this->so->get_status() >= 2) && ($this->so->get_status() <= 6))
                        && ($this->so->get_refund_status() == 0)
                        && ($this->so->get_hold_status() == 0)
                    ) {
                        $holdAuto = "Auto hold by system, from success to " . $payment_result;

                        $order_note_obj = $this->order_note_dao->get();
                        $order_note_obj->set_so_no($this->so->get_so_no());
                        $order_note_obj->set_type('O');
                        $order_note_obj->set_note('Order payment status changed from success to ' . $payment_result . ' by CYBS, need compliance to verify');
                        $this->order_note_dao->insert($order_note_obj);

                        $hold_reason_obj = $this->so_hold_reason_dao->get();
                        $hold_reason_obj->set_so_no($this->so->get_so_no());
                        $hold_reason_obj->set_reason("change_of_address");
                        $this->so_hold_reason_dao->insert($hold_reason_obj);

                        $this->so->set_status(2);
                        $this->so->set_hold_status(1);
                        if (!$so_srv->update($this->so)) {
                            mail($this->get_technical_support_email(), 'Error: ACCEPTED order change to ' . $payment_result . ' in VB:' . $this->so->get_client_id() . '-' . $so_no, $holdAuto . "\n\nLogging:\n" . $input_data, 'From: website@valuebasket.com');
                        }
                    } else {
                        $holdAuto = "No change in status in the system, order was hold before";
                    }
                    //if yes, put order on hold and send email to compliance to verify
                    mail('compliance@valuebasket.com', 'Authorised order change to ' . $payment_result . ' in VB:' . $this->so->get_client_id() . '-' . $so_no, $holdAuto . "\n\nLogging:\n" . $input_data, 'From: website@valuebasket.com');
                    mail($this->get_technical_support_email(), 'Authorised order change to ' . $payment_result . ' in VB:' . $this->so->get_client_id() . '-' . $so_no, $holdAuto . "\n\nLogging:\n" . $input_data, 'From: website@valuebasket.com');
                } else {
                    //just cancelled the order
                    $sops_data = array("risk_ref1" => $reason_code);
                    $socc_data = array("card_type" => $card_type,
                        "card_last4" => $card_last_digits,
                        "card_bin" => $card_bin);
                    $this->payment_cancel_operation(array(), $sops_data, $socc_data);
                }
//              $process_success = true;
            }
            /*
                        if ($process_success)
                        {
                            print "[OK]";
                            return;
                        }
            */
        }
//      print "fail";
    }

    public function get_technical_support_email()
    {
        return "oswald@eservicesgroup.net";
    }

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
    {
        return TRUE;
    }

    public function process_success_action()
    {
//      $this->fire_success_event();
        redirect($this->get_successful_page());
    }

    public function get_successful_page()
    {
        $debug_string = ($this->debug) ? "?debug=1" : "";
        $url = "https://" . $this->_domain . "/" . $this->so->get_lang_id() . "_" . $this->so->get_bill_country_id() . "/" . $this->successful_page . $this->so->get_so_no() . $debug_string;
        return $url;
    }

    public function process_failure_action()
    {
        redirect($this->get_failure_page());
    }

    public function get_failure_page()
    {
        $url = "https://" . $this->_domain . "/" . $this->so->get_lang_id() . "_" . $this->so->get_bill_country_id() . "/" . $this->failure_page;
        $url .= $this->so->get_so_no();
        return $url;
    }

    public function process_cancel_action()
    {
        redirect($this->get_cancel_page());
    }

    public function get_cancel_page()
    {
        return "https://" . $this->_domain . "/" . $this->so->get_lang_id() . "_" . $this->so->get_bill_country_id() . "/" . $this->cancel_page;
    }

    public function send_order_to_dm($debug = 0)
    {
        $where = array("so.create_on >" => "2014-04-01 00:00:00");
        $options = array("limit" => -1);
        $orders = $this->get_so_dao()->get_orders_for_dm($where, $options);
        $this->debug = $debug;

        $possible_obj = "";
        $j = 0;
        include_once APPPATH . "libraries/vo/so_item_vo.php";
        foreach ($orders as $order) {
            if ($possible_obj != "") {
                if ($possible_obj->get_so_no() != $order->get_so_no()) {
                    $this->send_request_to_dm($possible_obj, $possible_obj->get_so_no());
                    $possible_obj = $order;
                    $j = 0;
                }
            } else if ($possible_obj == "") {
                $possible_obj = $order;
            }

            $possible_obj->so_item[$j] = new So_item_vo();
            $possible_obj->so_item[$j]->set_so_no($order->get_so_no());
            $possible_obj->so_item[$j]->set_line_no($order->get_line_no());
            $possible_obj->so_item[$j]->set_prod_sku($order->get_prod_sku());
            $possible_obj->so_item[$j]->set_prod_name($order->get_prod_name());
            $possible_obj->so_item[$j]->set_unit_price($order->get_unit_price());
            $possible_obj->so_item[$j]->set_qty($order->get_qty());
            if ($possible_obj->get_payment_gateway_id() == 'paypal') {
                $possible_obj->set_email($possible_obj->get_payer_email());
                if (($possible_obj->get_surname() == '') || is_null($possible_obj->get_surname())) {
//separate the forename and surname
                    $original_name = $possible_obj->get_forename();
                    $separate_name = explode(' ', $original_name);
                    if (sizeof($separate_name) > 1) {
                        $possible_obj->set_forename($separate_name[0]);
                        $possible_obj->set_surname(str_replace($separate_name[0] . " ", "", $original_name));
                    }
                }
            }
            $j++;
        }
        if ($possible_obj != "") {
            $this->send_request_to_dm($possible_obj, $possible_obj->get_so_no());
        }
    }

    public function get_so_dao()
    {
        return $this->_so_dao;
    }

    public function set_so_dao($new_dao)
    {
        $this->_so_dao = $new_dao;
    }

    public function send_request_to_dm($possible_order_obj_to_xml, $so_no)
    {
        $this->_cybersource_integrator->send_dm_request($this->debug, $possible_order_obj_to_xml, $request, $response);

        if ($request != null) {
            $this->get_sopql_srv()->add_log($so_no, "O", $request);
        }
        if ($response != null) {
            $payment_result = (array)$response;
            $afs_reply = (array)$payment_result['afsReply'];
            $decision_reply_rule_result = $payment_result["decisionReply"]->activeProfileReply->rulesTriggered->ruleResultItem;
            $ruleResultItem = $this->_extract_and_format_rule_result_item($decision_reply_rule_result);
            $save_text = $this->std_obj_to_string($response);
            $this->get_sopql_srv()->add_log($so_no, "I", $save_text);
            $smart_id = "";
            $device_fingerprint = (array)$afs_reply["deviceFingerprint"];

            if ($device_fingerprint) {
                if (!empty($device_fingerprint["smartID"])) {
                    $smart_id = $device_fingerprint["smartID"];
                }
            }

            $so_srv = $this->get_so_srv();
            if ($this->so = $so_srv->get(array("so_no" => $so_no))) {
                $need_credit_checks = TRUE;
                if ($payment_result['merchantReferenceCode'] == $so_no) {
                    $sor_data = array("risk_requested" => 1,
                        "risk_var1" => ($payment_result['decision'] . "|" . $payment_result['reasonCode']),
                        "risk_var4" => $afs_reply['afsFactorCode'],
                        "risk_var5" => $afs_reply['afsResult'],
                        "risk_var6" => $afs_reply['suspiciousInfoCode'],
                        "risk_var7" => $afs_reply['velocityInfoCode'],
                        "risk_var8" => $afs_reply['internetInfoCode'] . "|" . $afs_reply['ipCountry'],
                        "risk_var9" => $smart_id,
                        "risk_var10" => $ruleResultItem
                    );
                    $this->sor_update($sor_data);
                    if ($payment_result['decision'] == "REJECT") {
                        /*
                                                $socc_data = array("fd_status" => 2);
                                                $this->create_socc($socc_data);
                                                $this->so->set_status(0);
                        */
                        $this->so->set_status(2);
                        if ($this->so->get_hold_status() != 1)
                            $this->so->set_hold_status(0);
                        $so_srv->get_dao()->update($this->so);
                        mail("compliance@valuebasket.com", 'DM REJECT [VB]:' . $so_no, $save_text, 'From: website@valuebasket.com');
                    } else {
                        $need_credit_checks = $this->_need_credit_check_after_dm($payment_result['decision'], $afs_reply['afsResult']);
                    }
                    if ($need_credit_checks === FALSE) {
                        $this->so->set_status(3);
                        if ($this->so->get_hold_status() != 1)
                            $this->so->set_hold_status(0);
                        $this->get_so_srv()->get_dao()->update($this->so);
                    }
                } else {
                    $sor_data = array("risk_requested" => 2);
                    $this->sor_update($sor_data);
                    $this->_alert_it('No such SKU DM [VB]:' . $so_no, $save_text);
                }
            }
            if ($payment_result['decision'] == "ERROR") {
                $this->_alert_it('ERROR IN DM [VB]:' . $so_no, $save_text);
            }
        }
    }

    private function _extract_and_format_rule_result_item($resultItems)
    {
        $outputString = "";
        if ($resultItems->name) {
            $outputString .= $resultItems->name . "||" . $resultItems->decision . "||" . $resultItems->evaluation;
        } else {
            if ($resultItems) {
                foreach ($resultItems as $item) {
                    $outputString .= $item->name . "||" . $item->decision . "||" . $item->evaluation . "&&";
                }
                if ($outputString != "")
                    $outputString = substr($outputString, 0, (strlen($outputString) - 2));
            }
        }
        return $outputString;
    }

    private function _need_credit_check_after_dm($decision, $score)
    {
        $this->client = $this->get_client_srv()->get(array("id" => $this->so->get_client_id()));
        $this->sops = $this->get_so_srv()->get_sops_dao()->get(array("so_no" => $this->so->get_so_no()));

        if ($this->sops->get_payment_gateway_id() == 'paypal')
            return $this->_need_credit_check_after_dm_paypal_order($decision, $score);
        else if ($this->sops->get_payment_gateway_id() == 'yandex')
            return $this->_need_credit_check_after_dm_yandex_order($decision, $score);
        else
            return true;
    }

    public function _need_credit_check_after_dm_paypal_order($decision, $score)
    {
        if ($this->so->get_currency_id() == "RUB") {
            if (($decision == "REVIEW") && ($score > 50)) {
                return TRUE;
            } else
                return FALSE;
        }
    }

    public function _need_credit_check_after_dm_yandex_order($decision, $score)
    {
        if ($this->sops->get_card_id() == "AC") {
//credit card
            if (($decision == "REVIEW") && ($score > 70)) {
                return TRUE;
            } else if ($this->so->get_amount() >= 3700) {
                return TRUE;
            } else
                return FALSE;
        } else {
//e-wallet
            if (($decision == "REVIEW") && ($score > 50)) {
                return TRUE;
            } else
                return FALSE;
        }
    }

    public function _alert_it($subject, $message)
    {
        mail($this->get_technical_support_email(), $subject, $message, 'From: website@valuebasket.com');
    }

    public function risk_indictor_risk1($input)
    {
        $array_review = array('REVIEW');
        $array_bad = array('REJECT');
        $array_good = array('ACCEPT');
        $array_unknown = array('ERROR');
        $result = array();
        $decision = explode("|", $input);
        $result[0] = $this->_get_general_color_style($decision[0], $array_review, $array_bad, $array_good, $array_unknown);
        $result[0]["value"] = $input;
        return $result;
    }

    private function _get_general_color_style($input, $array_review, $array_bad, $array_good, $array_unknown)
    {
        if (is_null($input) || empty($input))
            return null;
        $result = array("value" => $input, "style" => "normal");
        if (in_array($input, $array_bad)) {
            $result["style"] = "bad";
        } elseif (in_array($input, $array_review)) {
            $result["style"] = "review";
        } elseif (in_array($input, $array_good)) {
            $result["style"] = "good";
        } elseif (in_array($input, $array_unknown)) {
            $result["style"] = "unknown";
        }

        return $result;
    }

    public function risk_indictor_avs_risk2($input)
    {
        $array_review = array('A', 'B', 'F', 'H', 'K'
        , 'L', 'O', 'P', 'T', 'W'
        , 'Z');
        $array_bad = array('C', 'I', 'N');
        $array_good = array('D', 'J', 'M', 'V', 'X', 'Y');
        $array_unknown = array('2');
        $result = array();
        $result[0] = $this->_get_general_color_style($input, $array_review, $array_bad, $array_good, $array_unknown);
        return $result;
    }

    public function risk_indictor_cvn_risk3($input)
    {
        $array_review = array();
        $array_bad = array('D', 'I', 'N');
        $array_good = array('M');
        $array_unknown = array('2');
        $result = array();
        $result[0] = $this->_get_general_color_style($input, $array_review, $array_bad, $array_good, $array_unknown);
        return $result;
    }

    public function risk_indictor_afs_factor_risk4($input)
    {
        $array_review = array('B', 'C', 'D', 'G', 'H'
        , 'I', 'N', 'U', 'Z');
        $array_bad = array('A', 'F', 'O', 'Q', 'R'
        , 'V', 'W');
        $array_good = array('E');
        $array_unknown = array();

        $values = explode('^', $input);
        $result = array();
        for ($i = 0; $i < sizeof($values); $i++) {
            $result[$i] = $this->_get_general_color_style($values[$i], $array_review, $array_bad, $array_good, $array_unknown);
        }
        return $result;
    }

    public function risk_indictor_score_risk5($input)
    {
        $result[0] = array("value" => $input, "style" => "normal");
        return $result;
    }

    public function risk_indictor_suspicious_risk6($input)
    {
        $array_review = array('MUL-EM', 'NON-BC', 'NON-FN', 'NON-LN');
        $array_bad = array('BAD-FP', 'MM-TZTLO', 'OBS-BC', 'OBS-EM', 'RISK-AVS'
        , 'RISK-BIN', 'RISK-DEV', 'RISK-PIP', 'RISK-TIP');
        $array_good = array('E');
        $array_unknown = array();
        $result = array();
        $result[0] = $this->_get_general_color_style($input, $array_review, $array_bad, $array_good, $array_unknown);
        return $result;
    }

    public function risk_indictor_velocity_risk7($input)
    {
        $array_review = array('VEL-NAME', 'VELI-CC', 'VELI-EM', 'VELI-IP', 'VELI-SA', 'VELI-TIP');
        $array_bad = array('VEL-ADDR', 'VELS-CC', 'VELS-EM', 'VELS-IP', 'VELS-SA', 'VELS-TIP');
        $array_good = array();
        $array_unknown = array();

        $values = explode('^', $input);
        $result = array();
        for ($i = 0; $i < sizeof($values); $i++) {
            $result[$i] = $this->_get_general_color_style($values[$i], $array_review, $array_bad, $array_good, $array_unknown);
        }
        return $result;
    }

    public function risk_indictor_internet_risk8($input)
    {
        $array_review = array('FREE-EM', 'INV-EM', 'MM-IPBC', 'UNV-EMBCO');
        $array_bad = array('RISK-EM', 'UNV-NID', 'UNV-RISK');
        $array_good = array();
        $array_unknown = array();

        $result = array();
        $separate_ip = explode('|', $input);
        if (sizeof($separate_ip) > 1) {
            $ip = $separate_ip[1];
        } else
            $ip = "";

        $values = explode('^', $separate_ip[0]);
        for ($i = 0; $i < sizeof($values); $i++) {
            $result[$i] = $this->_get_general_color_style($values[$i], $array_review, $array_bad, $array_good, $array_unknown);
        }
        if ($ip != "")
            $result[$i] = array("value" => $ip, "style" => "normal");
        return $result;
    }
}

/* End of file payment_gateway_redirect_cybersource_service.php */
/* Location: ./system/application/libraries/service/Payment_gateway_redirect_cybersource_service.php */
