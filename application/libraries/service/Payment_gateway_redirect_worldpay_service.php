<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Worldpay/worldpay_integrator.php");

class Payment_gateway_redirect_worldpay_service extends Payment_gateway_redirect_service
{
    private $_worldpay_integrator;

    public function __construct($debug)
    {
        parent::__construct($debug);
        $this->_worldpay_integrator = new Worldpay_integrator();
    }

    public function get_payment_gateway_name()
    {
        return "worldpay";
    }

    public function prepare_get_url_request($payment_info = array(), &$request_data)
    {
        $this->_config_payment_gateway();

        $xmlInformation = array();
        $xmlInformation['orderId'] = $this->so->get_client_id() . "-" . $this->so->get_so_no();
        $xmlInformation['description'] = date("Y-m-d H:i:s");
        $xmlInformation['amount'] = number_format($this->so->get_amount(), 2, '.', '');
        $xmlInformation['currency'] = strtoupper($this->so->get_currency_id());
        $xmlInformation['firstName'] = $this->client->get_forename();
        $xmlInformation['email'] = $this->client->get_email();
        $xmlInformation['lastName'] = $this->client->get_surname();
        $xmlInformation['postalCode'] = $this->so->get_bill_postcode();
        $address = explode("|", $this->so->get_bill_address());
        $xmlInformation['address1'] = $address[0];
        $xmlInformation['address2'] = (sizeof($address) > 1) ? $address[1] : "";
        $xmlInformation['address3'] = (sizeof($address) > 2) ? $address[2] : "";
        $xmlInformation['city'] = $this->so->get_bill_city();
        $xmlInformation['countryCode'] = $this->so->get_bill_country_id();
        $xmlInformation['tel'] = $this->client->get_tel_1();

        $attribute = array("preferredPaymentMethod" => $payment_info["card_type"], "language_id" => $payment_info["language_id"]);
        if ($xmlInformation['countryCode'] == "FR")
            $attribute['country_id'] = $xmlInformation['countryCode'];
        $this->_worldpay_integrator->worldpay_set_attribute($attribute);

//      $request_data = $this->array_implode('=', ',', $xmlInformation);
        $merchantCode = $this->_get_merchant_account_by_currency(strtoupper($this->so->get_currency_id()));
        $request_data =  $this->_worldpay_integrator->form_payment_xml($xmlInformation, $merchantCode["merchantCode"], $merchantCode['installationId']);
        return $request_data;
    }

    public function get_redirect_url($request_data, &$response_data)
    {
        $trycount = 0;
        $server_result = '';
        $server_error = '';
        $server_info = '';
        do
        {
            $this->_worldpay_integrator->send_data_to_wp($request_data, '', '', $server_result, $server_error, $server_info);
            $trycount++;
        }while (($trycount < 2) && ((!$server_result) || (empty($server_result))));
        $response_data = $server_result;

        if (empty($server_result) || ($server_result== ""))
        {
//log the error
            $response_data = $this->array_implode('=', ',', $server_error) . " info:" . $this->array_implode('=', ',', $server_info);
            return $this->keep_error_message("request timeout, please try again");
        }
        else
        {
            $parse_result = $this->_worldpay_integrator->get_submit_result($server_result);
            if (isset($parse_result->reply->error["code"]))
            {
                return $this->keep_error_message((string)$parse_result->reply->error["code"]);
            }
            else
            {
                $redirectUrl = (string)$parse_result->reply->orderStatus->reference . $this->_worldpay_integrator->get_url_parameters();
                $referenceId = (string)$parse_result->reply->orderStatus->reference['id'];
                $this->so->set_txn_id($referenceId);
                $this->get_so_srv()->get_dao()->update($this->so);
                return $redirectUrl;
            }
        }
        return $this->keep_error_message("cannot retrieve redirect url, please try again");
    }

