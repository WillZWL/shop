<?php

class Profit_var_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/region_service');
		$this->load->library('service/country_service');
		$this->load->library('service/courier_service');
		$this->load->library('service/delivery_type_service');
		$this->load->library('service/shiptype_service');
		$this->load->library('service/selling_platform_service');
		$this->load->library('service/language_service');
	}

	public function get_selling_platform_list($where = array(), $option = array())
	{
		return $this->platform_biz_var_service->get_selling_platform_list($where, $option);
	}

	public function get_currency_list()
	{
		return $this->platform_biz_var_service->get_currency_list();
	}

	public function get_platform_biz_var($id="")
	{
		return $this->platform_biz_var_service->get_platform_biz_var($id);
	}

	public function check_platform($value)
	{
		return $this->selling_platform_service->get_dao()->get(array("id"=>$value));
	}

	public function update($data)
	{
		return $this->platform_biz_var_service->update($data);
	}

	public function add($data)
	{
		return $this->platform_biz_var_service->get_dao()->insert($data);
	}

	public function __autoload()
	{
		$this->platform_biz_var_service->load_vo();
	}

	public function get_courier_region_list()
	{
		return $this->region_service->get_dao()->get_list(array("type"=>"C"));
	}

	public function get_courier_list()
	{
		return $this->courier_service->get_dao()->get_list(array("type"=>"W", "weight_type <>"=>"CO"));
	}

	public function get_country_list($where = array(), $option = array())
	{
		return $this->country_service->get_dao()->get_list($where, $option);
	}

	public function get_delivery_type_list()
	{
		return $this->delivery_type_service->get_dao()->get_list();
	}

	public function get_shiptype_list($where=array())
	{
		return $this->shiptype_service->get_dao()->get_list($where);
	}
}

?>