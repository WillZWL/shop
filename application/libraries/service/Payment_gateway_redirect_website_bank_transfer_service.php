<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Moneybookers/ctpe_integrator.php");

class Payment_gateway_redirect_website_bank_transfer_service extends Payment_gateway_redirect_service
{
    public $customized_css = null;
    public $customized_javascript = null;
    private $_pmgw_return_code;

    public function __construct($debug)
    {
        parent::__construct($debug);
    }

    public function get_payment_gateway_name()
    {
        return "w_bank_transfer";
    }

    public function prepare_get_url_request($payment_info = array(), &$request_data)
    {
        /*
            for other external payment_gateways, e.g. moneybookers, this is where we redirect to payment_gateways,
            then redirect back to send email.
            Since this is website_bank_transfer, we send acknowledgement email with payment instructions
        */
        $this->fire_collect_payment_event("acknowledge_order");
        return "";
    }

    public function get_redirect_url($request_data = "", &$response_data)
    {
        return base_url() . "checkout_redirect_method/order_acknowledge_frame";
    }

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
    {
        return Payment_gateway_redirect_service::PAYMENT_NO_STATUS;
    }

    public function process_payment_status($general_data, $get_data, &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
    {
        return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
    }

    public function is_need_dm_service($is_fraud = false)
    {
        return parent::require_decision_manager($is_fraud);
    }

    public function process_success_action()
    {
// send confirmation email
        // $this->fire_success_event();
        // print $this->_get_successful_page_with_so_no($this->so->get_so_no());
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

    public function is_payment_need_credit_check($is_fraud = false)
    {
        return parent::is_payment_need_credit_check($is_fraud);
    }

    public function get_technical_support_email()
    {
        return "oswald@eservicesgroup.net";
    }

    private function _get_successful_page_with_so_no($so_number)
    {
        $debug_string = ($this->debug) ? "?debug=1" : "";
        $url = $this->successful_page . $so_number . $debug_string;
        return $url;
    }
}

