<?php

class All_orders extends MY_Controller
{
    private $_app_id = "ORD0021";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/all_orders_model');
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");

        #SBF #2768 add search filters
        $data["pmgw_list"] = $this->sc['PaymentGateway']->getDao('PaymentGateway')->getList([], ['orderby' => 'id', 'limit' => -1]); #payment_gateway
        $data["order_type_list"] = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList([], ['limit' => -1]); #from platform_biz_var
        $data["currency_list"] = $this->sc['Currency']->getDao('Currency')->getList([], ['limit' => -1]); #from platform_biz_var
        $data["so_hold_reason_list"] = $this->sc['So']->getDao('SoHoldReason')->getReasonList();
        // echo "<pre>"; var_dump($data["currency_list"]);die();

        if ($_POST["post"]) {
            $order_type = trim($_POST["order_type"]);
            $psp_gateway = trim($_POST["psp_gateway"]);
            $hold_reason = trim($_POST["hold_reason"]);
            $currency = trim($_POST["currency"]);
            $start_date = trim($_POST["start_date"]);
            $end_date = trim($_POST["end_date"]);
            $so_number = trim($_POST["so_number"]);
            if (!empty($so_number)) {
                $so_number = strtoupper($so_number);
            }
        }

        $data["lang"] = $lang;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['so_number'] = $so_number;

        # if we already have a POST value, then let value stay in option
        $data['select_order_type'] = $order_type;
        $data['select_psp_gateway'] = $psp_gateway;
        $data['select_hold_reason'] = $hold_reason;
        $data['select_currency'] = $currency;


        if ($_POST["exportSubmit"]) {
            $data['output'] = $this->sc['AllOrders']->getAllOrdersExportReport($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
            $data['filename'] = $this->sc['AllOrders']->getExportFilename($start_date, $end_date);
            $this->load->view('output_csv.php', $data);
        } else {
            $data['heading'] = explode(",", $this->sc['AllOrders']->getReportHeading());
            if ($_POST["post"]) {
                $data['orders'] = $this->sc['AllOrders']->getAllOrdersReport($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
            }
            $this->load->view('order/all_orders/index', $data);
        }
    }

    public function getAppId()
    {
        return $this->_app_id;
    }

}
