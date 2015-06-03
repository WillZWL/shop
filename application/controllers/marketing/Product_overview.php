<?php
DEFINE("PLATFORM_TYPE", "SKYPE");

class Product_overview extends MY_Controller
{

	private $app_id="MKT0046";
	private $lang_id="en";

	//must set to public for view
	public $overview_path;
	public $default_platform_id;

	public function __construct()
	{
		parent::__construct();
		$this->overview_path = 'marketing/product_overview';
		$this->load->model($this->overview_path.'_'.strtolower(PLATFORM_TYPE).'_model', 'product_overview_model');
		$this->load->helper(array('url', 'notice', 'object', 'operator'));
		$this->load->library('service/pagination_service');
		$this->load->library('service/context_config_service');
		$this->load->library('service/display_qty_service');
		$this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
	}

	public function index($platform_id = "")
	{
		$sub_app_id = $this->_get_app_id()."00";
		$_SESSION["LISTPAGE"] = base_url().$this->overview_path."/?".$_SERVER['QUERY_STRING'];

		if ($this->input->post("posted") && $_POST["check"])
		{
			$rsresult = "";
			$shownotice = 0;
			foreach ($_POST["check"] as $rssku)
			{
				$success = 0;
				list($platform,$sku) = explode("||",$rssku);
				if (($price_obj = $this->product_overview_model->get_price(array("sku"=>$sku, "platform_id"=>$platform)))!==FALSE)
				{
					if (empty($price_obj))
					{
						$price_obj = $this->product_overview_model->get_price();
						set_value($price_obj, $_POST["price"][$platform][$rssku]);
						$price_obj->set_sku($rssku);
						$price_obj->set_platform_id($platform);
						//$price_obj->set_listing_status('L');
						$price_obj->set_status(1);
						$price_obj->set_allow_express('N');
						$price_obj->set_is_advertised('N');
						$price_obj->set_max_order_qty(100);
						$price_obj->set_auto_price('N');
						if ($this->product_overview_model->add_price($price_obj))
						{
							$success = 1;
						}
					}
					else
					{
						set_value($price_obj, $_POST["price"][$platform][$sku]);
						if ($this->product_overview_model->update_price($price_obj))
						{
							$success = 1;
						}
					}
				}
				if ($success)
				{
					if ($product_obj = $this->product_overview_model->get("product", array("sku"=>$sku)))
					{
						$prev_webqty = $product_obj->get_website_quantity();
						set_value($product_obj, $_POST["product"][$platform][$sku]);
						if($_POST["product"][$platform][$sku]["website_quantity"] != $prev_webqty)
						{
							include_once(APPPATH."libraries/dao/product_dao.php");
							$prod_dao = new Product_dao();
							$vpo_where = array("vpo.sku"=>$product_obj->get_sku());
							$vpo_option = array("to_currency_id"=>"GBP", "orderby"=> "vpo.price > 0 DESC, vpo.platform_currency_id = 'GBP' DESC, vpo.price *  er.rate DESC", "limit"=>1);

							if ($vpo_obj = $prod_dao->get_prod_overview_wo_cost_w_rate($vpo_where, $vpo_option))
							{
								$display_qty = $this->display_qty_service->calc_display_qty($vpo_obj->get_cat_id(), $_POST["product"][$platform][$sku]["website_quantity"], $vpo_obj->get_price());
								$product_obj->set_display_quantity($display_qty);
							}
						}
						if ($this->product_overview_model->update("product", $product_obj))
						{
							$success = 1;
						}
						else
						{
							$success = 0;
						}
					}
					else
					{
						$success = 0;
					}
				}
				if (!$success)
				{
					$shownotice = 1;
				}
				$rsresult .= "{$rssku} -> {$success}\\n";
			}
			if ($shownotice)
			{
				$_SESSION["NOTICE"] = $rsresult;
			}
			redirect(current_url()."?".$_SERVER['QUERY_STRING']);
		}

		$where = array();
		$option = array();

		$submit_search = 0;

		$option["inventory"] = 1;

		if ($this->input->get("sku") != "")
		{
			$where["sku LIKE "] = "%".$this->input->get("sku")."%";
			$submit_search = 1;
		}

		if ($this->input->get("cat_id") != "")
		{
			$where["cat_id"] = $this->input->get("cat_id");
		}

		if ($this->input->get("sub_cat_id") != "")
		{
			$where["sub_cat_id"] = $this->input->get("sub_cat_id");
		}

		if ($this->input->get("brand_id") != "")
		{
			$where["brand_id"] = $this->input->get("brand_id");
		}

		if ($this->input->get("supplier_id") != "")
		{
			$where["supplier_id"] = $this->input->get("supplier_id");
		}

		if ($this->input->get("prod_name") != "")
		{
			$where["prod_name LIKE "] = "%".$this->input->get("prod_name")."%";
			$submit_search = 1;
		}

		if($this->input->get("platform_id") != "")
		{
			$where["platform_id"] = $this->input->get("platform_id");
			$submit_search = 1;
		}

		if ($this->input->get("clearance") != "")
		{
			$where["clearance"] = $this->input->get("clearance");
			$submit_search = 1;
		}

		if ($this->input->get("listing_status") != "")
		{
			$where["listing_status"] = $this->input->get("listing_status");
			$submit_search = 1;
		}

		if ($this->input->get("inventory") != "")
		{
			fetch_operator($where, "inventory", $this->input->get("inventory"));
			$submit_search = 1;
		}

		if ($this->input->get("website_quantity") != "")
		{
			fetch_operator($where, "website_quantity", $this->input->get("website_quantity"));
			$submit_search = 1;
		}

		if ($this->input->get("website_status") != "")
		{
			$where["website_status"] = $this->input->get("website_status");
			$submit_search = 1;
		}

		if ($this->input->get("sourcing_status") != "")
		{
			$where["sourcing_status"] = $this->input->get("sourcing_status");
			$submit_search = 1;
		}

		if ($this->input->get("purchaser_updated_date") != "")
		{
			fetch_operator($where, "purchaser_updated_date", $this->input->get("purchaser_updated_date"));
			$submit_search = 1;
		}

		if ($this->input->get("shiptype_name") != "")
		{
			$where["shiptype_name"] = $this->input->get("shiptype_name");
			$submit_search = 1;
		}

		if ($this->input->get("profit") != "")
		{
			fetch_operator($where, "profit", $this->input->get("profit"));
			$option["refresh_margin"] = 1;
			$submit_search = 1;
		}

		if ($this->input->get("margin") != "")
		{
			fetch_operator($where, "margin", $this->input->get("margin"));
			$option["refresh_margin"] = 1;
			$submit_search = 1;
		}

		if ($this->input->get("price") != "")
		{
			fetch_operator($where, "price", $this->input->get("price"));
			$submit_search = 1;
		}

		$sort = $this->input->get("sort");
		$order = $this->input->get("order");

		$limit = '20';

		$pconfig['base_url'] = $_SESSION["LISTPAGE"];
		$option["limit"] = $pconfig['per_page'] = $limit;
		if ($option["limit"])
		{
			$option["offset"] = $this->input->get("per_page");
		}

		if (empty($sort))
		{
			$sort = "prod_name";
		}

		if (empty($order))
			$order = "asc";

		if($sort == "margin" || $sort == "profit")
		{
			$option["refresh_margin"] = 1;
		}


		$option["orderby"] = $sort." ".$order;

		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		if ($this->input->get("search"))
		{
			$data["objlist"] = $this->product_overview_model->get_product_list($where, $option, $lang);
			$data["total"] = $this->product_overview_model->get_product_list_total($where, $option);

		}

		$pconfig['total_rows'] = $data['total'];
		$this->pagination_service->set_show_count_tag(TRUE);
		$this->pagination_service->initialize($pconfig);

		$data["notice"] = notice($lang);
		$data["clist"] = $this->product_overview_model->price_service->get_platform_biz_var_service()->selling_platform_dao->get_list(array("type"=>PLATFORM_TYPE, "status"=>1));
		$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
		$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
//		$data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
		$data["searchdisplay"] = "";
		$this->load->view($this->overview_path.'/product_overview_v', $data);
	}


	public function js_overview()
	{
		$this->product_overview_model->print_overview_js();
	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id(){
		return $this->lang_id;
	}
}

/* End of file product.php */
/* Location: ./system/application/controllers/product.php */