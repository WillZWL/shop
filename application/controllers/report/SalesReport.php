<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SalesReport extends MY_Controller
{
    protected $appId = "RPT0002";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'image'));
    }

    public function query()
    {
        $data['lang'] = $this->loadParentLang();
        if ($this->input->post('is_query')) {
            $from_date = $this->input->post("start_date");
            $to_date = $this->input->post("end_date");
            $currency_id = $this->input->post('currency');
            $country_id = $this->input->post('country');
            $payment_gateway = $this->input->post('payment_gateway');
            $is_sales_rpt = $this->input->post('is_sales_rpt');
            $is_light_version = $this->input->post("light_version");
            $is_china_oem = $this->input->post('china_oem');
            $clearance = $this->input->post('clearance');
            $where = array();
            $where['so.status >= 2'] = NULL;
            $where['so.hold_status !='] = 15;
            if ($from_date && $to_date) {
                $from_date = $from_date. ' 00:00:00';
                $to_date = $to_date.' 23:59:59';
                $where["sps.pay_date BETWEEN '$from_date' AND '$to_date'"] = NULL;
            }
            if ($currency_id != -1) {
                $where['so.currency_id'] = $currency_id;
            }
            if ($country_id != -1) {
                $where['so.delivery_country_id'] = $country_id;
            }
            if ($payment_gateway != -1) {
                $where['sps.payment_gateway_id'] = $payment_gateway;
            }
            if ($is_china_oem != -1) {
                if ($is_china_oem == 0 || $is_china_oem == 1) {
                    $where['p.china_oem'] = $is_china_oem;
                }
            }
            switch ($clearance) {
                case 'clearance':
                    $where['clearance'] = 1;
                    break;
                case 'exclude_negative_clearance':
                    $where['(soid.profit > 0 OR clearance = 0)'] = null;
                    break;
            }
            $data['output'] = $this->sc['RptSalesService']->getCsv($from_date, $to_date, $where, $is_sales_rpt, $is_light_version);
            if ($is_light_version) {
                $data['filename'] = "light_sales_report.csv";
            } else {
                $data['filename'] = "sales_report.csv";
            }
            $this->load->view('output_csv.php', $data);
        }
    }

    private function loadParentLang()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        return $lang;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function setAppId($value)
    {
        $this->appId = $value;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function index()
    {
        $data['lang'] = $this->loadParentLang();
        $data['controller'] = strtolower(get_class($this));
        $data['countrys'] = $this->sc['Country']->getDao()->getList(array("allow_sell" => "1"), array("orderby" => "name", "limit" => -1));
        $data['currencys'] = $this->sc['Country']->getSellCurrencyList();
        $data['gateways'] = $this->sc['PaymentGateway']->getDao()->getList(array("status" => 1), array("limit" => -1));
        $data["start_date"] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
        $data["end_date"] = date('Y-m-d');
        $this->load->view('report/sales_report', $data);
    }

    public function splitOrdersReport()
    {
        $data['lang'] = $this->loadParentLang();
        $data["start_date"] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
        $data["end_date"] = date('Y-m-d');
        $data["notice"] = notice($data['lang']);
        $data["prompt_notice"] = 0;
        if ($this->input->post('is_query')) {
            $ret = $this->querySplitOrder();
            if ($ret["status"] === FALSE) {
                $_SESSION["NOTICE"] = $ret["message"];
            } else {
                if ($ret["data"]) {
                    $filename = "split_orders_report_" . date('Ymd_His') . ".csv";
                    $fp = fopen('php://output', 'w');
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment;filename=' . $filename);
                    foreach ($ret["data"] as $fields) {
                        fputcsv($fp, $fields);
                    }
                    fclose($fp);
                    die();
                } else {
                    $_SESSION["NOTICE"] = "Error getting data";
                }
            }
            // if any errors, redirect back with notice
            Redirect(base_url() . "report/sales_report/split_orders_report");
        }
        $this->load->view('report/split_orders_report', $data);
    }

    private function querySplitOrder()
    {
        $data['lang'] = $this->_load_parent_lang();
        $ret["status"] = false;
        if ($this->input->post('is_query')) {
            $from_date = $this->input->post("start_date");
            $to_date = $this->input->post("end_date");
            $ret = $this->sc['RptSalesService']->getSplitOrderCsv($from_date, $to_date);

        }
        return $ret;
    }

    public function getShippedSummary($start_date = "", $end_date = "")
    {
        if ($start_date == "") $start_date = $_GET["start_date"];#"2013-01-01";
        if ($end_date == "") $end_date = $_GET["end_date"];#2013-01-15";
        $start_date_ok = "";
        $end_date_ok = "";
        if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $start_date, $matches)) {
            $start_date_ok = $matches[0];
        }
        if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $end_date, $matches)) {
            $end_date_ok = $matches[0];
        }

        if ($start_date_ok == "" || $end_date_ok == "") {
            die();
        }
        date_default_timezone_set("GMT+0");
        $xml = new SimpleXMLElement('<shipped/>');
        $xml->description = "Shipped orders summary from $start_date_ok to $end_date_ok";
        $result = $this->sc['SoShipment']->getDao()->getShippedSummary($start_date_ok, $end_date_ok);
        if ($result) {
            foreach ($result as $row) {
                $sku = $xml->addChild("sku");
                $sku->master_sku = $row->master_sku;
                $sku->total_quantity = $row->total_quantity;
                $sku->total_amount_hkd = $row->total_amount_hkd;
            }
        }
        header('Content-type: text/xml');
        print($xml->asXML());
    }

    public function getSalesSummary($start_date = "", $end_date = "")
    {
        if ($start_date == "") $start_date = $_GET["start_date"];#"2013-01-01";
        if ($end_date == "") $end_date = $_GET["end_date"];#2013-01-15";
        $start_date_ok = "";
        $end_date_ok = "";
        if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $start_date, $matches)) {
            $start_date_ok = $matches[0];
        }
        if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $end_date, $matches)) {
            $end_date_ok = $matches[0];
        }
        if ($start_date_ok == "" || $end_date_ok == "") {
            die();
        }
        date_default_timezone_set("GMT+0");
        $xml = new SimpleXMLElement('<sales/>');
        $xml->description = "Sales orders summary from $start_date_ok to $end_date_ok";
        $result = $this->sc['SoService']->getDao()->getSalesSummary($start_date_ok, $end_date_ok);
        if ($result) {
            foreach ($result as $row) {
                $sku = $xml->addChild("sku");
                $sku->master_sku = $row->master_sku;
                $sku->total_quantity = $row->total_quantity;
                $sku->total_amount_hkd = $row->total_amount_hkd;
            }
        }
        header('Content-type: text/xml');
        print($xml->asXML());
    }
}


