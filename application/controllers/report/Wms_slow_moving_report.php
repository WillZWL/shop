<?php

class Wms_slow_moving_report extends MY_Controller
{
    private $app_id = "RPT0024";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/wms_slow_moving_report_model');
    }

    public function query()
    {
        if ($this->input->post('is_query')) {
            $data = $this->wms_slow_moving_report_model->get_report($this->input->post('date_after'));
            $this->load->view('output_csv.php', $data);
        }
    }

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();
        $this->load->view('report/wms_slow_moving_report', $data);
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        return $lang;
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}


