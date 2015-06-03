<?php

class Promotion_text_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/promotion_text_service');
		$this->load->library('service/selling_platform_service');
		$this->load->library('service/language_service');
	}

	public function get($where=array())
	{
		return $this->promotion_text_service->get($where);
	}

	public function get_list($where=array(), $option=array())
	{
		return $this->promotion_text_service->get_list($where, $option);
	}

	public function get_product_list($where=array(), $option=array())
	{
		return $this->promotion_text_service->get_product_list($where, $option);
	}

	public function get_product_list_total($where=array(),$option = array())
	{
		$option["num_rows"] = 1;
		return $this->promotion_text_service->get_product_list_total($where, $option);
	}

	public function get_platform_id_list($where=array() ,$option=array())
	{
		return $this->selling_platform_service->get_list($where, $option);
	}

	public function get_lang_list($where=array(), $option=array())
	{
		return $this->language_service->get_list($where, $option);
	}

	public function insert($obj)
	{
		return $this->promotion_text_service->insert($obj);
	}

	public function update($obj)
	{
		return $this->promotion_text_service->update($obj);
	}
}

