<?php

class Cron_surplus_oos_email extends MY_Controller
{
    private $appId = 'CRN0030';

    function __construct()
    {
        parent::__construct();
        $this->load->library('service/surplus_email_service');
    }

    public function send_surplus_oos_email()
    {
        $this->surplus_email_service->send_surplus_oos_email();
    }

    public function getAppId()
    {
        return $this->appId;
    }
}



