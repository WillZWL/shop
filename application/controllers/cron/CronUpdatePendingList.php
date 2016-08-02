<?php

class CronUpdatePendingList extends MY_Controller
{
    private $appId = "CRN0002";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($pmgw = "GlobalCollect", $debug = 0)
    {
        $gatewayServieName = "\ESG\Panther\Service\PaymentGatewayRedirect" . ucfirst($pmgw) . "Service";
        $gatewayObj = new $gatewayServieName(null, $debug);
        $gatewayObj->updatePendingList($debug);
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

    public function getAppId()
    {
        return $this->appId;
    }
}



