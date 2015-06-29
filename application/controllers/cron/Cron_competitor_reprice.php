<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_competitor_reprice extends MY_Controller
{
    private $app_id="CRN0020";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/competitor_model');
    }

    public function reprice($platform_id = "WEBGB", $echo_file=0, $debug_sku="")
    {
        # echo_file = 0 : will do actual reprice and send report emails
        # echo_file = 1 : debug; prompt csv report download; no reprice done
        # echo_file = 2 : debug;  echo debug msg in browser; no reprice done
        $this->competitor_model->reprice($platform_id, $echo_file, $debug_sku);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

}