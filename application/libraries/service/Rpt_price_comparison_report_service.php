<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";


class Rpt_price_comparison_report_service extends Report_service
{
	private $so_service;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Price_service.php");
		$this->set_price_service(new Price_service());
		include_once(APPPATH."libraries/service/Country_service.php");
		$this->set_country_service(new Country_service());
		$this->set_output_delimiter(',');
	}

	public function set_price_service($value)
	{
		$this->price_service = $value;
		return $this;
	}

	public function get_price_service()
	{
		return $this->price_service;
	}

	public function set_country_service($value)
	{
		$this->country_service = $value;
		return $this;
	}

	public function get_country_service()
	{
		return $this->country_service;
	}

	public function get_data()
	{
		$country_list = $this->country_service->get_list(array("status"=>1, "allow_sell"=>1));
		foreach($country_list AS $obj)
		{
			$res[$obj->get_id()] = $this->get_price_service()->get_dao()->get_price_comparison_report_item_list($obj->get_id());
		}

		return $res;
	}

	public function get_csv($where)
	{
		$res = "";
		$arr = $this->get_data();

		$country_list = $this->country_service->get_list(array("status"=>1, "allow_sell"=>1));
		foreach($country_list AS $obj)
		{
			if(trim($this->convert($arr[$obj->get_id()])) != "")
			{
				$res .= trim($this->convert($arr[$obj->get_id()], FALSE))."\n";
			}
		}
		$header = "SKU, Product Name, Country Name, Platform ID (Website), Price, Platform ID (Skype), Price\n";
		$res = $header.$res;

		return $res;
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
