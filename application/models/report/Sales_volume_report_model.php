<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_volume_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_sales_volume_service');
    }

    public function get_csv($where = array(), $option)
    {

        $header = $this->get_sales_volume_service()->get_header();

        $data = $this->get_sales_volume_service()->get_data($where, $option);

        return $header . $data;

    }

    public function get_sales_volume_service()
    {
        return $this->rpt_sales_volume_service;
    }

}
