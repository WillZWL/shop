<?php

include_once(APPPATH . "models/website/checkout_redirect_model.php");
include_once(APPPATH . "libraries/service/payment_gateway_redirect_moneybookers_ctpe_service.php");

class Checkout_redirect_moneybookers_ctpe_model extends Checkout_redirect_model
{
    public function __construct($debug = 0)
    {
        parent::__construct($debug);
//      $this->load->library('service/payment_gateway_redirect_moneybookers_ctpe_service');
    }

    public function set_pmgw_service()
    {
        $this->pmgw_redirect_service = new Payment_gateway_redirect_moneybookers_ctpe_service($this->debug);
    }
/*
    public function process_payment_status($general_data = array(), $get_data = array())
    {
        return $this->pmgw_redirect_service->process_payment_status($general_data, $get_data);
    }
*/
}
