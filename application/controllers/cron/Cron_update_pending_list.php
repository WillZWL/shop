<?php

class Cron_update_pending_list extends MY_Controller
{
    private $app_id = "CRN0002";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('website/checkout_model');
    }

    public function index($pmgw = "global_collect", $debug = 0)
    {
        $this->payment_gateway_service->init_pmgw_srv($pmgw);
        $this->payment_gateway_service->get_pmgw_srv()->update_pending_list($debug);
    }

    public function update_altapay_pending_list($debug = 0)
    {
        include_once(APPPATH . "models/website/checkout_redirect_altapay_model.php");
        $this->checkout_model = new Checkout_redirect_altapay_model($debug);
        $this->checkout_model->update_pending_list();
    }

    public function query_altapay($so_no, $debug = 0)
    {
        include_once(APPPATH . "models/website/checkout_redirect_altapay_model.php");
        $this->checkout_model = new checkout_redirect_altapay_model($debug);
        $this->checkout_model->query_transaction_in_general($so_no);
    }

    public function update_global_collect_pending_list($debug = 0)
    {
        include_once(APPPATH . "models/website/checkout_redirect_global_collect_model.php");
        $this->checkout_model = new Checkout_redirect_global_collect_model($debug);
        $this->checkout_model->update_pending_list();
    }

    public function query_global_collect($so_no, $debug = 0)
    {
        include_once(APPPATH . "models/website/checkout_redirect_global_collect_model.php");
        $this->checkout_model = new Checkout_redirect_global_collect_model($debug);
        $this->checkout_model->query_transaction_in_general($so_no);
    }

    public function update_inpendium_pending_list($debug = 0)
    {
        include_once(APPPATH . "models/website/checkout_redirect_inpendium_ctpe_model.php");
        $this->checkout_model = new Checkout_redirect_inpendium_ctpe_model($debug);
        $this->checkout_model->update_pending_list($so_no);
    }

    public function query_inpendium($so_no, $debug = 0)
    {
        include_once(APPPATH . "models/website/checkout_redirect_inpendium_ctpe_model.php");
        $this->checkout_model = new Checkout_redirect_inpendium_ctpe_model($debug);
        $this->checkout_model->query_transaction_in_general($so_no);
    }

    public function payment_verificaiton($debug = 0)
    {
        include_once(APPPATH . "models/website/checkout_redirect_inpendium_ctpe_model.php");
        $this->checkout_model = new Checkout_redirect_inpendium_ctpe_model($debug);
        $this->checkout_model->payment_verificaiton();
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}



