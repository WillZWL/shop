<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_website_bank_transfer extends MY_Controller
{
    private $appId = "CRN0021";

    function __construct()
    {
        parent::__construct();
        $this->load->model('order/website_bank_transfer_model');
    }

    public function payment_reminder($platform_id = "WEBES")
    {
        $this->website_bank_transfer_model->payment_reminder($platform_id);
    }

    public function cancel_unpaid($platform_id = "WEBES")
    {
        $this->website_bank_transfer_model->cancel_unpaid($platform_id);
    }

    public function getAppId()
    {
        return $this->appId;
    }

}