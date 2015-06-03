<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "sales_report_model.php";

class Aftership_report_model extends Sales_report_model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_report_service()
	{
		$this->load->library('service/rpt_aftership_service');
		return $this->rpt_aftership_service;
	}

	public function get_data($start_date, $end_date, $where = array())
	{
		return $this->get_report_service()->get_data($start_date, $end_date, $where = array());
	}

	public function get_aftership_report_for_ftp($start_date, $end_date, $where = array())
	{
		return $this->get_report_service()->get_aftership_report_for_ftp($start_date, $end_date, $where);
	}



}

/* End of file aftership_report_model.php */
/* Location: ./system/application/models/report/aftership_report_model.php */