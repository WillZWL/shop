<?php

class Warehouse_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/warehouse_service');
	}

	public function get_warehouse_list($where=array(), $option=array())
	{
		return $this->warehouse_service->get_dao()->get_list($where,$option);
	}

}
