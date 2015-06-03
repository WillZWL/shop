<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";


class Rpt_special_order_report_service extends Report_service
{
	private $so_service;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_service(new So_service());
		$this->set_output_delimiter(',');
	}

	public function set_so_service($value)
	{
		$this->so_service = $value;
		return $this;
	}

	public function get_so_service()
	{
		return $this->so_service;
	}

	public function get_csv($where = array())
	{
		set_time_limit(300);
		$arr = $this->get_so_service()->get_dao()->get_special_order_report($where, array("limit"=>-1));
//print $this->get_so_service()->get_dao()->db->last_query();
		return $this->convert($arr);
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return APPPATH . 'data/special_order_report_xml2csv.txt';
	}
}
