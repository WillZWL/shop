<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_fireman extends MY_Controller
{
    private $app_id="CRN0026";

    function __construct()
    {
        parent::__construct();
        $this->load->model('order/fireman_model');
    }

    public function cron_fireman_report($type = null)
    {
        if ($type != null)
            $this->fireman_model->send_report($type);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}
