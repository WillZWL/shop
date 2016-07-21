<?php

class Product_category_report extends MY_Controller
{
    private $appId = "ACC0003";
    private $lang_id = "en";

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $this->load->view('account/product_category_report/index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function get_csv()
    {
        if ($this->input->post("search") == 1) {

            $where = array();
            $where['m.ext_sku is not null'] = NULL;

            $option = array();
            $option['limit'] = -1;
            $option['orderby'] = 'm.ext_sku';

            $result = array();
            $result['filename'] = 'product_category_report.csv';
            $result['output'] = $this->sc['Product']->getProductCategoryReport($where, $option);

            $this->load->view('output_csv.php', $result);
        }
    }

    public function get_contact_email()
    {
        return 'shing-alert@eservicesgroup.com';
    }
}


