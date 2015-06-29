<?php

class Product_category_report extends MY_Controller
{
    private $app_id = "ACC0003";
    private $lang_id = "en";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/product_model');
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $this->load->view('account/product_category_report/index_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function get_csv()
    {
        if ($this->input->post("search") == 1) {
            $data = $this->product_model->get_product_category_report();
            $this->load->view('output_csv.php', $data);
        }
    }

    public function get_contact_email()
    {
        return 'shing-alert@eservicesgroup.com';
    }
}


