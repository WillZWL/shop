<?php
DEFINE("PLATFORM_TYPE", "FNAC");

class Product_overview_fnac extends MY_Controller
{

	private $app_id="MKT0064";
	private $lang_id="en";

	//must set to public for view
	public $overview_path;
	public $default_platform_id;

	public function __construct()
	{
		parent::__construct();
		$this->overview_path = 'marketing/product_overview_'.strtolower(PLATFORM_TYPE);
		$this->load->model($this->overview_path.'_model', 'product_overview_model');
		$this->load->helper(array('url', 'notice', 'object', 'operator'));
		$this->load->library('service/fnac_service');
		$this->load->library('service/pagination_service');
		$this->load->library('service/context_config_service');
		$this->load->library('service/display_qty_service');
		$this->load->library('service/wms_warehouse_service');
		$this->load->library('service/price_margin_service');
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
				$profit = $_POST["hidden_profit"][$platform][$sku];
				$margin = $_POST["hidden_margin"][$platform][$sku];
				$price = $_POST["price"][$platform][$sku]["price"];

				$current_listing_status = $_POST["price"][$platform][$sku]["listing_status"];
				$country_id = substr($platform, -2);
				if (($price_obj = $this->product_overview_model->get_price(array("sku"=>$sku, "platform_id"=>$platform)))!==FALSE)
				{
					if (empty($price_obj))
					{
						$price_obj = $this->product_overview_model->get_price();
						set_value($price_obj, $_POST["price"][$platform][$sku]);
						$price_obj->set_sku($sku);
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

							// update price_margin tb for all platforms
							$this->price_margin_service->insert_or_update_margin($sku, $platform, $price, $profit, $margin);
						}
					}
					else
					{
						set_value($price_obj, $_POST["price"][$platform][$sku]);
						if ($this->product_overview_model->update_price($price_obj))
						{
							$success = 1;

							// update price_margin tb for all platforms
							$this->price_margin_service->insert_or_update_margin($sku, $platform, $price, $profit, $margin);
						}
					}
				}

				if ($success)
				{
					if (($price_ext_obj = $this->product_overview_model->get_price_ext(array("sku"=>$sku, "platform_id"=>$platform)))!==FALSE)
					{
						if (empty($price_ext_obj))
						{
							$price_ext_obj = $this->product_overview_model->get_price_ext();
							set_value($price_ext_obj, $_POST["price_extend"][$platform][$sku]);
							$price_ext_obj->set_sku($sku);
							$price_ext_obj->set_platform_id($platform);
							$price_ext_obj->set_ext_qty($_POST["price_extend"][$platform][$sku]["ext_qty"]*1);

							if($current_listing_status == "N")
							{
								$price_ext_obj->set_ext_qty(0);
								$price_ext_obj->set_action(null);
								$price_ext_obj->set_remark(null);
							}

							if (!$this->product_overview_model->add_price_ext($price_ext_obj))
							{
								$success = 0;
							}
						}
						else
						{
							set_value($price_ext_obj, $_POST["price_extend"][$platform][$sku]);

							if($current_listing_status == "N")
							{
								$price_ext_obj->set_ext_qty(0);
								$price_ext_obj->set_action(null);
								$price_ext_obj->set_remark(null);
							}

							if (!$this->product_overview_model->update_price_ext($price_ext_obj))
							{
								$success = 0;
							}
						}
					}
				}

