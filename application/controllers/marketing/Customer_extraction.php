<?php

class Customer_extraction extends MY_Controller
{
    private $appId = "MKT0039";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();

        $this->load->model('marketing/customer_extraction_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('input');
        $this->load->library('service/log_service');
        $this->load->library('service/context_config_service');

        $this->title = 'Customer Data Extraction';
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        $_SESSION["notice"] = "";
        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];

        $where = array();
        $option = array();

        $data['joined_plat_list'] = $this->input->post('joined_plat_list');
        $data['joined_cat_list'] = $this->input->post('joined_cat_list');

        $plat_list = $data['joined_plat_list'];
        $plat_arr = $this->customer_extraction_model->get_platform_list(array(), array("orderby" => "id"));

        $cat_list = $data['joined_cat_list'];
        $cat_arr = $this->customer_extraction_model->get_combined_cat_list(array(), array());

        if ($plat_list) {
            foreach ($plat_list as $v) {
                unset ($plat_arr[array_search($v, $plat_arr)]);
            }
        }

        if ($cat_list) {
            foreach ($cat_list as $v) {
                unset ($cat_arr[array_search($v, $cat_arr)]);
            }
        }

        $data['lang'] = $lang;
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');

        $data['platform_in'] = $plat_arr;
        $data['platform_ex'] = $this->customer_extraction_model->get_platform_ex($plat_list, $plat_arr);

        $data['category_in'] = $cat_arr;
        $data['category_ex'] = $this->customer_extraction_model->get_category_ex($cat_arr, $cat_list);

        $data['notice'] = notice($lang);

        if ($this->input->post('posted')) {
            $record['plat_box'] = $this->input->post('plat_box');
            $record['period_box'] = $this->input->post('period_box');
            $record['freq_box'] = $this->input->post('freq_box');
            $record['order_box'] = $this->input->post('order_box');
            $record['cat_box'] = $this->input->post('cat_box');
            $record['start_date'] = $this->input->post('start_date');
            $record['end_date'] = $this->input->post('end_date');
            $record['currency'] = $this->input->post('currency');

            if ($record['freq_box']) {
                fetch_operator($freq, "frequency", $this->input->post('frequency'));
                foreach ($freq as $k => $v) {
                    if ($k == 'frequency') {
                        $record['frequency'] = $k . ">=" . $v;
                    } else {
                        $record['frequency'] = $k . " " . $v;
                    }
                }
            }

            $currency = $this->input->post('currency');

            if ($record['order_box']) {
                fetch_operator($value, "order_value", $this->input->post('order_value'));
                foreach ($value as $k => $v) {
                    if ($k == 'order_value') {
                        $record['order_value'] = $k . ">=" . $v;
                    } else {
                        $record['order_value'] = $k . " " . $v;
                    }
                }
            }

            $record['plat_list'] = $this->input->post('joined_plat_list');
            $record['cat_list'] = $this->input->post('joined_cat_list');

            $this->generate_csv($record);

        }
        if ($this->input->post('posted') != TRUE) {
            $this->load->view('marketing/customer_extraction/customer_extraction_view', $data);
        }
    }

    public function generate_csv($record)
    {
        $data['lang'] = $this->_load_parent_lang();
        $data['output'] = $this->customer_extraction_model->get_csv($record, $where);
        $data['filename'] = 'customers.csv';

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

?>