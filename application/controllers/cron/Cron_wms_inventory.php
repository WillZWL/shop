<?php

class Cron_wms_inventory extends MY_Controller
{
    private $appId = "CRN0001";

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

    public function getAppId()
    {
        return $this->appId;
    }
}


