<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Moneybookers/ctpe_integrator.php");

class Payment_gateway_redirect_moneybookers_ctpe_service extends Payment_gateway_redirect_service
{
    public $customized_css = null;
    public $customized_javascript = null;
    private $_pmgw_return_code;
    private $_ctpe_integrator;

    public function __construct($debug)
    {
        parent::__construct($debug);
        $this->customized_css = "https://{$_SERVER['HTTP_HOST']}/css/moneybookers_ctpe.css";
        $this->customized_javascript = "https://{$_SERVER['HTTP_HOST']}/js/moneybookers_ctpe.js";
        if ($this->debug) {
            $this->_ctpe_integrator = new Ctpe_integrator(
                Ctpe_integrator::CTPE_TEST_PAYMENT_SERVER,
                Ctpe_integrator::CTPE_ACTION_PAGE,
                Ctpe_integrator::CTPE_TEST_SECURITY_SENDER,
                Ctpe_integrator::CTPE_TEST_TRANSACTION_CHANNEL,
                Ctpe_integrator::CTPE_TEST_USER_LOGIN_ID,
                Ctpe_integrator::CTPE_TEST_USER_PASSWORD,
                Ctpe_integrator::CTPE_TEST_SECURITY_TOKEN,
                Ctpe_integrator::CTPE_TEST_TRANSACTION_MODE,
                "SYNC"
            );
        } else {
            $this->_ctpe_integrator = new Ctpe_integrator(
                Ctpe_integrator::CTPE_PAYMENT_SERVER,
                Ctpe_integrator::CTPE_ACTION_PAGE,
                Ctpe_integrator::CTPE_SECURITY_SENDER,
                Ctpe_integrator::CTPE_TRANSACTION_CHANNEL,
                Ctpe_integrator::CTPE_USER_LOGIN_ID,
                Ctpe_integrator::CTPE_USER_PASSWORD,
                Ctpe_integrator::CTPE_SECURITY_TOKEN,
                Ctpe_integrator::CTPE_TRANSACTION_MODE,
                "SYNC"
            );
        }
    }

    public function get_payment_gateway_name()
    {
        return "moneybookers_ctpe";
    }

    public function prepare_get_url_request($payment_info = array(), &$request_data)
    {
        $bill_address = str_replace("|", " ", $this->so->get_bill_address());
        $bill_firstname = $this->client->get_forename();
        $bill_surname = $this->client->get_surname();

        $this->_ctpe_integrator->setPaymentInformation($this->so->get_amount(), $this->so->get_so_no(), $this->so->get_client_id() . "-" . $this->so->get_so_no(), $this->so->get_currency_id());
        $this->_ctpe_integrator->setCustomerContact($this->client->get_email(), '', $this->client->get_tel_1(), $_SERVER["REMOTE_ADDR"]);
        $this->_ctpe_integrator->setCustomerAddress($bill_address, $this->so->get_bill_postcode(), $this->so->get_bill_city(), $this->so->get_bill_state(), $this->so->get_bill_country_id());
        $this->_ctpe_integrator->setCustomerName('', '', $bill_firstname, $bill_surname, $this->so->get_bill_company());
        $this->_ctpe_integrator->setWPFparams('true', 'false', 'DEFAULT', 'en', $this->get_payment_response_page());
//      $this->_ctpe_integrator->setPaymentCode("CC.DB");
        $this->_ctpe_integrator->setPaymentMethod($payment_info["card_type"]);

        $this->_ctpe_integrator->setUiCustomization($this->customized_css, $this->customized_javascript);
        $request_data = $this->array_implode('=', ',', $this->_ctpe_integrator->params);
        return $this->_ctpe_integrator->params;
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

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw)
    {
        $transaction_id = $input_parameters['transaction_id'];
        $input_params = array("transaction_id" => $transaction_id);
        $this->_ctpe_integrator->setServer(Ctpe_integrator::CTPE_TEST_QUERY_SERVER, Ctpe_integrator::CTPE_QUERY_ACTION_PAGE);
        $request_xml = $this->_ctpe_integrator->form_xml_query($input_params);

//      var_dump($request_xml);
        $data_to_pmgw = $request_xml;

        $trycount = 0;
        do {
            $output = $this->_ctpe_integrator->queryToCtpe($request_xml);
            $trycount++;
        } while (($trycount < 2) && ((!$output) || (empty($output))));

//      print $output;
        if ($output) {
            $simpleXml = new SimpleXMLElement($output);
        }
        $number_of_result = (string)$simpleXml->Result['count'];
        $data_from_pmgw = $output;

        for ($i = 0; $i < $number_of_result; $i++) {
            if ((string)$simpleXml->Result->Transaction[$i]["source"] == "SYSTEM") {
                if (((string)$simpleXml->Result->Transaction[$i]->Processing->Result == "ACK")
                    &&
                    ((((string)$simpleXml->Result->Transaction[$i]->Processing->Status['code'] == "00")
                            && ((string)$simpleXml->Result->Transaction[$i]->Processing->Reason['code'] == "00"))
                        ||
                        (((string)$simpleXml->Result->Transaction[$i]->Processing->Status['code'] == "90")
                            && ((string)$simpleXml->Result->Transaction[$i]->Processing->Reason['code'] == "00")))
                ) {
                    $this->_pmgw_return_code = (string)$simpleXml->Result->Transaction[$i]->Processing->Return["code"];
//input more data into dtabase, success payment
                    $this->socc_add(array("card_last4" => ltrim((string)$simpleXml->Result->Transaction[$i]->Account->Number, "*")
                    , "card_exp_month" => (string)$simpleXml->Result->Transaction[$i]->Account->Expiry['month']
                    , "card_exp_year" => (string)$simpleXml->Result->Transaction[$i]->Account->Expiry['year']));
                    return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
                } else
                    return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
            }
        }
        return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
    }

