<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "sales_report_model.php";

class Dispatch_report_model extends Sales_report_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_report_service()
    {
        $this->load->library('service/rpt_dispatch_service');
        return $this->rpt_dispatch_service;
    }
}

/* End of file sales_report_model.php */
/* Location: ./system/application/models/report/sales_report_model.php */