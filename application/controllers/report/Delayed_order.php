<?php

class Delayed_order extends MY_Controller
{
    private $appId = "RPT0013";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/delayed_order_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('input');
        $this->load->library('service/context_config_service');
    }

    public function index()
    {
        include_once APPPATH . '/language/' . $this->getAppId() . '00_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["title"] = "Delayed Orders Report";

        if ($this->input->post('is_query')) {
            $data["posted"] = 1;
            $data["start_date"] = $this->input->post('start_date');
            $data["end_date"] = $this->input->post('end_date');
            $data["display_type"] = $this->input->post('display_type');

        }

        $this->load->view('report/delayed_order/delayed_order', $data);

    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function export_by_order($start_date, $end_date)
    {
        $where = '';
        $data['lang'] = $this->_load_parent_lang();
        $data['output'] = $this->delayed_order_model->get_csv($start_date, $end_date, $where);
        $data['filename'] = 'delayed_orders_report_' . $start_date . '-' . $end_date . '.csv';

        $this->load->view('output_csv.php', $data);
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        return $lang;
    }

    public function export_by_dispatched($start_date, $end_date)
    {
        $where = 'and s.dispatch_date is not null';
        $data['lang'] = $this->_load_parent_lang();
        $data['output'] = $this->delayed_order_model->get_csv($start_date, $end_date, $where);
        $data['filename'] = 'delayed_orders_report_' . $start_date . '-' . $end_date . '.csv';

        $this->load->view('output_csv.php', $data);
    }
}