<?php

class Price_comparison_report extends MY_Controller
{
    private $app_id = "RPT0016";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/price_comparison_report_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('input');
        $this->load->library('service/context_config_service');
    }

    public function index()
    {
        include_once APPPATH . '/language/' . $this->_get_app_id() . '00_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["title"] = "Price Comparision Report";
        if ($this->input->post('is_query')) {
            $data["posted"] = 1;
        }

        $this->load->view('report/price_comparison_report', $data);

    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function export_csv()
    {
        $where = '';
        $data['lang'] = $this->_load_parent_lang();
        $data['output'] = $this->price_comparison_report_model->get_csv($where);
        $data['filename'] = 'price_comparison_report.csv';

        $this->load->view('output_csv.php', $data);
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        return $lang;
    }
}