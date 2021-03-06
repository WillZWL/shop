<?php

include_once(APPPATH . "models/website/checkout_redirect_model.php");
include_once(APPPATH . "libraries/service/payment_gateway_redirect_cybersource_service.php");

class Checkout_redirect_cybersource_model extends Checkout_redirect_model
{
    public function __construct($debug = 0)
    {
        parent::__construct($debug);
    }

    public function set_pmgw_service()
    {
        $this->pmgw_redirect_service = new Payment_gateway_redirect_cybersource_service($this->debug);
    }

    public function payment_notification($input_data)
    {
        $this->pmgw_redirect_service->payment_notification($input_data);
    }
}