    public function process_payment_status($general_data, $get_data, &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
    {
        $result = $general_data['PROCESSING_RESULT'];
        $user_cancel_payment = $general_data['FRONTEND_REQUEST_CANCELLED'];
        $transaction_id = $general_data['IDENTIFICATION_TRANSACTIONID'];
        $transaction_arr = explode('-', $transaction_id);
        $so_number = $transaction_arr[1];
        $client_id = $transaction_arr[0];

        $this->_pmgw_return_code = $general_data['PROCESSING_RETURN_CODE'];
        $data_from_pmgw = $this->array_implode('=', ',', $general_data);
        $sor_data = null;

        if ($result) {
            $sops_data = array();
            $sops_data['risk_ref1'] = $general_data['PROCESSING_RISK_SCORE'];
            $sops_data['remark'] = $general_data['PROCESSING_RETURN_CODE'] . "|" . $general_data['PROCESSING_CODE'];
            $sops_data['pay_date'] = $general_data['PROCESSING_TIMESTAMP'];
            $so_data['txn_id'] = $general_data['IDENTIFICATION_UNIQUEID'];

            if (($result == "NOK") && ($user_cancel_payment == "true")) {
                return Payment_gateway_redirect_service::PAYMENT_STATUS_CANCEL;
            } else if (strstr($result, "ACK")) {
                $data_to_pmgw = $this->_get_successful_page_with_so_no($so_number);
                return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
            } else {
                return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
            }
        }

        return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
    }

    private function _get_successful_page_with_so_no($so_number)
    {
        $debug_string = ($this->debug) ? "?debug=1" : "";
        $url = $this->successful_page . $so_number . $debug_string;
        return $url;
    }

    public function process_success_action()
    {
// send confirmation email
        $this->fire_success_event();
        print $this->_get_successful_page_with_so_no($this->so->get_so_no());
        return true;
    }

    public function process_failure_action()
    {
        print $this->get_failure_page();
        return true;
    }

    public function process_cancel_action()
    {
        print $this->get_cancel_page();
        return true;
    }

    public function is_payment_need_credit_check()
    {
        if (($this->_pmgw_return_code == "000.400.000")
            || ($this->_pmgw_return_code == "000.400.010")
            || ($this->_pmgw_return_code == "000.400.020")
            || ($this->_pmgw_return_code == "000.400.030")
            || ($this->_pmgw_return_code == "000.400.040")
            || ($this->_pmgw_return_code == "000.400.050")
        )
            return true;
        else {
            return false;
        }
    }

    public function get_technical_support_email()
    {
        return "oswald@eservicesgroup.net";
    }
}



