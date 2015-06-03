<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class rpt_fulfillment_service extends Report_service
{
	private $so_service;

	public function rpt_fulfillment_service()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_service(new So_service());
		$this->set_output_delimiter(',');
	}

	public function set_so_service($value)
	{
		return $this->so_service = $value;
	}

	public function get_so_service()
	{
		return $this->so_service;
	}

	public function get_data($where = array(),$option = array())
	{
		$arr = $this->get_so_service()->get_fulfillment_so($where,$option);
		$header = $this->get_header();
		$data = $this->convert($arr,'');
		return $data;
	}

	public function get_header()
	{

		$header = "order no,Platform,Tracking no,Courier,Warehouse,Fully Allocated,Dispatched date\r\n";
		return $header;
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return '';
	}
}