				if ($success)
				{
					if ($product_obj = $this->product_overview_model->get("product", array("sku"=>$sku)))
					{
						if ($this->product_overview_model->update("product", $product_obj))
						{
							$success = 1;

							if($this->input->post('sync'))
							{
								$sync_sku[] = $sku;
							}
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

			if($this->input->post("sync"))
			{
				foreach($sync_sku as $sku)
				{
					if($xmlResponse = $this->fnac_service->send_offers_update_request(array($sku), $country_id))
					{
						if(isset($xmlResponse["error_message"]) || $xmlResponse->error->attributes()->code=="ERR_023")
						{
							$price_obj = $this->product_overview_model->get_price(array("sku"=>$sku, "platform_id"=>$platform));
							if($price_obj)
							{
								$price_obj->set_listing_status('N');
								$ret = $this->product_overview_model->update_price($price_obj);
							}

							$price_ext_obj = $this->product_overview_model->get_price_ext(array("sku"=>$sku, "platform_id"=>$platform));
							if($price_ext_obj)
							{
								$price_ext_obj->set_remark(NULL);
								$ret = $this->product_overview_model->price_service->get_price_ext_dao()->update($price_ext_obj);
							}

							if(isset($xmlResponse["error_message"]))
								$_SESSION["NOTICE"] .= "$country_id - Fnac Offer Update Failed \n".$xmlResponse["error_message"]."\n";
							else
								$_SESSION["NOTICE"] .= "$country_id - Fnac Offer Update Failed - Fnac Batch not found"."\n";
						}
						else
						{
							if($batch_id = (string)$xmlResponse->batch_id)
							{
								$notice = $this->fnac_service->check_fnac_batch_offers_update_status($xmlResponse, $sku, $batch_id, $country_id);
								$_SESSION["NOTICE"] .= "$sku - $notice \n";
								// $action = "update";
								// if (!($price_ext_obj = $this->product_overview_model->price_service->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform))))
								// {
								// 	$action = "insert";
								// 	if (!isset($price_ext_vo))
								// 	{
								// 		$price_ext_vo = $this->product_overview_model->price_service->get_price_ext_dao()->get();
								// 	}
								// 	$price_ext_obj = clone $price_ext_vo;
								// 	$price_ext_obj->set_sku($sku);
								// 	$price_ext_obj->set_platform_id($platform);
								// 	$price_ext_obj->set_ext_qty(0);
								// }
								// $price_ext_obj->set_action("P");
								// $price_ext_obj->set_remark((string)$batch_id);
								// $ret = $this->product_overview_model->price_service->get_price_ext_dao()->$action($price_ext_obj);
							}
							else
							{
								$_SESSION["NOTICE"] .= "\n". __LINE__ . " Fail FNAC update - no batch ID";
							}
						}
					}
					else
					{
						$_SESSION["NOTICE"] = "Fail FNAC update";
					}
				}
			}

			redirect(current_url()."?".$_SERVER['QUERY_STRING']);
		}

		$where = array();
		$option = array();

		$submit_search = 0;

		$option["inventory"] = 1;
		$option["supplier_prod"] = 1;
		$option["master_sku"] = 1;

		if ($this->input->get("master_sku") != "")
		{
			$where["ext_sku LIKE "] = "%".$this->input->get("master_sku")."%";
			$submit_search = 1;
		}

		if ($this->input->get("cat_id") != "")
		{
			$where["p.cat_id"] = $this->input->get("cat_id");
		}

		if ($this->input->get("sub_cat_id") != "")
		{
			$where["p.sub_cat_id"] = $this->input->get("sub_cat_id");
		}

		if ($this->input->get("brand_id") != "")
		{
			$where["p.brand_id"] = $this->input->get("brand_id");
		}

		if ($this->input->get("supplier_id") != "")
		{
			$where["sp.supplier_id"] = $this->input->get("supplier_id");
		}

		if ($this->input->get("prod_name") != "")
		{
			$where["p.name LIKE "] = "%".$this->input->get("prod_name")."%";
			$submit_search = 1;
		}

		if($this->input->get("platform_id") != "")
		{
			$condition = "pbv.selling_platform_id IN ('";
			$plat_arr = explode(",", $this->input->get("platform_id"));
			$condition .= implode("','", $plat_arr);
			$condition .= "')";
			$where[$condition] = null;
			$submit_search = 1;
		}

		if ($this->input->get("clearance") != "")
		{
			$where["p.clearance"] = $this->input->get("clearance");
			$submit_search = 1;
		}

		if ($this->input->get("listing_status") != "")
		{
			if($this->input->get("listing_status") == "N")
			{
				$where["(pr.listing_status = 'N' or pr.listing_status is null)"] = null;
			}
			else
			{
				$where["pr.listing_status"] = $this->input->get("listing_status");
			}

			$submit_search = 1;
		}

		if ($this->input->get("inventory") != "")
		{
			fetch_operator($where, "inventory", $this->input->get("inventory"));
			$submit_search = 1;
		}

		if ($this->input->get("ext_qty") != "")
		{
			fetch_operator($where, "ext_qty", $this->input->get("ext_qty"));
			$submit_search = 1;
		}

		if ($this->input->get("website_status") != "")
		{
			$where["p.website_status"] = $this->input->get("website_status");
			$submit_search = 1;
		}

		if ($this->input->get("supplier_status") != "")
		{
			$where["supplier_status"] = $this->input->get("supplier_status");
			$submit_search = 1;
		}

		if ($this->input->get("purchaser_updated_date") != "")
		{
			fetch_operator($where, "sp.modify_on", $this->input->get("purchaser_updated_date"));
			$submit_search = 1;
		}

		if ($this->input->get("shiptype_name") != "")
		{
			$where["shiptype_name"] = $this->input->get("shiptype_name");
			$submit_search = 1;
		}

		if ($this->input->get("profit") != "")
		{
			fetch_operator($where, "pm.profit", $this->input->get("profit"));
			$option["refresh_margin"] = 1;
			$option["refresh_platform_list"] = $plat_arr;
			$submit_search = 1;
		}

		if ($this->input->get("margin") != "")
		{
			fetch_operator($where, "pm.margin", $this->input->get("margin"));
			$option["refresh_margin"] = 1;
			$option["refresh_platform_list"] = $plat_arr;
			$submit_search = 1;
		}

		if ($this->input->get("price") != "")
		{
			fetch_operator($where, "pr.price", $this->input->get("price"));
			$submit_search = 1;
		}

		if ($this->input->get("surplusqty") != "")
		{
			switch($this->input->get("surplusqty_prefix"))
			{
				case 1:
					$where["surplus_quantity is not null and surplus_quantity > 0 and surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
					break;
				case 2:
					$where["surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
					break;
				case 3:
					$where["surplus_quantity >= {$this->input->get("surplusqty")}"] = null;
					break;
			}
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
			$sort = "p.name";
		}
		else
		{
			if(strpos($sort, "prod_name") !== FALSE)
				$sort = "p.name";
			elseif(strpos($sort, "listing_status") !== FALSE)
				$sort = "pr.listing_status";
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
			// HTML inside here
			$data["objlist"] = $this->product_overview_model->get_product_list_v2($where, $option, $lang);
			$data["total"] = $this->product_overview_model->get_product_list_total_v2($where, $option);
		}

		$pconfig['total_rows'] = $data['total'];
		$this->pagination_service->set_show_count_tag(TRUE);
		$this->pagination_service->initialize($pconfig);

		$wms_warehouse_where["status"] = 1;
		$wms_warehouse_where["type != 'W'"] = null;
		$data["wms_wh"] = $this->wms_warehouse_service->get_list($wms_warehouse_where, array('limit'=>-1, 'orderby'=>'warehouse_id'));

		$data["notice"] = notice($lang);
		$data["clist"] = $this->product_overview_model->price_service->get_platform_biz_var_service()->selling_platform_dao->get_list(array("type"=>PLATFORM_TYPE, "status"=>1));
		$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
		$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
//		$data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
		$data["searchdisplay"] = "";
		$this->load->view($this->overview_path.'/product_overview_v', $data);
	}

	public function check_fnac_batch_offers_update_status($xmlResponse, $country_id = "ES")
	{
		$platform_id = "FNAC".strtoupper($country_id);
		if($xmlResponse)
		{
			if((string)$xmlResponse->attributes()->status == "OK")
			{
				$notice = "Fnac Sync Result:\n\n";
				foreach($xmlResponse->offer as $offer)
				{
					$resp = (string)$offer->attributes()->status;
					switch($resp)
					{
						case "OK":
							$sku = (string)$offer->offer_seller_id;
							$ext_item_id = (string)$offer->product_fnac_id;
							$action = "update";
							if (!($price_ext_obj = $this->product_overview_model->price_service->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id))))
							{
								$action = "insert";
								if (!isset($price_ext_vo))
								{
									$price_ext_vo = $this->product_overview_model->price_service->get_price_ext_dao()->get();
								}
								$price_ext_obj = clone $price_ext_vo;
								$price_ext_obj->set_sku($sku);
								$price_ext_obj->set_platform_id($platform_id);
								$price_ext_obj->set_ext_qty(0);
							}
							$price_ext_obj->set_ext_item_id($ext_item_id);
							$ret = $this->product_overview_model->price_service->get_price_ext_dao()->$action($price_ext_obj);

							$notice .= (string)$offer->offer_seller_id . " => " . "Success" . "\n";
							break;
						case "ERROR":
							$notice .= (string)$offer->offer_seller_id . " => " . (string)$offer->error . "\n";
							break;
						default:
					}
				}
				return $notice;
			}
			elseif($xmlResponse->attributes()->status == "RUNNING" || $xmlResponse->attributes()->status == "ACTIVE")
			{
				$batch_id = $xmlResponse->batch_id;
				$newResponse = $this->fnac_service->send_batch_status_request($batch_id);
				return $this->check_fnac_batch_offers_update_status($newResponse);
			}
		}

		return "Unable to Connect to Fnac. Please Try Again.";
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