    public function process_payment_status($general_data, $get_data, &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
    {
        $payment_result = $get_data['paymentStatus'];
        $transaction_arr = explode('-', $get_data["orderKey"]);
        $so_number = $transaction_arr[1];

//      print_r($general_data);
//      exit;
        $data_from_pmgw = $this->array_implode('=', ',', $get_data);
        $data_to_pmgw = null;
        $sor_data = null;

        if ($payment_result == "AUTHORISED")
        {
            return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
        }
        else if ($payment_result == 'REFUSED')
        {
            return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
        }
        else if ($get_data["result"] == "CANCEL")
        {
            return Payment_gateway_redirect_service::PAYMENT_STATUS_CANCEL;
        }
        return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
    }

    public function is_need_dm_service($is_fraud = false)
    {
        if (!$this->_use_wp_own_cc_dm_rule())
            return parent::require_decision_manager($is_fraud);
        else
            return false;
    }

    public function payment_notification($input_data)
    {
        $simpleXml = new SimpleXMLElement($input_data);
        $order = explode('-', $simpleXml->notify->orderStatusEvent ["orderCode"]);
        $so_no = $order[1];
        $so_srv = $this->get_so_srv();
        $process_success = false;

        if ($input_data)
            $this->get_sopl_srv()->add_log($so_no, "I", str_replace("&", "\n&", $input_data));

        if ($this->so = $so_srv->get(array("so_no"=>$so_no)))
        {
            $payment_result = (string)$simpleXml->notify->orderStatusEvent->payment->lastEvent;
            $card_type = (string)$simpleXml->notify->orderStatusEvent->payment->paymentMethod;
            $card_number = (string)$simpleXml->notify->orderStatusEvent->payment->cardNumber;
            $risk_score = (string)$simpleXml->notify->orderStatusEvent->payment->riskScore["value"];
            if (isset($simpleXml->notify->orderStatusEvent->payment->ThreeDSecureResult))
            {
                $threeDResult = (string) $simpleXml->notify->orderStatusEvent->payment->ThreeDSecureResult["description"];
            }
            else
                $threeDResult = "";

            if (((substr($card_type, 0, 4) == "VISA")
                || (substr($card_type, 0, 4) == "ECMC"))
                && (strlen($card_number) > 15))
            {
                $card_bin = substr($card_number, 0, 4);
                $card_last_digits = substr($card_number, 12, 4);;
            }
            else
            {
                $card_bin = "";
                $card_last_digits = "";
            }

            if ($payment_result == "AUTHORISED")
            {
                $sops_dao = $this->get_so_srv()->get_sops_dao();
                $this->sops = $sops_dao->get(array("so_no"=>$so_no));
                $sops_data = array("risk_ref1"=>$risk_score,
                                    "risk_ref2"=>$threeDResult,
                                    "pay_date"=>date("Y-m-d H:i:s")
                                    );
                $socc_data = array("card_type"=>$card_type,
                                    "card_last4"=>$card_last_digits,
                                    "card_bin"=>$card_bin);

                $this->payment_success_operation(array(), $sops_data, $socc_data);
                $this->fire_success_event();
                $process_success = true;
            }
            else if ($payment_result == "CANCELLED")
            {
                $sops_dao = $this->get_so_srv()->get_sops_dao();
                $this->sops = $sops_dao->get(array("so_no"=>$so_no));

                $holdAuto = "";
//check if payment is authorised before
                if (($this->sops->get_payment_status() == "S")
                    || ($this->sops->get_payment_status() == "D"))
                {
//change status if the record is not on hold by CS/logistic before
                    if ((($this->so->get_status() >= 2) && ($this->so->get_status() <= 6))
                        && ($this->so->get_refund_status() == 0)
                        && ($this->so->get_hold_status() == 0))
                    {
                        $holdAuto = "Auto hold by system, from success to cancel";

                        $order_note_obj = $this->order_note_dao->get();
                        $order_note_obj->set_so_no($this->so->get_so_no());
                        $order_note_obj->set_type('O');
                        $order_note_obj->set_note('Order payment status changed from success to cancel by WP, need compliance to verify');
                        $this->order_note_dao->insert($order_note_obj);

                        $hold_reason_obj = $this->so_hold_reason_dao->get();
                        $hold_reason_obj->set_so_no($this->so->get_so_no());
                        $hold_reason_obj->set_reason("change_of_address");
                        $this->so_hold_reason_dao->insert($hold_reason_obj);

                        $this->so->set_status(2);
                        $this->so->set_hold_status(1);
                        if(!$so_srv->update($this->so))
                        {
                            mail($this->get_technical_support_email(), 'Error: Authorised order change to Cancel in VB:' . $this->so->get_client_id() . '-' . $so_no , $holdAuto . "\n\nLogging:\n" . $input_data, 'From: website@valuebasket.com');
                        }
                    }
                    else
                    {
                        $holdAuto = "No change in status in the system, order was hold before";
                    }
    //if yes, put order on hold and send email to compliance to verify
                    mail('compliance@valuebasket.com', 'Authorised order change to Cancel in VB:' . $this->so->get_client_id() . '-' . $so_no , $holdAuto . "\n\nLogging:\n" . $input_data, 'From: website@valuebasket.com');
//                  mail($this->get_technical_support_email(), 'Authorised order change to Cancel in VB:' . $this->so->get_client_id() . '-' . $so_no , $holdAuto . "\n\nLogging:\n" . $input_data, 'From: website@valuebasket.com');
                }
                else
                {
    //just cancelled the order
                    $sops_data = array("risk_ref1"=>$risk_score);
                    $socc_data = array("card_type"=>$card_type,
                                        "card_last4"=>$card_last_digits,
                                        "card_bin"=>$card_bin);
                    $this->payment_cancel_operation(array(), $sops_data, $socc_data);
                }

                $process_success = true;
            }

            if ($process_success)
            {
                print "[OK]";
                return;
            }
        }
        print "fail";
    }

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
    {
        return TRUE;
    }

    public function process_success_action()
    {
        redirect($this->get_successful_page());
    }

    public function process_failure_action()
    {
        redirect($this->get_failure_page());
    }

    public function process_cancel_action()
    {
        redirect($this->get_cancel_page());
    }

    public function is_payment_need_credit_check($is_fraud = false)
    {
        if (!$this->_use_wp_own_cc_dm_rule())
            return parent::is_payment_need_credit_check($is_fraud);
        else
            return false;
    }

    public function get_technical_support_email()
    {
        return "oswald@eservicesgroup.net";
    }

    private function _use_wp_own_cc_dm_rule()
    {
        if ($this->sops)
        {
            if ($this->sops->get_risk_ref2())
            {
                if (strtolower($this->sops->get_risk_ref2()) == "cardholder authenticated")
                    return true;
                else
                    return false;
            }
            else
            {
                mail($this->get_technical_support_email(), '[VB] WP no sops in so:' . $this->so->get_client_id() . '-' . $this->so->get_so_no(), "Line:" . __LINE__ , 'From: website@valuebasket.com');
            }
        }
        else
        {
            mail($this->get_technical_support_email(), '[VB] WP no sops in so:' . $this->so->get_client_id() . '-' . $this->so->get_so_no(), "Line:" . __LINE__ , 'From: website@valuebasket.com');
            return false;
        }
    }

    private function _config_payment_gateway()
    {
        $merchant_information = $this->_get_merchant_account_by_currency(strtoupper($this->so->get_currency_id()));
        $this->_worldpay_integrator->worldpay_config('', $merchant_information['merchantCode'], $merchant_information['merchantPassword'], $this->debug);
        $appearance = array();
        $appearance['successUrl'] = $this->get_payment_response_page() . "&result=OK";
        $appearance['failureUrl'] = $this->get_payment_response_page() . "&result=NOK";
        $appearance['cancelUrl'] = $this->get_payment_response_page() . "&result=CANCEL";
        $this->_worldpay_integrator->worldpay_set_attribute($appearance);
    }

    private function _get_merchant_account_by_currency($inputCurrency)
    {
        $merchantInformation = array();
        $merchantInformation['installationId'] = Worldpay_integrator::WORLD_PAY_INSTALLATION_ID_OTHER;
        if ($inputCurrency == 'GBP')
        {
            $merchantInformation['merchantCode'] = Worldpay_integrator::WORLD_PAY_MERCHANT_CODE_GBP;
            $merchantInformation['merchantPassword'] = Worldpay_integrator::WORLD_PAY_MERCHANT_PASSWORD_GBP;
        }
        else if ($inputCurrency == 'AUD')
        {
            $merchantInformation['merchantCode'] = Worldpay_integrator::WORLD_PAY_MERCHANT_CODE_AUD;
            $merchantInformation['merchantPassword'] = Worldpay_integrator::WORLD_PAY_MERCHANT_PASSWORD_AUD;
        }
        else if ($inputCurrency == 'EUR')
        {
            $merchantInformation['merchantCode'] = Worldpay_integrator::WORLD_PAY_MERCHANT_CODE_EUR;
            $merchantInformation['merchantPassword'] = Worldpay_integrator::WORLD_PAY_MERCHANT_PASSWORD_EUR;
        }
        else if ($inputCurrency == 'USD')
        {
            $merchantInformation['merchantCode'] = Worldpay_integrator::WORLD_PAY_MERCHANT_CODE_USD;
            $merchantInformation['merchantPassword'] = Worldpay_integrator::WORLD_PAY_MERCHANT_PASSWORD_USD;
        }
        else if ($inputCurrency == 'NZD')
        {
            $merchantInformation['merchantCode'] = Worldpay_integrator::WORLD_PAY_MERCHANT_CODE_NZD;
            $merchantInformation['merchantPassword'] = Worldpay_integrator::WORLD_PAY_MERCHANT_PASSWORD_NZD;
        }
        else if ($inputCurrency == 'SGD')
        {
            $merchantInformation['merchantCode'] = Worldpay_integrator::WORLD_PAY_MERCHANT_CODE_SGD;
            $merchantInformation['merchantPassword'] = Worldpay_integrator::WORLD_PAY_MERCHANT_PASSWORD_SGD;
        }
        else if (($inputCurrency == 'HKD')
                || ($inputCurrency == 'MYR')
                || ($inputCurrency == 'CHF'))
        {
            $merchantInformation['merchantCode'] = Worldpay_integrator::WORLD_PAY_MERCHANT_CODE_HKD;
            $merchantInformation['merchantPassword'] = Worldpay_integrator::WORLD_PAY_MERCHANT_PASSWORD_HKD;
        }
        return $merchantInformation;
    }
}

/* End of file payment_gateway_redirect_worldpay_service.php */
/* Location: ./system/application/libraries/service/Payment_gateway_redirect_worldpay_service.php */
