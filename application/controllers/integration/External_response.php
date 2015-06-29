<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
class External_response extends PUB_Controller
{
    // this is for external parties
    // no login to admincentre needed, but still suscepted to htaccess
    function __construct()
    {
        parent::PUB_Controller();
        // $this->load->model('marketing/external_query_model');

        // Payment_gateway_redirect_adyen_service
        $this->load->library('service/payment_gateway_redirect_adyen_service');

    }

    public function adyen_notification()
    {
        return $this->payment_gateway_redirect_adyen_service->payment_notification($_POST);
    }


}