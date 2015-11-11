<?php


class Inventory_movement extends MY_Controller
{

    private $appId = "RPT0009";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/inventory_movement_model');
        $this->load->helper(array('url'));
        //$this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    public function query()
    {
        $data['lang'] = $this->_load_parent_lang();
        if ($this->input->post('is_query')) {
            $sku = $this->input->post('sku');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');

            $data['output'] = $this->inventory_movement_model->get_csv($sku, $start_date, $end_date);
            $data['filename'] = 'inv_movement_report_' . $sku . '.csv';

            $this->load->view('output_csv.php', $data);
        }
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

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();
        //$this->model->get_csv($sku, $prod_name);

        $this->load->view('report/inventory_movement', $data);
    }
}


