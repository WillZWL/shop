<?php

class Cron_update_so extends MY_Controller
{
    private $appId = "CRN0033";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/so_model');
    }

    public function update_so_item_unit_cost()
    {
        $this->so_model->so_service->update_empty_so_item_cost();
    }

    public function getAppId()
    {
        return $this->appId;
    }
}


