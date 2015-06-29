<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_comparison_by_period_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_sales_comparison_by_period_service');
    }

    public function get_report_service()
    {
        $this->load->library('service/rpt_sales_comparison_by_period_service');
        return $this->rpt_sales_comparison_by_period_service;
    }

    public function get_xls($from_date1, $to_date1,
                $from_date2, $to_date2)
    {
        return $this->get_report_service()->get_xls($from_date1, $to_date1,
                $from_date2, $to_date2);
    }
}

/* End of file sales_comparison_by_period_report_model.php */
/* Location: ./system/application/models/report/sales_comparison_by_period_report_model.php */