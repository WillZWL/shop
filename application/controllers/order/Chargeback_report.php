<?php

class Chargeback_report extends MY_Controller
{
    private $appId = "ORD0029";
    private $lang_id = "en";

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $_SESSION["LISTPAGE"] = base_url() . "order/chargeback_report/?" . $_SERVER['QUERY_STRING'];
        $data["lang"] = $lang;
        $filter = array();
        if ($this->input->post("search")) {
            $filter["platform_id"] = trim($_POST["platform"]);
            $filter["order_start_date"] = trim($_POST["orderstart"]);
            $filter["order_end_date"] = trim($_POST["orderend"]);
            $filter["payment_gateway_id"] = trim($_POST["pmgw"]);
            $filter["hold_reason"] = trim($_POST["rsn"]);
            $filter["chargeback_reason"] = trim($_POST["cbrsn"]);
            $filter["chargeback_start_date"] = trim($_POST["cbstart"]);
            $filter["chargeback_end_date"] = trim($_POST["cbend"]);
            $filter["chargeback_status"] = trim($_POST["cbstatus"]);
            $filter["chargeback_remark"] = trim($_POST["cbremark"]);
            $filter["so_no"] = trim($_POST["so"]);
            $filter["currency_id"] = trim($_POST["curr"]);

            if ($result = (array)$this->sc['Chargeback']->getChargebackData($filter)) {
                $output = $this->sc['Chargeback']->processData($result, 'csv');

                if ($output) {
                    $filename = "chargeback_orders_" . date('YmdHis') . ".csv";
                    header("Content-type: text/csv");
                    header("Cache-Control: no-store, no-cache");
                    header("Content-disposition: filename=$filename");
                    echo $output;
                    die();
                }

            } else {
                $_SESSION["NOTICE"] = "No data available for your selection";
            }
            $data["notice"] = notice($lang);
        }


        $data["selling_platform"] = $this->sc['PlatformBizVar']->getSellingPlatformList();
        $data["currency_list"] = $this->sc['Country']->getSellCurrencyList();
        $data["pmgw_list"] = $this->sc['PaymentGateway']->getDao('PaymentGateway')->getList([], ["orderby" => "name ASC", "limit" => -1]);
        $data["hold_reason_list"] = $this->sc['So']->getDao('SoHoldReason')->getReasonList();
        $data["chargeback_reason_list"] = $this->sc['Chargeback']->getDao('Chargeback')->getChargebackReasonList();
        $data["chargeback_status_list"] = $this->sc['Chargeback']->getDao('Chargeback')->getChargebackStatusList();
        $data["chargeback_remark_list"] = $this->sc['Chargeback']->getDao('Chargeback')->getChargebackRemarkList();

        $this->load->view('order/chargeback_report/index_v', $data);
    }


    public function getAppId()
    {
        return $this->appId;
    }

}



