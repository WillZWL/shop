<?php
include_once "pricing_tool_model.php";

class Pricing_tool_rakuten_model extends Pricing_tool_model
{

	public function __construct()
	{
		parent::Pricing_tool_model("RAKUTEN");
	}

	public function get_rrp_factor_by_sku($sku='')
	{
		$default_rrp_factor = 1.34;
		return $default_rrp_factor;
	// 	$rrp_factor = NULL;
	// 	if ($sku == '')
	// 	{
	// 		$rrp_factor = $default_rrp_factor;
	// 	}
	// 	else
	// 	{
	// 		$price_obj = $this->get(array('sku'=>$sku));
	// 		echo "<pre>"; var_dump($price_obj); var_dump($this->db->last_query()); die();
	// 		if ($price_obj)
	// 		{
	// 			$rrp_factor = $price_obj->get_rrp_factor();
	// 		}
	// 		else
	// 		{
	// 			$product_obj = $this->get_product_service()->get(array('sku'=>$sku));

	// 			$where = array();
	// 			$where['p.cat_id'] = $product_obj->get_cat_id();
	// 			$where['p.sub_cat_id'] = $product_obj->get_sub_cat_id();
	// 			$where['p.sub_sub_cat_id'] = $product_obj->get_sub_sub_cat_id();
	// 			$where['p.sku != '] = $sku;

	// 			$list = $this->get_product_service()->get_list_having_price($where, array('limit'=>1));
	// 			if (count($list) == 0)
	// 			{
	// 				$rrp_factor = $default_rrp_factor;
	// 			}
	// 			else
	// 			{
	// 				$product_obj = $list[0];
	// 				$price_obj = $this->get(array('sku'=>$product_obj->get_sku()));
	// 				if ($price_obj)
	// 				{
	// 					$rrp_factor = $price_obj->get_rrp_factor();
	// 				}
	// 				else
	// 				{
	// 					$rrp_factor = $default_rrp_factor;
	// 				}
	// 			}
	// 		}
	// 	}

	// 	if (is_null($rrp_factor))
	// 	{
	// 		$rrp_factor = $default_rrp_factor;
	// 	}

	// 	return $rrp_factor;
	}

}

