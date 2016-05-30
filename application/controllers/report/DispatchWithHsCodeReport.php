<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class DispatchWithHsCodeReport extends MY_Controller
{
    private $appId = "RPT0051";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice'));
    }

    public function index()
    {
        $data["title"] = "Dispatch Report with HS Code";
        $langfile = $this->getAppId() . "00_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;
        $data['countrys'] = $this->sc['Country']->getDao()->getList(array("allow_sell" => "1"), array("orderby" => "name", "limit" => -1));
        $data['currencys'] = $this->sc['Country']->getSellCurrencyList();
        $data['start_date'] = $data['end_date'] = date('Y-m-d');
        $this->load->view('report/dispatch_report_with_hs_code', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function query()
    {
        if ($this->input->post('is_query')) {
            $from_date = $this->input->post("start_date");
            $to_date = $this->input->post("end_date");
            $where = array();
            if ($currency_id != -1) {
                $where['so.currency_id'] = $currency_id;
            }
            if ($country_id != -1) {
                $where['so.delivery_country_id'] = $country_id;
            }

            $where['from_date'] = $from_date;
            $where['to_date'] = $to_date;

            $list = $this->sc['RptDispatchWithHsCode']->getCsv($where);
            $content = $this->sc['RptDispatchWithHsCode']->getHeader();

            foreach ($list as $line) {
                $content .= "{$line['so_no']},{$line['warehouse_id']},{$line['ext_sku']},{$line['prod_name']},{$line['qty']},{$line['code']},{$line['description']}, {$line['order_create_date']},{$line['pack_date']},{$line['dispatch_date']},{$line['currency_id']},{$line['amount']},{$line['fc_country']},{$line['delivery_country_id']},{$line['courier_id']},{$line['tracking_no']},, {$line['average_delivery_cost']}, {$line['item_declared_value']}, {$line['total_declared_value']}\r\n";
            }

            $data['output'] = $content;
            $data['filename'] = 'dispatch_with_hs_code_report.csv';
            $this->load->view('output_csv.php', $data);
        }
    }

    public function export_csv()
    {
        $hold_status_list[0] = "No";
        $hold_status_list[1] = "Requested";
        $hold_status_list[2] = "Manager Requested";
        $hold_status_list[3] = "Product HS Code Report";
        $refund_status_list[0] = "No";
        $refund_status_list[1] = "Requested";
        $refund_status_list[2] = "Logistic Approved";
        $refund_status_list[3] = "CS Approved";
        $refund_status_list[4] = "Refunded";

        if (trim($this->input->post("cat_id")) == "") {
            header("Location: /report/product_hs_code_report");
        } else {
            $list = $this->so_service->get_dao()->get_product_hscode_report($this->input->post("cat_id"));
            $content = "MasterSKU,SKU,Product Name,Category, Subcategory,Sub_subcategory,Country ID,HS Code,HS Code Description,Duty Percent\r\n";
            foreach ($list as $line) {
                if ($line->sub_subcategory == 'Base') {
                    $line->sub_subcategory = '--------';
                }
            }
        }
        $data['output'] = $content;
        $data['filename'] = 'dispatch_with_hs_code_report.csv';
        $this->load->view('output_csv.php', $data);
        return;
    }
}

