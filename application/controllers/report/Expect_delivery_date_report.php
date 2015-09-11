<?php
include_once "base_report.php";

class expect_delivery_date_report extends Base_report
{
    private $appId = "RPT0066";
    private $lang_id = "en";

    public function expect_delivery_date_report()
    {
        parent::__construct();
        $this->load->model('report/expect_delivery_date_report_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('service/pagination_service');
        $this->load->library('template');
        $this->template->set_template('report');
    }

    public function index()
    {
        $data["title"] = "expect_delivery_date Report";

        $langfile = $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;


        if ($_POST["display_report"] == 1 || $_GET['per_page'] || $_GET["search"]) {
            if ($_POST["expect_delivery_date_approve_start_date"] && $_POST["expect_delivery_date_approve_end_date"]) {
                $data['start_date'] = $_POST["expect_delivery_date_approve_start_date"];
                $data['end_date'] = $_POST["expect_delivery_date_approve_end_date"];
                $_SESSION["start_date"] = $data['start_date'];
                $_SESSION["end_date"] = $data['end_date'];
            } else {
                $data['start_date'] = $_SESSION["start_date"];
                $data['end_date'] = $_SESSION["end_date"];
            }

            $where = array();
            $option = array();

            $where["so.create_on >="] = $_SESSION["start_date"] . " 00:00:00";
            $where["so.create_on <="] = $_SESSION["end_date"] . " 23:59:59";

            $sort = $this->input->get('sort');
            if ($sort == "") {
                $sort = "so.so_no";
            }
            $data["sort"] = $sort;

            $order = $this->input->get('order');
            if (empty($order)) {
                $order = "asc";
            }
            $data["order"] = $order;

            $option["orderby"] = $sort . " " . $order;

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            if ($this->input->get('so_no') != "") {
                $where["so.so_no"] = $this->input->get('so_no');
            }
            if ($this->input->get('platform_id') != "") {
                $where["so.platform_id"] = $this->input->get('platform_id');
            }
            if ($this->input->get('payment_gateway_id') != "") {
                $where["sops.payment_gateway_id"] = $this->input->get('payment_gateway_id');
            }
            if ($this->input->get('platform_order_id') != "") {
                $where["so.platform_order_id"] = $this->input->get('platform_order_id');
            }
            if ($this->input->get('ext_client_id') != "") {
                $where["c.ext_client_id"] = $this->input->get('ext_client_id');
            }
            if ($this->input->get('txn_id') != "") {
                $where["so.txn_id"] = $this->input->get('txn_id');
            }
            if ($this->input->get('amount') != "") {
                $where["so.amount"] = $this->input->get('amount');
            }
            if ($this->input->get('order_create_date') != "") {
                $where["so.order_create_date"] = $this->input->get('order_create_date');
            }
            if ($this->input->get('expect_delivery_date') != "") {
                $where["so.expect_delivery_date"] = $this->input->get('expect_delivery_date');
            }
            if ($this->input->get('bill_name') != "") {
                $where["so.bill_name"] = $this->input->get('bill_name');
            }
            if ($this->input->get('emial') != "") {
                $where["c.emial"] = $this->input->get('emial');
            }
            if ($this->input->get('contact_no') != "") {
                $where["contact_no"] = $this->input->get('contact_no');
            }
            if ($this->input->get('dispatch_date') != "") {
                $where["so.dispatch_date"] = $this->input->get('dispatch_date');
            }
            if ($this->input->get('status') != "") {
                $where["so.status"] = $this->input->get('status');
            }
            if ($this->input->get('hold_status') != "") {
                $where["so.hold_status"] = $this->input->get('hold_status');
            }
            if ($this->input->get('refund_status') != "") {
                $where["so.refund_status"] = $this->input->get('refund_status');
            }
            if ($this->input->get('score') != "") {
                $where["sps.score"] = $this->input->get('score');
            }
            if ($this->input->get('modify_by')) {
                $where['so.modify_by'] = $this->input->get('modify_by');
            }

            $option["limit"] = $pconfig['per_page'] = 20;
            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            $_SESSION["LISTPAGE"] = base_url() . "report/expect_delivery_date_report/index?" . $_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];
            $data['list'] = $this->expect_delivery_date_report_model->get_obj_list($where, $option);
            $option['num_rows'] = 1;
            $data['total'] = $this->expect_delivery_date_report_model->get_obj_list($where, $option);


            $_SESSION["LISTPAGE"] = base_url() . "report/expect_delivery_date_report/index?" . $_SERVER['QUERY_STRING'];
            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);
        }

        $this->load->view('report/expect_delivery_date_report', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function export_csv()
    {
        if ($this->input->post('is_query')) {
            $data["posted"] = 1;

            if ($_POST["start_date"]["order_create"]) {
                $_SESSION['start_date'] = $_POST["start_date"]["order_create"];
                $where["so.create_on >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
            }
            if ($_POST["end_date"]["order_create"]) {
                $_SESSION['end_date'] = $_POST["end_date"]["order_create"];
                $where["so.create_on <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
            }
            $data['output'] = $this->expect_delivery_date_report_model->get_csv($where);
            $data['filename'] = 'expect_delivery_date_report.csv';
            $this->load->view('output_csv.php', $data);
        }
    }
}
