<?php

class Pricing_tool_amfr extends MY_Controller
{

	private $app_id = 'MKT0012';
	private $lang_id = 'en';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','notice','image'));
		$this->load->library('input');
		$this->load->library('service/pagination_service');
		$this->load->model('marketing/pricing_tool_amfr_model');
		$this->load->library('service/context_config_service');
	}

	public function index()
	{
		$data = array();
		include_once APPPATH."language/".$this->_get_app_id()."00_".$this->_get_lang_id().".php";
		$data["lang"] = $lang;
		$this->load->view("marketing/pricing_tool_amfr/pricing_tool_amfr_index",$data);
	}

	public function plist()
	{
		$where = array();
		$option = array();
		$sub_app_id = $this->_get_app_id()."02";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;


		if (($sku = $this->input->get("sku")) != "" || ($prod_name = $this->input->get("name")) != "")
		{

			$data["search"] = 1;
			if ($sku != "")
			{
				$where["sku"] = $sku;
			}

			if ($prod_name != "")
			{
				$where["name"] = $prod_name;
			}

			$sort = $this->input->get("sort");
			$order = $this->input->get("order");

			$limit = '20';

			$pconfig['base_url'] = current_url()."?".$_SERVER['QUERY_STRING'];
			$option["limit"] = $pconfig['per_page'] = $limit;

			if ($option["limit"])
			{
				$option["offset"] = $this->input->get("per_page");
			}

			if (empty($sort))
				$sort = "sku";

			if (empty($order))
				$order = "asc";

			$option["orderby"] = $sort." ".$order;

			$data["objlist"] = $this->pricing_tool_amfr_model->get_product_list($where, $option);
			$data["total"] = $this->pricing_tool_amfr_model->get_product_list_total($where);
			$pconfig['total_rows'] = $data['total'];
			$this->pagination_service->set_show_count_tag(TRUE);
			$this->pagination_service->msg_br = TRUE;
			$this->pagination_service->initialize($pconfig);

			$data["notice"] = notice($lang);

			$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
			$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
		}

		$this->load->view('marketing/pricing_tool_amfr/pricing_tool_amfr_list', $data);
	}

	public function view($value="")
	{
		$no_of_valid_supplier = $this->pricing_tool_model->check_valid_supplier_cost($value);
		if($no_of_valid_supplier == 1)
		{
			$data = array();
			$data["valid_supplier"] = 1;
			define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));
			if($this->input->post('posted'))
			{
				//print_r($_POST);
				$this->pricing_tool_amfr_model->__autoload();
				$price_obj = unserialize($_SESSION["price_obj"]);
				$price_obj->set_platform_id("AMFR");
				$price_obj->set_sku($value);
				$price_obj->set_default_shiptype($this->input->post('default_shipping'));
				$price_obj->set_ext_mapping_code($this->input->post('ext_mapping_code'));
				$price_obj->set_status($this->input->post('status'));
				$price_obj->set_auto_price($this->input->post('auto_price'));
				$price_obj->set_latency($this->input->post('latency'));
				$price_obj->set_max_order_qty($this->input->post('max_order_qty'));
				$price_obj->set_platform_code($this->input->post('platform_code'));
				$price_obj->set_listing_status($this->input->post('listing_status'));
				$dst = $this->input->post('default_shipping');
				$price_obj->set_price($this->input->post("selling_price"));
				if($this->input->post('allow_express'))
				{
					$ae = 'Y';
				}
				else
				{
					$ae = 'N';
				}

				if($this->input->post('is_advertised'))
				{
					$ia = 'Y';
				}
				else
				{
					$ia = 'N';
				}
				$price_obj->set_allow_express($ae);
				$price_obj->set_is_advertised($ia);

				if($this->input->post('formtype') == "update")
				{
					$ret = $this->pricing_tool_amfr_model->update($price_obj);
				}
				else
				{
					$ret = $this->pricing_tool_amfr_model->add($price_obj);
				}

				if($ret === FALSE)
				{
					$_SESSION["NOTICE"] = $this->db->_error_message();
				}
				else
				{
					unset($_SESSION["price_obj"]);
					if($this->input->post('target') != "")
					{
						$data["prompt_notice"] = 1;
					}
				}

				$this->pricing_tool_amfr_model->__autoload_product_vo();
				$prod_obj = unserialize($_SESSION["prod_obj"]);
				$prod_obj->set_mpn($this->input->post('mpn'));
				$prod_obj->set_upc($this->input->post('upc'));
				$prod_obj->set_ean($this->input->post('ean'));
				$prod_obj->set_clearance($this->input->post('clearance'));
				$prod_obj->set_website_quantity($this->input->post('webqty'));
				if($this->input->post('webqty') == 0)
				{
					$prod_obj->set_website_status('O');
				}
				else
				{
					$prod_obj->set_website_status($this->input->post('status'));
				}

				$ret2 = $this->pricing_tool_amfr_model->update_product($prod_obj);
				if($ret2 === FALSE)
				{
					$_SESSION["NOTICE"] = $this->db->_error_message();
				}
				else
				{
					unset($_SESSION["prod_obj"]);
				}
				if(trim($this->input->post('m_note')) != "")
				{
					$note_obj = $this->pricing_tool_amfr_model->get_note();
					$note_obj->set_platform_id("AMFR");
					$note_obj->set_sku($value);
					$note_obj->set_type('M');
					$note_obj->set_note($this->input->post('m_note'));
					if (!($ret3 = $this->pricing_tool_amfr_model->add_note($note_obj)))
					{
						$_SESSION["NOTICE"] = "update_note_failed";
					}
				}

				if(trim($this->input->post('s_note')) != "")
				{
					$note_obj = $this->pricing_tool_amfr_model->get_note();
					$note_obj->set_sku($value);
					$note_obj->set_type('S');
					$note_obj->set_note($this->input->post('s_note'));
					if (!($ret4 = $this->pricing_tool_amfr_model->add_note($note_obj)))
					{
						$_SESSION["NOTICE"] = "update_note_failed";
					}
				}

				Redirect(base_url()."marketing/pricing_tool_amfr/view/".$value);
			}

			$shiptype_list = $this->pricing_tool_amfr_model->get_shiptype_list();
			$price_obj = $this->pricing_tool_amfr_model->get_price_obj(array("platform_id"=>"AMFR","sku"=>$value));
			$data["action"] = "update";
			if(empty($price_obj))
			{
				$price_obj = $this->pricing_tool_amfr_model->get_price_obj();
				$data["action"] = "add";
			}
			include_once APPPATH."language/".$this->_get_app_id()."01_".$this->_get_lang_id().".php";
			$data["lang"] = $lang;
			$_SESSION["price_obj"] = serialize($price_obj);
			$data["canedit"] = 1;
			$data["value"] = $value;
			$data["price_obj"] = $price_obj;
			$data["inv"] = $this->pricing_tool_amfr_model->get_inventory(array("sku"=>$value));

			$tmpx = $this->pricing_tool_amfr_model->get_quantity_in_orders($value);

			foreach($tmpx as $key=>$val)
			{
				$data["qty_in_orders"] .= $lang["last"]." ".$key." ".$lang["days"]." : ".$val."<br>";
			}

			$data["qty_in_orders"] = ereg_replace("<br>$","",$data["qty_in_orders"]);

			$product_cost_dto = $this->pricing_tool_amfr_model->get_product_cost_dto($value,"AMFR");
			$shiptype = array();

			if($value != "")
			{
				$data["objcount"] = 0;
				foreach($product_cost_dto as $obj)
				{
					$shiptype[] = $obj->get_shiptype();
					$shiptype_name = $obj->get_shiptype_name();
					$obj->set_price($price_obj->get_price());
					$this->pricing_tool_amfr_model->set_dto_ps($obj);
					$this->pricing_tool_amfr_model->calc_profit_ps();


					if($shiptype_name != "PG")
					{
						$data["table_row"] .= $this->pricing_tool_amfr_model->get_table_row_ps($price_obj->get_default_shiptype());
					}
					else
					{
						$data["table_row"] .= $this->pricing_tool_amfr_model->get_table_row_ps_pg($price_obj->get_default_shiptype());
					}
					$data["objcount"]++;
				}

				$prod_obj =  $this->pricing_tool_amfr_model->get_prod($value);
				if ($data["objcount"])
				{
					$data["table_header"] = $this->pricing_tool_amfr_model->get_table_header_ps();
					//$data["js"] = $this->pricing_tool_amfr_model->get_js_ps();
					$data["shiptype"] = $shiptype;
					$currency = $this->pricing_tool_amfr_model->get_currency();
					$data["currency_obj"] = $this->pricing_tool_amfr_model->get_currency_detail($currency);
					$data["mkt_note_obj"] = $this->pricing_tool_amfr_model->get_note($value, "M");
					$data["src_note_obj"] = $this->pricing_tool_amfr_model->get_note($value, "S");
				}
			}

			if($value != "")
			{
				$data["supplier"] = $this->pricing_tool_amfr_model->get_current_supplier($value);
				$fcat = $this->pricing_tool_amfr_model->get_freight_cat($prod_obj->get_freight_cat_id());
				$data["freight_cat"] = $fcat->get_name();
			}

			$data["target"] = $this->input->get('overview');
			$data["prod_obj"] = $prod_obj;
			$data["website_link"] = $this->context_config_service->value_of("website_domain");
			$_SESSION["prod_obj"] = serialize($prod_obj);
			$data["notice"] = notice($lang);
		}

		$this->load->view("marketing/pricing_tool_amfr/pricing_tool_amfr_view",$data);

	}

	public function get_js()
	{
		header("Content-type: text/javascript; charset: UTF-8");
		header("Cache-Control: must-revalidate");
		$offset = 60 * 60 * 24;
		$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		header($ExpStr);
		$js ="	function rePrice(typeInput)
				{
					var obj;
					var type;
					for(var i =0; i <shiptype.length;i++)
					{
						obj = document.getElementById('sp['+shiptype[i]+']');
						obj.value = document.getElementById('sp['+typeInput+']').value;

						type = shiptype[i];

						var price = document.getElementById('sp['+type+']').value * 1;
						price = price.toFixed(2);
						var freight_cost = document.getElementById('freight_cost['+type+']').innerHTML * 1;
						freight_cost = freight_cost.toFixed(2);
						var declared = document.getElementById('declared_rate['+type+']').value * price / 100 * 1;
						declared = declared.toFixed(2);
						var delivery_charge = document.getElementById('delivery_charge['+type+']').innerHTML*1;
						delivery_charge = delivery_charge.toFixed(2);
						var vat_pcent = document.getElementById('vat_percent['+type+']').value * 1;
						var vat = (declared * 1 + delivery_charge * 1) * vat_pcent * 1 / (100 + vat_pcent * 1);
						vat = vat.toFixed(2);
						var duty = document.getElementById('duty_percent['+type+']').value / 100 * declared * 1;
						duty = duty.toFixed(2);
						var payment = document.getElementById('payment_charge_rate['+type+']').value / 100 * price * 1;
						payment = payment.toFixed(2);
						var admin_fee = document.getElementById('admin_fee['+type+']').innerHTML * 1;
						admin_fee = admin_fee.toFixed(2);
						var commission = document.getElementById('platform_commission['+type+']').value * (price * 1 +  delivery_charge * 1) / 100;
						commission = commission.toFixed(2);
						var delivery_cost = document.getElementById('delivery_cost['+type+']').innerHTML * 1;
						delivery_cost = delivery_cost.toFixed(2);
						var supplier_cost = document.getElementById('scost['+type+']').value * 1;
						supplier_cost = supplier_cost.toFixed(2);

						var total = price *1 + delivery_charge * 1;
						total = total.toFixed(2);
						var cost =  vat *1+ duty *1+ payment *1+ admin_fee *1+ freight_cost *1+ delivery_cost *1+ supplier_cost *1 + commission * 1;

						cost = cost.toFixed(2);
						var profit = total *1 - cost * 1 ;
						profit = profit.toFixed(2);
						var margin = 100 * profit * 1 / (price*1 - vat*1) ;
						margin = margin.toFixed(2);

						var color = '';
						if(profit > 0)
						{
							color = '#ddffdd';
						}
						else
						{
							color = '#ffdddd';
						}
						var rowo = document.getElementById('row['+type+']');
						rowo.style.backgroundColor = color;

						var declo = document.getElementById('declare['+type+']');
						if (declo != null) declo.innerHTML = declared;
						var vato = document.getElementById('vat['+type+']');
						if (vato != null) vato.innerHTML = vat;

						var dutyo = document.getElementById('duty['+type+']');
						dutyo.innerHTML = duty;
						var plat_commo = document.getElementById('platform_comm['+type+']');
						plat_commo.innerHTML = commission;
						var pmo = document.getElementById('pc['+type+']');
						pmo.innerHTML = payment;
						var dco = document.getElementById('delivery_charge['+type+']');
						dco.innerHTML = delivery_charge;
						var tco = document.getElementById('total_cost['+type+']');
						tco.innerHTML = cost;
						var ttlo = document.getElementById('total['+type+']');
						ttlo.innerHTML = total;
						var pfto = document.getElementById('profit['+type+']');
						pfto.innerHTML = profit;
						var mgno = document.getElementById('margin['+type+']');
						mgno.innerHTML = margin + '%';
					}
				}";
		echo $js;
	}

	public function _get_app_id()
	{
		return $this->app_id;

	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}
}

