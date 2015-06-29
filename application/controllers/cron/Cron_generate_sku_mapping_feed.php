<?php
class Cron_generate_sku_mapping_feed extends MY_Controller
{
    private $app_id="CRN0004";

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

    public function _get_app_id()
    {
        return $this->app_id;
    }
}
