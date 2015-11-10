<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_dispatch_with_hs_code_service extends Report_service
{
    private $so_service;
    private $pricing_tool_model;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/pricing_tool_model');

        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
        $this->set_output_delimiter('none');
    }

    public function get_csv($from_date, $to_date, $where = array())
    {
        $arr = $this->get_data($from_date, $to_date, $where);
        return $arr;
    }

    public function get_data($from_date = '', $to_date = '', $where = array())
    {
        $arr = $this->get_so_service()->get_dispatch_data($where, $from_date, $to_date);
        $data = $this->process_data_row($arr);
        return $data;
    }

    public function get_so_service()
    {
        return $this->so_service;
    }

    public function set_so_service($value)
    {
        $this->so_service = $value;
        return $this;
    }

    public function process_data_row($arr)
    {
        $new_list = array();
        if ($arr) {
            $last_so_no = "";
            foreach ($arr as $row) {
                $row['prod_name'] = str_replace('"', '""', $row['prod_name']);
                $row['prod_name'] = '"' . $row['prod_name'] . '"';

                $row['amount'] = number_format($row['amount'] * $row['rate'], 2, '.', '');

                if ($row['so_no'] == $last_so_no) {
                    //$row['so_no'] = '-----------------';
                    $row['order_create_date'] = '-----------------';
                    $row['pack_date'] = '-----------------';
                    $row['dispatch_date'] = '-----------------';
                    $row['tracking_no'] = '-----------------';
                    $row['courier_id'] = '-----------------';
                    $row['amount'] = '-----------------';
                }

                if ($row['so_no'] != '-----------------') {
                    $last_so_no = $row['so_no'];
                }

                $new_list[] = $row;
            }

            unset($arr);

        }

        return $new_list;
    }

    public function get_header()
    {
        return "SO No,Warehouse ID,MasterSKU,Product Name,Quantity,HS Code,Order Create Date,Pack Date,Dispatch Date,Amount (USD),Origin Country ID, Destination Country ID, Courier ID, Tracking No\r\n";
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


