<?php

class Refund_report extends MY_Controller
{
    private $appId = "RPT0025";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        include_once APPPATH . '/language/' . $this->getAppId() . '00_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["title"] = "Refund Report";
        if ($this->input->post('is_query')) {
            $data["posted"] = 1;
            $this->export_csv();
        } else {
            if ($this->input->post("is_query") == 1) {
                $data["start_date"] = $this->input->post("start_date");
                $data["end_date"] = $this->input->post("end_date");
            } else {
                $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
                $data["end_date"] = date('Y-m-d');
            }

            $this->load->view('report/refund_report', $data);
        }
    }

    public function export_csv()
    {
        if ($_POST["check"]["order_create"]) {
            $where["so.order_create_date >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
            $where["so.order_create_date <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
            $filename = "Panther_Refund_Report_create--{$_POST["start_date"]["order_create"]}_to_{$_POST["end_date"]["order_create"] }";
        }

        if ($_POST["check"]["cs_request"]) {
            $where["r.create_on >="] = $_POST["start_date"]["cs_request"] . " 00:00:00";
            $where["r.create_on <="] = $_POST["end_date"]["cs_request"] . " 23:59:59";
            $filename = "Panther_Refund_Report_Req--{$_POST["start_date"]["cs_request"]}_to_{$_POST["end_date"]["cs_request"]}";
        }

        if ($_POST["check"]["refund"]) {
            $where["rh.app_status"] = 'A';
            $where["rh.status"] = 'C';
            $where["rh.modify_on >="] = $_POST["start_date"]["refund"] . " 00:00:00";
            $where["rh.modify_on <="] = $_POST["end_date"]["refund"] . " 23:59:59";
            $filename = "Panther_Refund_Report_Account--{$_POST["start_date"]["refund"]}_to_{$_POST["end_date"]["refund"]}";
        }

        $data['lang'] = $this->_load_parent_lang();
        $data['output'] = $this->sc['RefundReport']->get_csv($where);
        $data['filename'] = $filename.'.csv';
        $this->load->view('output_csv.php', $data);
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        return $lang;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}