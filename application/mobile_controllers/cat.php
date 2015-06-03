<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Cat extends MOBILE_Controller
{
	const NUMBER_OF_ITEMS_PER_PAGE = 8;
	const NUMBER_OF_PAGES_TO_DISPLAY = 4;

	public function Cat()
	{
		parent::MOBILE_Controller(array("template" => "default"));
		$this->load->model('website/common_data_prepare_model');
		$this->load->helper(array('url'));
		$this->load->model('website/website_model');
		$this->load->model('marketing/product_model');
		$this->load->model('marketing/category_model');
		$this->load->library('service/affiliate_service');
		$this->load->library('service/price_website_service');
	}

	public function view($cat_id)
	{
		$data = $this->common_data_prepare_model->get_data_array($this, array("cat_id" => $cat_id
															, "rpp" => self::NUMBER_OF_ITEMS_PER_PAGE
															, "display_range" => self::NUMBER_OF_PAGES_TO_DISPLAY));
//		var_dump($data["product_list"]);

# SBF #2284 add Tradedoubler variable js portion
/*
		$this->tradedoubler_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
		$prod_list = array();
		foreach($obj_list AS $key=>$obj)
		{
			$param_list["id"] = $obj->get_sku();
			$param_list["price"] = $obj->get_price();
			$param_list["currency"] = array_shift(array_keys($_SESSION["CURRENCY"]));
			$param_list["name"] = $obj->get_prod_name();
			$prod_list[] = $param_list;
		}
		$td_variable_code = $this->tradedoubler_tracking_script_service->get_variable_code("category", $prod_list, "");
		$this->template->add_js($td_variable_code, "print");
*/

		$this->load_tpl('content', 'cat', $data, TRUE);
	}

	public function get_cat_filter_grid_info($level, $sku_list)
	{
		$condition = "p.sku IN ('".implode("','", $sku_list)."')";
		$where[$condition] = null;
		$where['p.status'] = 2;
		$where['scex.lang_id'] = $where['sscex.lang_id'] = get_lang_id();

		switch($level)
		{
			case 1:
				$option['groupby'] = "p.sub_cat_id";
				break;
			case 2:
				$option['groupby'] = "p.sub_sub_cat_id";
				break;
			case 3:
			default:
				return null;
		}

		if($rs = $this->category_model->get_cat_filter_grid_info($level, $where, $option))
		{
			foreach($rs as $key=>$val)
			{
				$rs[$key]['url'] = $this->website_model->get_cat_url($val['id']);
			}
		}
		return $rs;
	}

	public function get_brand_filter_grid_info($sku_list)
	{
		$condition = "p.sku IN ('".implode("','", $sku_list)."')";
		$where[$condition] = null;
		$where['p.status'] = 2;
		$option['groupby'] = "p.brand_id";

		return $this->category_model->get_brand_filter_grid_info($where, $option);
	}
}

?>
