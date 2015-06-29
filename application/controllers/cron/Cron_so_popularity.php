<?php

class Cron_so_popularity extends MY_Controller
{
    private $app_id = "CRN0010";

    function __construct()
    {
        parent::__construct();

        $this->load->model('order/so_model');
    }

    public function index()
    {
        $so_item = $this->so_model->so_service->get_soi_dao();
        #var_dump($so_item);
        if (!$so_item->calculate_popularity(7)) echo "FAILED";
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

}