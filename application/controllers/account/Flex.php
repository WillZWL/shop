<?php

class Flex extends MY_Controller
{
    private $appId = "ACC0002";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'object', 'image'));
    }

    public function sales($start_date = "", $end_date = "")
    {
        $query = 0;
        $sub_app_id = $this->getAppId() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $data["lang"] = $lang;
        DEFINE('FLEX_REPORT_PATH', $this->sc['ContextConfig']->valueOf("flex_report_path"));
        if ($this->input->post("is_query") == 1) {
            $data["start_date"] = $this->input->post("start_date");
            $data["end_date"] = $this->input->post("end_date");
        } elseif ($start_date && $end_date) {
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $query = 1;
        } else {
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        }
        $start_date = $date = date("Ymd", strtotime("+1 day", strtotime($data["start_date"])));
        $end_date = date("Ymd", strtotime("+1 day", strtotime($data["end_date"])));
        if ($this->input->post("search") == 1 || $query) {
            $result_array = [];
            $i = 0;
            while (strtotime($date) <= strtotime($end_date) && $i < 30) {
                $result = [];
                $result["date"] = date('Y-m-d', strtotime($date));
                $file_path = FLEX_REPORT_PATH . $date . "/sales/";
                $file_info = $this->getDirFileInfo($file_path);

                if (array_key_exists("zip", $file_info)) {
                    $result["type"] = "settled";
                    foreach ($file_info["zip"] as $zip_name) {
                        $result["zip"][] = $zip_name;
                    }
                    @asort($result["zip"]);
                }

                if (is_file(FLEX_REPORT_PATH . $date . "/sales/Exception.csv")) {
                    $result["type"] = "waiting_confirm";
                    $result["filepath"] = FLEX_REPORT_PATH . $date . "/sales/";
                    $result["filename"] = "Exception.csv";
                    $result["process_url"] = "";
                    $result["regen_url"] = "";
                } elseif (!is_file(FLEX_REPORT_PATH . $date . "/sales/Exception.csv")) {
                    $result["type"] = "gen_exception";
                } else {
                    $result["type"] = "error";
                    $result["msg"] = "Exception status on " . $date . ". Please contact IT.";
                }
                $result_array[$date] = $result;
                $date = date("Ymd", strtotime("+1 day", strtotime($date)));
                $i++;
            }
            $data["result_array"] = $result_array;
        }
        $data["notice"] = notice($lang);
        $data["status"] = 'sales';

        $this->load->view('account/flex/index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getDirFileInfo($file_path)
    {
        $file_info = [];
        $file_info['other'] = [];
        if (is_dir($file_path) && ($handle = opendir($file_path))) {
            while (false !== ($entity = readdir($handle))) {
                if ($entity != "." && $entity != "..") {
                    $ext = pathinfo($entity, PATHINFO_EXTENSION);
                    if ($ext == "zip") {
                        $file_info['zip'][] = $entity;
                    } else {
                        $file_info['other'][] = $entity;
                    }
                }
            }
            closedir($handle);
        }
        return $file_info;
    }

    public function refund($start_date = "", $end_date = "")
    {
        $query = 0;
        $sub_app_id = $this->getAppId() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $data["lang"] = $lang;

        if ($this->input->post("is_query") == 1) {
            $data["start_date"] = $this->input->post("start_date");
            $data["end_date"] = $this->input->post("end_date");
        } elseif ($start_date && $end_date) {
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $query = 1;
        } else {
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        }

        $start_date = $date = date("Ymd", strtotime("+1 day", strtotime($data["start_date"])));
        $end_date = date("Ymd", strtotime("+1 day", strtotime($data["end_date"])));
        DEFINE('FLEX_REPORT_PATH', $this->sc['ContextConfig']->valueOf("flex_report_path"));
        if ($this->input->post("search") == 1 || $query) {
            $result_array = [];
            $i = 0;
            while (strtotime($date) <= strtotime($end_date) && $i < 30) {
                $result = [];
                $result["date"] = date('Y-m-d', strtotime($date));
                $file_path = FLEX_REPORT_PATH . $date . "/refund/";
                $file_info = $this->getDirFileInfo($file_path);

                if (array_key_exists("zip", $file_info)) {
                    foreach ($file_info["zip"] as $zip_name) {
                        $result["zip"][] = $zip_name;
                    }
                    asort($result["zip"]);
                }

                if (is_file(FLEX_REPORT_PATH . $date . "/refund/refund_invoice.csv")) {
                    $result["r_invoice"] = "invoice";
                    $result["r_invoice_filepath"] = FLEX_REPORT_PATH . $date . "/refund/";
                    $result["r_invoice_filename"] = "refund_invoice.csv";
                }
                if (is_file(FLEX_REPORT_PATH . $date . "/refund/refund_exception.csv")) {
                    $result["r_exception"] = "exception";
                    $result["r_exception_filepath"] = FLEX_REPORT_PATH . $date . "/refund/";
                    $result["r_exception_filename"] = "refund_exception.csv";
                }
                if (is_file(FLEX_REPORT_PATH . $date . "/refund/chargeback_invoice.csv")) {
                    $result["cb_invoice"] = "invoice";
                    $result["cb_invoice_filepath"] = FLEX_REPORT_PATH . $date . "/chargeback/";
                    $result["cb_invoice_filename"] = "chargeback_invoice.csv";
                }
                if (is_file(FLEX_REPORT_PATH . $date . "/refund/chargeback_exception.csv")) {
                    $result["cb_exception"] = "exception";
                    $result["cb_exception_filepath"] = FLEX_REPORT_PATH . $date . "/chargeback/";
                    $result["cb_exception_filename"] = "chargeback_exception.csv";
                }
                $result_array[$date] = $result;
                $date = date("Ymd", strtotime("+1 day", strtotime($date)));
                $i++;
            }
            $data["result_array"] = $result_array;
        }
        $data["notice"] = notice($lang);
        $data["status"] = 'refund';
        $this->load->view('account/flex/refund_index_v', $data);
    }

    public function pendingOrderReport()
    {
        $query = 0;
        $sub_app_id = $this->getAppId() . "04";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $data["lang"] = $lang;
        DEFINE('FLEX_REPORT_PATH', $this->sc['ContextConfig']->valueOf("flex_report_path"));
        if ($this->input->post("is_query") == 1) {
            $data["ship_date"] = $this->input->post('ship_date');
            $this->getPendingOrderReport($data["ship_date"]);
        }
        $this->load->view('account/flex/pending_order_report_index_v', $data);
    }

    public function getPendingOrderReport($ship_date = '')
    {
        $flex_report_path = $this->sc['ContextConfig']->valueOf("flex_report_path");
        $this->sc['Flex']->getPendingOrderReport($ship_date);
        $this->download($flex_report_path . "pending_order_report.csv", "pending_order_report.csv");
    }

    public function download($file_path, $filename)
    {
        if (is_file($file_path)) {
            header("Expires: 0");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("Content-Type: application/octet-stream");
            header('Content-length: ' . filesize($file_path));
            header('Content-disposition: attachment; filename="' . $filename . '"');
            readfile($file_path);
            exit;
        }
    }

    public function orderNotInRiaReport()
    {
        $sub_app_id = $this->getAppId() . "05";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $data['lang'] = $lang;
        if ($this->input->post('is_query') == 1) {
            $where = [];
            $where['so.order_create_date >= '] = $this->input->post('start_date') . ' 00:00:00';
            $where['so.order_create_date <= '] = $this->input->post('end_date') . ' 23:59:59';
            if ($this->input->post('payment_gateway') != -1) {
                $where['sps.payment_gateway_id'] = $this->input->post('payment_gateway');
            } else {
                $where['sps.payment_gateway_id is not null'] = null;
            }
            if ($this->input->post('currency') != -1) {
                $where['so.currency_id'] = $this->input->post('currency');
            }
            $data['output'] = $this->sc['RptOrderNotInRiaReport']->getCsv($where);
            $data['filename'] = 'order_not_in_ria_report.csv';
            $this->load->view('output_csv.php', $data);
        } else {
            $data['currencys'] = $this->sc['Country']->getSellCurrencyList();
            $data['gateways'] = $this->sc['PaymentGateway']->getDao('PaymentGateway')->getList(array('status' => 1), array('limit' => -1));
            $this->load->view('account/flex/order_not_in_ria_report', $data);
        }
    }

    public function fee($start_date = "", $end_date = "")
    {
        $query = 0;
        $sub_app_id = $this->getAppId() . "03";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $data["lang"] = $lang;
        DEFINE('FLEX_REPORT_PATH', $this->sc['ContextConfig']->valueOf("flex_report_path"));
        if ($this->input->post("is_query") == 1) {
            $data["start_date"] = $this->input->post("start_date");
            $data["end_date"] = $this->input->post("end_date");
            $data["payment_gateway"] = $this->input->post("payment_gateway");
            $query = 1;
        } elseif ($start_date && $end_date) {
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $query = 1;
        } else {
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        }
        if ($query) {
            $this->genFeeInvoice($data["start_date"], $data["end_date"], $data["payment_gateway"]);
        }
        $this->load->view('account/flex/fee_index_v', $data);
    }

    public function genFeeInvoice($start_date, $end_date, $gateway_id = "")
    {
        $this->genSoFeeInvoice($start_date, $end_date, $gateway_id);
        $this->genGatewayFeeInvoice($start_date, $end_date, $gateway_id);
        $this->genRollingReserveReport($start_date, $end_date, $gateway_id);
        DEFINE('FLEX_REPORT_PATH', $this->sc['ContextConfig']->valueOf("flex_report_path"));
        $zip_name = "fee.zip";
        if (is_file(FLEX_REPORT_PATH . "so_fee.csv")) {
            $file_to_zip[] = "so_fee.csv";
        }
        if (is_file(FLEX_REPORT_PATH . "gateway_fee.csv")) {
            $file_to_zip[] = "gateway_fee.csv";
        }
        $flex_service = new $this->sc['Flex'];
        if (is_file(FLEX_REPORT_PATH . $flex_service::ROLLING_RESERVE_REPORT_FILE_NAME)) {
            $file_to_zip[] = $flex_service::ROLLING_RESERVE_REPORT_FILE_NAME;
        }
        if ($file_to_zip) {
            @unlink(FLEX_REPORT_PATH . $zip_name);
            $this->sc['Flex']->createZip($file_to_zip, $zip_name, FLEX_REPORT_PATH);
            $this->download(FLEX_REPORT_PATH . $zip_name, $zip_name);
        }
    }

    public function genSoFeeInvoice($start_date, $end_date, $gateway_id = "")
    {
        return $this->sc['Flex']->getSoFeeInvoice($start_date, $end_date, $gateway_id);
    }

    public function genGatewayFeeInvoice($start_date, $end_date, $gateway_id = "")
    {
        return $this->sc['Flex']->getGatewayFeeInvoice($start_date, $end_date, $gateway_id = "");
    }

    public function genRollingReserveReport($start_date, $end_date, $gateway_id = "")
    {
        return $this->sc['Flex']->getRollingReserveReport($start_date, $end_date, $gateway_id);
    }

    public function downloadFile($date, $status, $filename = "")
    {
        DEFINE('FLEX_REPORT_PATH', $this->sc['ContextConfig']->valueOf("flex_report_path"));
        $file_path = FLEX_REPORT_PATH . $date . "/" . $status . "/" . $filename;
        $this->download($file_path, $filename);
    }

    public function generateException($date, $start_date = "", $end_date = "", $folder_name = "")
    {
        $file_path = $this->sc['ContextConfig']->valueOf("flex_report_path") . $date . "/sales/";
        $flag_file = "processing.txt";
        if (!$this->isGenSalesInvoiceRunning($file_path, $flag_file)) {
            $this->sc['Flex']->reverseSalesInvoiceStatus($date);
            $this->sc['Flex']->getSalesInvoice($date, $date, $folder_name);
            if (!file_exists($file_path . "Exception.csv")) {
                $this->genSalesInvoice($date, $start_date, $end_date, $folder_name);
            }
        } else {
            unset($_SESSION["NOTICE"]);
            $_SESSION["NOTICE"] = "sales invoice is generating. Please try later";
        }
        redirect(base_url() . "account/flex/sales/" . $start_date . "/" . $end_date);
    }

    public function isGenSalesInvoiceRunning($file_path, $flag_file)
    {
        $file_info = $this->getDirFileInfo($file_path);
        if (in_array($flag_file, $file_info["other"])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function genSalesInvoice($date, $start_date = "", $end_date = "", $folder_name = "")
    {
        $file_path = $this->sc['ContextConfig']->valueOf("flex_report_path") . $date . "/sales/";
        $file_info = $this->getDirFileInfo($file_path);
        $flag_file = "processing.txt";
        if ($this->isGenSalesInvoiceRunning($file_path, $flag_file)) {
            unset($_SESSION["NOTICE"]);
            $_SESSION["NOTICE"] = "sales invoice is generating. Please try later";
        } else {
            $this->sc['Flex']->reverseSalesInvoiceStatus($date);
            $result = $this->sc['Flex']->getSalesInvoice($date, $date, $folder_name, FALSE, FALSE);
            if (file_exists($file_path)) {
                file_put_contents($file_path . $flag_file, "processing..");
            }
            if ($result) {
                $next_zip_file_no = count($file_info["zip"]) + 1;
                $zip_name = "sales" . $date . "_" . $next_zip_file_no . ".zip";
                $this->sc['Flex']->generateZipFile($file_path, $zip_name);
            }
            unlink($file_path . $flag_file);
        }
        redirect(base_url() . "account/flex/sales/" . $start_date . "/" . $end_date);
    }

    public function regenerateException($date, $start_date = "", $end_date = "", $folder_name = "")
    {
        $file_path = $this->sc['ContextConfig']->valueOf("flex_report_path") . $date . "/sales/";
        $flag_file = "processing.txt";
        if (!$this->isGenSalesInvoiceRunning($file_path, $flag_file)) {
            $this->sc['Flex']->reverseSalesInvoiceStatus($date);
            $this->sc['Flex']->getSalesInvoice($date, $date, $folder_name, TRUE, FALSE);
            if (!file_exists($file_path . "Exception.csv")) {
                $this->genSalesInvoice($date, $start_date, $end_date, $folder_name);
            }
        } else {
            unset($_SESSION["NOTICE"]);
            $_SESSION["NOTICE"] = "sales invoice is generating. Please try later";
        }
        redirect(base_url() . "account/flex/sales/" . $start_date . "/" . $end_date);
    }

    public function genSalesInvoiceIgnoreStatus($start_date, $end_date, $folder_name)
    {
        $result = $this->sc['Flex']->getSalesInvoice($start_date, $end_date, $folder_name, FALSE, TRUE);
        if ($result) {
            $file_path = $this->sc['ContextConfig']->valueOf("flex_report_path") . $folder_name . "/sales/";
            $zip_name = "sales" . $folder_name . ".zip";
            $this->sc['Flex']->generateZipFile($file_path, $zip_name);
        }
    }

    public function genRefundAndChargebackInvoice($date, $start_date = "", $end_date = "", $folder_name = "")
    {
        $this->sc['Flex']->reverseRefundInvoiceStatus($date);
        $this->sc['Flex']->getRefundInvoice($date, $date, "R", $folder_name);
        $this->sc['Flex']->getRefundInvoice($date, $date, "CB", $folder_name);
        $file_path = $this->sc['ContextConfig']->valueOf("flex_report_path") . $date . "/refund/";
        $file_info = $this->getDirFileInfo($file_path);
        $next_zip_file_no = count($file_info["zip"]) + 1;
        $zip_name = "refund_and_chargeback" . $date . "_" . $next_zip_file_no . ".zip";
        $this->sc['Flex']->generateZipFile($file_path, $zip_name);
        redirect(base_url() . "account/flex/refund/" . $start_date . "/" . $end_date);
    }

    public function genRefundInvoice($date, $start_date = "", $end_date = "", $folder_name = "")
    {
        $this->sc['Flex']->getRefundInvoice($date, $date, "R", $folder_name);
        redirect(base_url() . "account/flex/refund/" . $start_date . "/" . $end_date);
    }

    public function genChargebackInvoice($date, $start_date = "", $end_date = "", $folder_name = "")
    {
        $this->sc['Flex']->getRefundInvoice($date, $date, "CB", $folder_name);
        redirect(base_url() . "account/flex/refund/" . $start_date . "/" . $end_date);
    }

    public function genRefundInvoiceIgnoreStatus($start_date = "", $end_date = "", $folder_name = "")
    {
        $this->sc['Flex']->getRefundInvoice($start_date, $end_date, "R", $folder_name);
    }

    public function genChargebackInvoiceIgnoreStatus($start_date = "", $end_date = "", $folder_name = "")
    {
        $this->sc['Flex']->getRefundInvoice($start_date, $end_date, "CB", $folder_name);
    }

    public function platfromOrderInsertFlexRia($gateway_id = '', $so_no = '')
    {
        if (empty($gateway_id) || empty($so_no)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }
        if ($this->sc['Flex']->platfromOrderInsertFlexRia($gateway_id, $so_no)) {
            $_SESSION['NOTICE'] = 'confirm successed!';
        } else {
            $_SESSION['NOTICE'] = 'confirm failed!';
        }
        redirect(base_url() . "account/flex/get_rakuten_shipped_order");
    }

    public function getRakutenShippedOrder($page = 'search')
    {
        $sub_app_id = $this->getAppId() . "06";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $data["lang"] = $lang;

        if ($platform_order_id = $this->input->post("platform_order_id")) {
            $data['obj_list'] = $this->sc['Flex']->getRakutenShippedOrder($platform_order_id);
        }

        if ($page == 'search') {
            $this->load->view('account/flex/search_rakuten_shipped_order_v', $data);
        } elseif ($page == 'list') {
            $data['obj_list'] = $this->sc['Flex']->getRakutenShippedOrderFromInterface();
            $this->load->view('account/flex/list_rakuten_shipped_order_v', $data);
        }

        if ($this->input->post('add_to_list')) {
            if ($checked = $this->input->post('check')) {
                $this->platfromOrderInsertInterfaceFlexRia('rakuten', $checked);
            }
        }
    }

    public function platfromOrderInsertInterfaceFlexRia($gateway_id, $so_no_list)
    {
        if (empty($gateway_id) || empty($so_no_list)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }
        if ($this->sc['Flex']->platfromOrderInsertInterfaceFlexRia($gateway_id, $so_no_list)) {
            $_SESSION['NOTICE'] = 'add to list successed!';
        } else {
            $_SESSION['NOTICE'] = 'add to list failure!';
        }
    }

    public function getRakutenShippedOrderList()
    {

        if ($this->input->post('comfirm') == 'approve') {
            if ($checked = $this->input->post('check')) {
                $this->platfromOrderInsertFlexRiaA('rakuten', $checked);
            }
        } elseif ($this->input->post('comfirm') == 'cancel') {
            if ($checked = $this->input->post('check')) {
                $this->platformOrderDeleteInterfaceFlexRia('rakuten', $checked);
            }
        }

        $data['obj_list'] = $this->sc['Flex']->getRakutenShippedOrderFromInterface();
        $this->load->view('account/flex/order_to_comfirm_v', $data);
    }

    public function platfromOrderInsertFlexRiaA($gateway_id = '', $so_no = '')
    {
        if (empty($gateway_id) || empty($so_no)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }
        if (is_array($so_no)) {
            foreach ($so_no as $so) {

                if ($this->sc['Flex']->platfromOrderInsertFlexRia($gateway_id, $so)) {
                    $_SESSION['NOTICE'] = 'confirm successed!';
                } else {
                    $_SESSION['NOTICE'] = 'confirm failed!';
                }
            }
        }
    }

    public function platformOrderDeleteInterfaceFlexRia($gateway_id = '', $so_no_list)
    {
        if (empty($gateway_id) || empty($so_no_list)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }
        $this->sc['Flex']->platformOrderDeleteInterfaceFlexRia($gateway_id, $so_no_list);
    }

    public function riaControlReport()
    {
        $sub_app_id = $this->getAppId() . "07";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $data["lang"] = $lang;

        if ($this->input->post('is_query')) {
            $where = array();
            if ($this->input->post('start_date')) {
                $where['fri.txn_time >= '] = $this->input->post('start_date') . ' 00:00:00';
            }
            $where['fri.txn_time <= '] = $this->input->post('end_date') . ' 23:59:59';
            $end_date = $this->input->post('end_date') .' 23:59:59';
            $data['output'] = $this->sc['Flex']->getRiaControlReport($where, ['end_date' => $end_date]);
            $data['filename'] = 'ria_control.csv';
            $this->load->view('output_csv.php', $data);
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
            $this->load->view('account/flex/ria_control_report_v', $data);
        }
    }
}


