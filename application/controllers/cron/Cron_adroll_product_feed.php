<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_adroll_product_feed extends MY_Controller
{
    private $appId = "CRN0032";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/adroll_product_feed_model');
    }

    public function cron_adroll_feed($platform_id = null)
    {

        $this->adroll_product_feed_model->send_report($platform_id);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}