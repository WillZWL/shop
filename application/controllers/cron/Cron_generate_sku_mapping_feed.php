<?php

class Cron_generate_sku_mapping_feed extends MY_Controller
{
    private $appId = "CRN0004";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/sku_mapping_feed_model');
    }

    function generate_sku_mapping($need_all_sku)
    {
        $this->sku_mapping_feed_model->generate_sku_mapping_difference($need_all_sku);
//      $this->load->view('index');
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
