<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_marketing_service extends Report_service
{
    private $supplier_service;
    private $class_factory_service;
    private $product_note_service;
    private $product_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Supplier_service.php");
        $this->set_supplier_service(new Supplier_service());
        include_once(APPPATH . "libraries/service/Class_factory_service.php");
        $this->set_class_factory_service(new Class_factory_service());
        include_once(APPPATH . "libraries/service/Product_note_service.php");
        $this->set_product_note_service(new Product_note_service());
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->set_product_service(new Product_service());

        $this->set_output_delimiter(',');
    }

    public function get_data($start_time = '', $end_time = '')
    {
        $list = $this->get_supplier_service()->get_supplier_prod_history_for_report(
            $start_time, $end_time);
        $price_srv = $this->get_class_factory_service()->get_price_service('WSGB');

        foreach ($list as $obj) {
            $price_srv->calc_profit($obj);
            //$obj->set_note($this->get_product_note_service()->get_note_by_sku($obj->get_sku())->get_note());
            $note_obj = $this->get_product_note_service()->get_note_by_sku($obj->get_sku());
            if ($note_obj) {
                $obj->set_note($note_obj->get_note());
            }
        }

        $prod_srv = $this->get_product_service()->get_new_product_for_report($start_time, $end_time);
        $new_prod_list = $this->get_product_service()->get_new_product_for_report(
            $start_time, $end_time);


        foreach ($new_prod_list as $obj) {
//          $price_srv->cal_profit($obj);
            $list[] = $obj;
        }

        return $list;
    }

    public function get_supplier_service()
    {
        return $this->supplier_service;
    }

    public function set_supplier_service($value)
    {
        $this->supplier_service = $value;
        return $this;
    }

    public function get_class_factory_service()
    {
        return $this->class_factory_service;
    }

    public function set_class_factory_service($srv)
    {
        $this->class_factory_service = $srv;
    }

    public function get_product_note_service()
    {
        return $this->product_note_service;
    }

    public function set_product_note_service($value)
    {
        $this->product_note_service = $value;
        return $this;
    }

    public function get_product_service()
    {
        return $this->product_service;
    }

    public function set_product_service($value)
    {
        $this->product_service = $value;
        return $this;
    }

    public function get_csv($from_date, $to_date)
    {
        //$arr = $this->get_data($from_date, $to_date);
        //return $this->convert($arr);
        return NULL;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        //return APPPATH . 'data/rpt_sales_xml2csv.txt';
        return '';
    }
}

/* End of file rpt_stock_valuation_service.php */
/* Location: ./system/application/libraries/service/Rpt_valuation_service.php */