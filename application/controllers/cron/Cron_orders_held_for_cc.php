<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_orders_held_for_cc extends MY_Controller
{
    private $appId = "CRN0031";

    function __construct()
    {
        parent::__construct();
        $this->load->model('order/order_held_for_cc_model');
    }

    public function cron_cc_order_held_report($duration = null)
    {

        $this->order_held_for_cc_model->send_report($duration);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}