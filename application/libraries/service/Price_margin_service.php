<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Price_margin_service extends Base_service
{
	private $class_factory_service;

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$this->load = $CI->load;
		$this->load->helper(array('object'));
		include_once(APPPATH."libraries/dao/Price_margin_dao.php");
		$this->set_dao(new Price_margin_dao());
		include_once(APPPATH."libraries/service/Class_factory_service.php");
		$this->set_class_factory_service(new Class_factory_service());
		include_once(APPPATH."libraries/service/Product_service.php");
		$this->set_product_service(new Product_service());
		include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
		$this->set_platform_biz_var_service(new Platform_biz_var_service());
	}

	public function set_class_factory_service($srv)
	{
		$this->class_factory_service = $srv;
	}

	public function get_class_factory_service()
	{
		return $this->class_factory_service;
	}

	public function set_product_service($srv)
	{
		$this->product_service = $srv;
	}

	public function get_product_service()
	{
		return $this->product_service;
	}

	public function set_platform_biz_var_service($srv)
	{
		$this->platform_biz_var_service = $srv;
	}

	public function get_platform_biz_var_service()
	{
		return $this->platform_biz_var_service;
	}

	public function refresh_margin_for_top_deal()
	{
		if ($obj_list = $this->get_platform_biz_var_service()->selling_platform_dao->get_list(array("status"=>1)))
		{
			foreach ($obj_list as $obj)
			{
				$this->refresh_margin($obj->get_id());
			}
		}
	}

	public function refresh_all_platform_margin($platform_where = array(), $skulist = "")
	{
		$ret = array();
		$ret["status"] = FALSE;
		$platform_where["status"] = 1;
		// e.g. $platform_where["id"] = $platform_id

		$this->db->save_queries = false;

		// echo "Updating price_margin";
		if($sp_list = $this->get_platform_biz_var_service()->selling_platform_dao->get_list($platform_where))
		{
			foreach ($sp_list as $key => $sellingplatform_obj)
			{
				// sleep(2);
				set_time_limit(300);
				ini_set("memory_limit","300M");

				$platform_id = $sellingplatform_obj->get_id();
				$updatelist .= $platform_id . ",\n";
				if($skulist == "")
				{
					echo "<br>Updating price_margin $platform_id,";
					$this->refresh_margin($platform_id);
				}
				else
				{
					$listedprod = $this->get_product_service()->get_product_w_price_info($platform_id, $skulist,'Product_cost_dto');
					if(count($listedprod) > 0)
					{
						echo "<br>Updating price_margin $platform_id $skulist,";
						$this->_update_margin($listedprod,$platform_id);
					}
				}
			}

			$ts = date("Y-m-d H:i:s");
			// mail("itsupport@eservicesgroup.net", "VB price_margin platforms update", "price_margin refreshed for following platforms @ GMT+0 $ts: \n$updatelist");
			$ret["status"] = TRUE;
			$ret["updatelist"] = $updatelist;
			return $ret;
		}
		else
		{
			$ret["error_message"] = __LINE__."price_margin_service. Unable to retrieve sellling platform list. DB error "
									.$get_platform_biz_var_service()->selling_platform_dao->db->_error_message();
		}

		return $ret;
	}

	public function refresh_margin($platform = 'WEBHK')
	{
		$prod_list = $this->get_product_service()->get_listed_product_list($platform, 'Product_cost_dto');
		$this->_update_margin($prod_list,$platform);
	}

	public function refresh_margin2($platform = 'WEBHK')
	{
		$prod_list = $this->get_product_service()->get_listed_product_list($platform, 'Product_cost_dto');
		$this->_update_margin2($prod_list,$platform);
	}

	public function refresh_margin_amazon($platform = 'AMUS')
	{
		$prod_list = $this->get_product_service()->get_listed_product_list($platform, 'Product_cost_dto');
		$this->_update_margin_amazon($prod_list,$platform);
	}

	public function refresh_latest_margin($where = array())
	{
		$prod_list = $this->get_product_service()->get_product_w_margin_req_update($where, 'Product_cost_dto');
		$this->_update_margin($prod_list, $where["v_prod_overview_w_update_time.platform_id"]);
	}

	public function _update_margin($prod_list, $platform='WEBHK')
	{
		if($platform == "")
		{
			return FALSE;
		}

		include_once(APPPATH."libraries/service/Class_factory_service.php");
		$cf_srv = new Class_factory_service();
		$pr_svc = $cf_srv->get_platform_price_service($platform);

		$sample_vo = $this->get_dao()->get();
		foreach ($prod_list as $prod)
		{
			$margin_vo = clone $sample_vo;

			$prod->set_price($pr_svc->get_price($prod));
			$pr_svc->calc_logistic_cost($prod);
			$pr_svc->calculate_profit($prod);
			set_value($margin_vo, $prod);
			$this->get_dao()->replace($margin_vo);
		}

		unset($pr_svc);
		unset($p_svc);
	}

	public function _update_margin2($prod_list, $platform='WEBHK')
	{
		if($platform == "")
		{
			$platform = "WEBHK";
		}

		$pf_var = $this->get_platform_biz_var_service()->get_platform_biz_var($platform);

		$shiptype = 1;

		if ($pf_var)
		{
			$shiptype = $pf_var->get_default_shiptype();
		}


		$price_srv = $this->get_class_factory_service()->get_price_service($platform);
		$sample_vo = $this->get_dao()->get();

		foreach ($prod_list as $prod)
		{
			$margin_vo = clone $sample_vo;
			$prod->set_shiptype($shiptype);
			$prod->set_price($price_srv->get_price($prod->get_sku()));

			$price_srv->calc_profit($prod);
			set_value($margin_vo, $prod);

			$this->get_dao()->replace($margin_vo);
			if($prod->get_sku() == '10051-NA')
			{
				var_dump($this->get_dao()->db->last_query());
				var_dump($prod);
				exit;
			}

		}
	}

	public function _update_margin_amazon($prod_list, $platform='AMUS')
	{
		if($platform == "")
		{
			return FALSE;
		}

		include_once(APPPATH."libraries/service/Class_factory_service.php");
		$cf_srv = new Class_factory_service();
		$pr_svc = $cf_srv->get_platform_price_service($platform);

		$sample_vo = $this->get_dao()->get();
		foreach ($prod_list as $prod)
		{
			$p_srv = $pr_svc->get_price_service_from_dto($prod);
			$p_srv->set_platform_id($prod->get_platform_id());
			$p_srv->set_platform_curr_id($prod->get_platform_currency_id());

			// get fulfillment centre id for amazon
			$price_ext_obj = $pr_svc->get_price_ext_dao()->get(array("sku"=>$prod->get_sku(),"platform_id"=>$prod->get_platform_id()));
			if(!$price_ext_obj || !$fc_id = $price_ext_obj->get_fulfillment_centre_id())
			{
				$fc_id = "DEFAULT";
			}
			$p_srv->set_fulfillment_centre_id($fc_id);

			$margin_vo = clone $sample_vo;

			$prod->set_price($pr_svc->get_price($prod));
			$pr_svc->calc_freight_cost($prod, $p_srv, $prod->get_platform_currency_id());
			$pr_svc->calculate_profit($prod);
			set_value($margin_vo, $prod);

			$this->get_dao()->replace($margin_vo);
		}
		unset($pr_svc);
		unset($p_svc);
	}

	public function get_price_service()
	{
		return $this->price_service;
	}

	public function set_price_service(Base_service $svc)
	{
		$this->price_service = $svc;
		return $this;
	}

	public function insert_or_update_margin($sku, $platform_id, $price = null, $profit, $margin)
	{
		if($price_margin_obj = $this->get_dao()->get(array('sku'=>$sku, 'platform_id'=>$platform_id)))
		{
			if(!$temp_price_margin_obj = $this->get_dao()->get(array('sku'=>$sku, 'platform_id'=>$platform_id, 'profit'=>$profit, 'margin'=>$margin)))
			{
				$price_margin_obj->set_profit($profit);
				$price_margin_obj->set_selling_price($price);
				$price_margin_obj->set_margin($margin);
				$this->get_dao()->update($price_margin_obj);
			}
		}
		else
		{
			$price_margin_obj = $this->get_dao()->get();
			$price_margin_obj->set_sku($sku);
			$price_margin_obj->set_platform_id($platform_id);
			$price_margin_obj->set_selling_price($price);
			$price_margin_obj->set_profit($profit);
			$price_margin_obj->set_margin($margin);
			$this->get_dao()->insert($price_margin_obj);
		}
	}

	public function get_cross_sell_product($prod_info, $platform_id, $language_id, $price, $price_adjustment)
	{
		return $this->get_dao()->get_cross_sell_product($prod_info, $platform_id, $language_id, $price, $price_adjustment);
	}
}

/* End of file price_margin_service.php */
/* Location: ./system/application/libraries/service/Price_margin_service.php */