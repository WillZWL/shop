<?php

include_once "Base_service.php";

class Promotion_text_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Promotion_text_dao.php");
		$this->set_dao(new Promotion_text_dao());
		include_once(APPPATH."libraries/service/Product_service.php");
		$this->set_product_service(new Product_service());
	}

	public function get_product_service()
	{
		return $this->product_service;
	}

	public function set_product_service(Base_service $svc)
	{
		$this->product_service = $svc;
	}

	public function get_promo_text($platform_type = "Skype", $lang_id = "", $platform_id ="", $sku = "")
	{
		return $this->get_dao()->get_promo_text($platform_type, $lang_id, $platform_id, $sku);
	}

	public function get_product_list($where=array(), $option=array())
	{
		return $this->product_service->get_dao()->get_list_w_country_id($where, $option, "Product_list_w_name_dto");
	}

	public function get_product_list_total($where=array(),$option=array())
	{
		return $this->product_service->get_dao()->get_list_w_country_id($where, $option,  "Product_list_w_name_dto");
	}

	public function get($where=array())
	{
		return $this->get_dao()->get($where);
	}

	public function get_list($where=array(), $option=array())
	{
		return $this->get_dao()->get_list($where, $option);
	}

	public function insert($obj)
	{
		return $this->get_dao()->insert($obj);
	}

	public function update($obj)
	{
		return $this->get_dao()->update($obj);
	}
}

/* End of file promotion_text_dao.php */
/* Location: ./app/libraries/dao/Promotion_text_dao.php */