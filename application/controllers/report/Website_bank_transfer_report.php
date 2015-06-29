<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");

class Website_bank_transfer_report extends MY_Controller
{
    public $overview_path;
    public $default_country_id;

    //must set to public for view
    private $app_id = "RPT0044";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->report_path = 'report/website_bank_transfer_report';
        $this->load->model($this->report_path . '_model', 'website_bank_transfer_report_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));

        $this->load->library('service/price_service');
        $this->load->library('service/website_bank_transfer_service');
        $this->load->library('dao/so_bank_transfer_dao');
        $this->load->library('dao/bank_account_dao');
    }


    public function index()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        $_SESSION["BTRPAGE"] = base_url() . "report/website_bank_transfer_report/";
        $rec_date = $order_date = array();

        if ($this->input->post('posted')) {

            if ($this->input->post('rec_date_from') && !$this->input->post('rec_date_to')) {
                $_SESSION["NOTICE"] = "PAYMENT RECEIVED DATE (to) cannot be blank.";
                redirect($_SESSION["BTRPAGE"]);
            } elseif (!$this->input->post('rec_date_from') && $this->input->post('rec_date_to')) {
                $_SESSION["NOTICE"] = "PAYMENT RECEIVED DATE (from) cannot be blank.";
                redirect($_SESSION["BTRPAGE"]);
            } elseif ($this->input->post('rec_date_from') && $this->input->post('rec_date_to')) {
                $rec_date["from"] = $this->input->post('rec_date_from');
                $rec_date["to"] = $this->input->post('rec_date_to');
            }

            if ($this->input->post('order_date_from') && !$this->input->post('order_date_to')) {
                $_SESSION["NOTICE"] = "ORDER CREATE DATE (to) cannot be blank.";
                redirect($_SESSION["BTRPAGE"]);
            } elseif (!$this->input->post('order_date_from') && $this->input->post('order_date_to')) {
                $_SESSION["NOTICE"] = "ORDER CREATE DATE (from) cannot be blank.";
                redirect($_SESSION["BTRPAGE"]);
            } elseif ($this->input->post('order_date_from') && $this->input->post('order_date_to')) {
                $order_date["from"] = $this->input->post('order_date_from');
                $order_date["to"] = $this->input->post('order_date_to');
            }

            if ($this->input->post('bank_acc')) {
                $where["sbt.bank_account_id"] = $this->input->post('bank_acc');
            }

            if ($bank_transfer_data = $this->so_bank_transfer_dao->bank_transfer_report($where, $rec_date, $order_date, $option)) {
                $this->generate_report($bank_transfer_data);
            } else {
                $_SESSION["NOTICE"] = "No data available";
                redirect($_SESSION["BTRPAGE"]);
            }
        } else {
            $data["bank_acc_list"] = $this->website_bank_transfer_service->get_list();
            $data["now_date"] = date("Y-m-d");

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            $data["notice"] = notice($lang);

            $this->load->view('report/website_bank_transfer_report', $data);

        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    private function generate_report($data = array())
    {
        $data_content = $ext_ref_no_arr = $received_amt_localcurr_arr = $bank_acc_no_arr = $received_date_localtime_arr = $bank_charge_arr = $notes_arr = $sbt_create_on_arr = $sbt_create_by_arr = array();
        $data_content[0] = array("SO Number", "Customer Name", "Currency", "Order Amt", "Order Create Date", "Payment Received Date", "Received Amt", "Bank Charge", "Outstanding Amt", "Bank/Sales Ref.", "Received Acc. No.", "User", "Transaction create_on (GMT0)");

        if ($data) {
            foreach ($data as $data_obj) {
                $bank_account_id_arr = array();
                $total_received_amt = $total_bank_charge = 0;
                $so_no = $data_obj->get_so_no();
                $order_amt = number_format($data_obj->get_amount(), 2, '.', '');
                $currency_id = $data_obj->get_currency_id();
                $order_create_date = $data_obj->get_order_create_date();
                $email = $data_obj->get_email();
                $forename = $data_obj->get_forename();

                $ext_ref_no_arr = explode('||', $data_obj->get_ext_ref_no());
                $received_amt_localcurr_arr = explode('||', $data_obj->get_received_amt_localcurr());
                $received_date_localtime_arr = explode('||', $data_obj->get_received_date_localtime());
                $bank_charge_arr = explode('||', $data_obj->get_bank_charge());
                $notes_arr = explode('||', $data_obj->get_notes());
                $sbt_create_on_arr = explode('||', $data_obj->get_sbt_create_on());
                $sbt_create_by_arr = explode('||', $data_obj->get_sbt_create_by());
                $bank_account_no_arr = explode('||', $data_obj->get_bank_account_no());


                if ($ext_ref_no_arr) {
                    foreach ($ext_ref_no_arr as $key => $ext_ref_no) {
                        $received_amt = number_format($received_amt_localcurr_arr[$key], 2, '.', '');
                        $bank_charge = number_format($bank_charge_arr[$key], 2, '.', '');
                        $received_date = date('Y-m-d', strtotime($received_date_localtime_arr[$key]));
                        $notes = $notes_arr[$key];
                        $sbt_create_on = date('Y-m-d H:i:s', strtotime($sbt_create_on_arr[$key]));
                        // var_dump($sbt_create_on);die();
                        $sbt_create_by = $sbt_create_by_arr[$key];
                        $acc_no = $bank_account_no_arr[$key];

                        # calculate total received for multiple transactions per SO
                        $total_received_amt += $received_amt;
                        $total_bank_charge += $bank_charge;
                        $net_diff = number_format(($order_amt - ($total_received_amt - $total_bank_charge)), 2, '.', '');

                        $data_content[] = array($so_no, $forename, $currency_id, $order_amt, $order_create_date, $received_date, $received_amt, $bank_charge, $net_diff, $ext_ref_no, $acc_no, $sbt_create_by, $sbt_create_on);
                    }
                }
            }
        }


        $this->generate_csv($data_content);
    }

    function generate_csv($data)
    {
        $now_time = date('YmdHis');
        header("Content-type: text/csv");
        header("Cache-Control: no-store, no-cache");
        header("Content-Disposition: attachment; filename=\"{$now_time}_bank_transfer_report.csv\"");

        foreach ($data as $key => $value) {
            $lines .= implode(',', $value) . "\r\n";
        }

        echo $lines;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    private function get_margin($platform_id, $sku, $price)
    {
        $json = $this->price_service->get_profit_margin_json($platform_id, $sku, $price);
        $arr = json_decode($json, true);

        return $arr["get_margin"];
    }
}
