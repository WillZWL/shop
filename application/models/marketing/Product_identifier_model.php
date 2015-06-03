<?php

class Product_identifier_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/product_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/product_identifier_service');
	}

	public function __autoload_product_identifier_vo()
	{
		$this->product_identifier_service->get_dao()->include_vo();
	}

	public function get_product_identifier_obj($where = array())
	{
		return $this->product_identifier_service->get($where);
	}

	public function insert($obj)
	{
		return $this->product_identifier_service->insert($obj);
	}

	public function update($obj)
	{
		return $this->product_identifier_service->update($obj);
	}

	public function get_product_list($where=array(), $option=array())
	{
		return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
	}

	public function get_product_list_total($where=array(), $option=array())
	{
		$option["num_rows"] = 1;
		return $this->product_service->get_dao()->get_list_w_name($where, $option);
	}

	public function get_prod($sku="")
	{
		if($sku != "")
		{
			return $this->product_service->get_dao()->get(array("sku"=>$sku));
		}
		else
		{
			return $this->product_service->get_dao()->get();
		}
	}

	public function get_mapping_obj($where = array())
	{
		return $this->product_service->get_master_sku($where);
	}

	public function get_sell_country_list()
	{
		return $this->country_service->get_sell_country_list();
	}

	public function get_product_identifier_list_grouped_by_country($where = array())
	{
		return $this->product_identifier_service->get_product_identifier_list_grouped_by_country($where);
	}
}
?>