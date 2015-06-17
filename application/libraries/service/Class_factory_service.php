<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Class_factory_service extends Base_service
{
	public function __construct()
	{
		parent::__construct();
		//$CI =& get_instance();
		//$this->load = $CI->load;
		include_once(APPPATH . 'helpers/string_helper.php');
	}

	public function get_price_service($method='', $set = array())
	{
		/*
			array setting: 	supplier_fc - Supplier Fulfillment Centre (Warehouse id)
							customer_fc - Customer fulfillment Centre (Warehouse id)
							supplier_region - Supplier Region (Courier Region)
							ccountry - Client Country (region id)

		*/

		$p_srv_name = strtolower($method)."_def_price_service";

		$p_srv_path = APPPATH."libraries/service/".$p_srv_name.".php";

		if(!is_file($p_srv_path))
		{
			return FALSE;
		}

		include_once ($p_srv_path);
		$p_class_name = ucwords($p_srv_name);

		$svc = new $p_class_name($set["supplier_region"],$set["supplier_fc"],$set["customer_fc"],$set["ccountry"],$set["weight_cat"],$set["price"]);

		return $svc;
	}

	public function get_platform_price_service($platform_id)
	{
		include_once(APPPATH."libraries/service/Selling_platform_service.php");
		$sp_srv = new Selling_platform_service();
		if ($sp_obj = $sp_srv->get(array("id" => $platform_id))) {
			$paltform_type = $sp_obj->get_type();

			$p_srv_name = "Price_".strtolower($paltform_type)."_service";

			$p_srv_path = APPPATH."libraries/service/".$p_srv_name.".php";

			if ( ! is_file($p_srv_path)) {
				return FALSE;
			}

			include_once ($p_srv_path);
			$svc = new $p_srv_name();

			return $svc;
		}
	}
}
