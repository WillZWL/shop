<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_stock_valuation_service extends Report_service
{
    private $inv_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Inventory_service.php");
        $this->set_inv_service(new Inventory_service());
        $this->set_output_delimiter(',');
    }

    public function get_csv($sku, $prod_name)
    {
        $arr = $this->get_data($sku, $prod_name);
        return $this->convert($arr);
    }

    public function get_data($sku = '', $prod_name = '')
    {
        $where = array();

        if (!empty($sku)) {
            $where['inventory.prod_sku'] = $sku;
        }

        if (!empty($prod_name)) {
            $where['product.name like '] = '%' . $prod_name . '%';
        }

        return $this->get_inv_service()->get_stock_valuation($where);
    }

    public function get_inv_service()
    {
        return $this->inv_service;
    }

    public function set_inv_service($value)
    {
        $this->inv_service = $value;
        return $this;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/rpt_stock_valuation_xml2csv.txt';
    }
}


