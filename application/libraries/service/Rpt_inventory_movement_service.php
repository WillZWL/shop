<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_inventory_movement_service extends Report_service
{
	private $inv_service;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Inv_movement_service.php");
		$this->set_invm_service(new Inv_movement_service());
		$this->set_output_delimiter(',');
	}

	public function set_invm_service($value)
	{
		$this->invm_service = $value;
		return $this;
	}

	public function get_invm_service()
	{
		return $this->invm_service;
	}

	public function get_data($sku,$start_date, $end_date)
	{
		$where = array();

		if (!empty($sku))
		{
			$where['sku'] = $sku;
		}

		if (!empty($start_date))
		{
			$where['start_date'] = $start_date;
		}

		if (!empty($end_date))
		{
			$where['end_date'] = $end_date;
		}

		return $this->get_invm_service()->get_inventory_movement($where);
	}

	public function get_csv($sku,$start_date, $end_date)
	{
		$arr = $this->get_data($sku,$start_date, $end_date);
		return $this->convert($arr);
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return APPPATH . 'data/rpt_inventory_movement_xml2csv.txt';
	}
}

/* End of file rpt_inventory_movement_service.php */
/* Location: ./system/application/libraries/service/Rpt_inventory_movement_service.php */