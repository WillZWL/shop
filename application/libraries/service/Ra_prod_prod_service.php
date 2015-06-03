<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Ra_prod_prod_service extends Base_service
{
	private $class_factory_service;
	private $price_service;

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$this->load = $CI->load;
		include_once(APPPATH."libraries/dao/Ra_prod_prod_dao.php");
		$this->set_dao(new Ra_prod_prod_dao());
		include_once(APPPATH . 'libraries/service/Class_factory_service.php');
		$this->set_class_factory_service(new Class_factory_service());
		include_once(APPPATH . 'libraries/service/Price_service.php');
		$this->set_price_service(new Price_service());
	}

	public function get_avail_ra_list($sku)
	{
		return $this->get_dao()->get_avail_ra_list($sku);
	}

	public function get_avail_ra_list_w_price($sku, $platform_id = 'WSGB')
	{
		$ra_list = $this->get_avail_ra_list($sku);

		$size = 0;

		if ($ra_list)
		{
			$size = count($ra_list);
		}
		else
		{
			return array();
		}

		$p_srv = $this->get_class_factory_service()->get_price_service($platform_id);

		for($i = 0; $i < $size; $i++)
		{
			$ra_price = $p_srv->get_price($ra_list[$i]['ra_sku']);

			if ($ra_price <= 0)
			{
				$ra_list[$i] = array();
			}
			else
			{
				$ra_list[$i]["ra_price"] = $ra_price;
			}
		}

		return $ra_list;
	}

	public function set_class_factory_service($serv)
	{
		$this->class_factory_service = $serv;
	}

	public function get_class_factory_service()
	{
		return $this->class_factory_service;
	}

	public function set_price_service($serv)
	{
		$this->price_service = $serv;
	}

	public function get_price_service()
	{
		return $this->price_service;
	}

	public function get_ra_prods_w_sku_key($sku)
	{
		if ($obj_list=$this->get_dao()->get_list(array("sku"=>$sku)))
		{
			foreach ($obj_list as $obj)
			{
				$ar_prod[$obj->get_rcm_prod_id_1()] = 1;
			}
			return $ar_prod;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_ra_prod_w_cat_key($where=array(), $option=array())
	{
		$data = array();
		if ($rs_list = $this->get_dao()->get_ra_prod_list_by_sub_cat($where, $option))
		{
			if(!$lang_id = get_lang_id())
			{
				$lang_id = 'en';
			}
			foreach ($rs_list as $rs)
			{
				if($listing_info_dto = $this->price_service->get_listing_info($rs['sku'], $where["platform_id"], $lang_id))
				{
					$data[$rs['sub_cat_id']][] = $listing_info_dto;
				}
			}
		}

		return $data;
	}
}
?>