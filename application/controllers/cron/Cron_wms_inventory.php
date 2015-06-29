<?php

class Cron_wms_inventory extends MY_Controller
{
    private $app_id = "CRN0001";

    function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/wms_inventory_model');
        $this->load->helper('url');
    }

    function get_inventory()
    {
        $this->wms_inventory_model->get_inventory();
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}


