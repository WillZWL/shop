<?php

class Marketing_report extends MY_Controller
{
    private $app_id = "RPT0003";
    private $lang_id = "en";
    private $sourcing_status =
        array(
            'A' => 'R. Avail',
            'C' => 'Limited Stock',
            'D' => 'Discont',
            'IS' => 'Instock with Supp.',
            'L' => 'Last Lot',
            'O' => 'Out of Stock'
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/marketing_report_model');
        $this->load->helper(array('url'));
        $this->load->library('service/context_config_service');
    }

    public function query()
    {
        $data['lang'] = $this->_load_parent_lang();
        $data['sourcing_status'] = $this->sourcing_status;
        if ($this->input->post('is_query')) {
            $from_year = $this->input->post('from_year');
            $from_month = $this->input->post('from_month');
            $from_day = $this->input->post('from_day');
            $from_hour = $this->input->post('from_hour');
            $to_year = $this->input->post('to_year');
            $to_month = $this->input->post('to_month');
            $to_day = $this->input->post('to_day');
            $to_hour = $this->input->post('to_hour');


            $from_time = $from_year . '-' . $from_month . '-' . $from_day . ' ' . $from_hour;
            $to_time = $to_year . '-' . $to_month . '-' . $to_day . ' ' . $to_hour;

            $data = array_merge($data, $this->marketing_report_model->get_html($from_time, $to_time));

            $this->load->view('report/marketing_report_body.php', $data);
        }
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

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();

        $this->load->view('report/marketing_report.php', $data);
    }
}


