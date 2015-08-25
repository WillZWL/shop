<?php

class Cron_payment_gateway extends MY_Controller
{
    private $appId = "CRN0023";

    public function __construct()
    {
        parent::__construct();
//      $this->load->model('order/affiliate_order_model');
    }

    public function send_yandex_refund_order($debug = 0, $so_no = null)
    {
        include_once(APPPATH . "models/integration/auto_refund_model.php");
        $auto_refund_model = new Auto_refund_model("yandex", $debug);

        $auto_refund_model->refund_orders($so_no);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
