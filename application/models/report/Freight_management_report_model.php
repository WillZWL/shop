<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "sales_report_model.php";

class Freight_management_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // public function get_report_service()
    // {
    //  $this->load->library('service/rpt_freight_management_service');
    //  return $this->rpt_freight_management_service;
    // }

    // public function get_data($start_date, $end_date, $where = array())
    // {
    //  return $this->get_report_service()->get_data($start_date, $end_date, $where = array());
    // }

    // public function get_freight_management_report_for_ftp($start_date, $end_date, $where = array())
    // {
    //  return $this->get_report_service()->get_freight_management_report_for_ftp($start_date, $end_date, $where);
    // }



}

/* End of file freight_management_report_model.php */
/* Location: ./system/application/models/report/freight_management_report_model.php */