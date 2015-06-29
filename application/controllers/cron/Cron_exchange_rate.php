<?php

class Cron_exchange_rate extends MY_Controller
{
    private $app_id = "CRN0006";

    function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/exchange_rate_model');
        $this->load->helper('url');
    }

    function index()
    {
        $this->upload_exchange_rate();
    }

    function upload_exchange_rate()
    {
        $this->exchange_rate_model->upload_exchange_rate();
    }

    function update_exchange_rate_from_cv()
    {
        $this->exchange_rate_model->update_exchange_rate_from_cv();
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file cCron_exchange_rate.php */
/* Location: ./app/controllers/cron_exchange_rate.php */
