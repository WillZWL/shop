<?php

$ws_array = array(NULL, 'index');
if (in_array($GLOBALS["URI"]->segments[2], $ws_array))
{
	DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
}

require_once(BASEPATH . 'plugins/My_plugin/validator/postal_validator.php');

class Checkout extends MOBILE_Controller
{
	public function Checkout($allow_force_https=true)
	{
		parent::MOBILE_Controller(array("template" => "default"));
		$this->load->helper(array('url'));
		$this->load->library('service/context_config_service');
		$this->load->library('service/affiliate_service');
		$this->load->library('service/country_service');

		$this->postal_validator = new Postal_validator();

		#tracking pixels need it for sbf#1658
		$this->load->model('marketing/category_model');

		if ($allow_force_https && ($this->context_config_service->value_of("force_https")))
		{
			if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
			{
				$httpsurl = str_replace("http://", "https://", current_url());
				if ($_SERVER['QUERY_STRING'] != "")
				{
					$httpsurl .= "?".$_SERVER['QUERY_STRING'];
				}
				redirect ($httpsurl);
			}
		}
		$this->load->model('website/checkout_model');
	}

	public function js_credit_card($platform_curr, $total_amount)
	{
		$data['lang_text'] = $this->_get_language_file();
		$this->checkout_model->js_credit_card($platform_curr, $total_amount, $data);
	}

	private function get_skuinfo ($so_no)
	{
		$so_items = $this->so_service->get_soi_dao()->get_items_w_name(array("so_no"=>$so_no), array("lang_id" => get_lang_id()));
		foreach($so_items as $value)
		{
			$sku = $value->get_prod_sku();

			if($listing_info = $this->product_model->get_listing_info($sku, PLATFORMID, get_lang_id()))
			{
				if(!$prod_info = $this->product_model->get_website_product_info($sku, PLATFORMID, get_lang_id()))
				{
					$prod_info = $this->product_model->get_website_product_info($sku, PLATFORMID);
				}
				$brandname = $prod_info->get_brand_name();

				if($this->product_model->price_service->get(array("sku"=>$sku, "listing_status"=>"L", "platform_id"=>PLATFORMID)))
				{
					if(!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_cat_id(), "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
					{
						$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_cat_id(), "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
					}

					$localized_cat_name = $cat_obj->get_name();
					if(!$sc_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_sub_cat_id(), "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
					{
						$sc_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_sub_cat_id(), "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
					}
					$localized_sc_name = $sc_obj->get_name();
				}
			}
			$s["brand"] = $brandname;
			$s["cat_name"] = $localized_cat_name;
			$s["sc_name"] = $localized_sc_name;
			$skuinfo[] = $s;
		}
		return $skuinfo;
	}

	public function process_checkout($card_code="", $debug=0)
	{

		$_SESSION["POSTFORM"] = $vars = $_POST;
		if (isset($_SESSION["POSTFORM"]["p_enc"]))
		{
			include_once(BASEPATH."libraries/Encrypt.php");
			$encrypt = new CI_Encrypt();
			$platform_id = $encrypt->decode($_SESSION["POSTFORM"]["p_enc"]);

			if ($this->so_service->get_pbv_srv()->selling_platform_dao->get(array("id"=>$platform_id)))
			{
				$vars["platform_id"] = $platform_id;
			}
			else
			{
				$this->payment_result(0);
			}
		}

		if (!isset($vars["platform_id"]))
		{
			$vars["platform_id"] = PLATFORMID;
		}

		if ($card_code == "paypal")
		{
			$pmgw = "paypal";
		}
		else
		{
			if ($pc_obj = $this->country_credit_card_service->get_pmgw_card_dao()->get(array("code"=>$card_code)))
			{
				$pmgw = $pc_obj->get_payment_gateway_id();
				$vars["payment_methods"] = $pc_obj->get_card_id();
			}
			else
			{
				$pmgw = $card_code;
			}
		}

		$vars["payment_gateway"] = $pmgw;
		switch ($pmgw)
		{
			case "bibit":
				if ($this->context_config_service->value_of("bibit_model") == "redirect")
				{
					if ($this->check_login("checkout/index/{$debug}?".$_SERVER['QUERY_STRING']))
					{
						$_SESSION["review"] = $this->input->post("review");
						$this->checkout_model->payment_gateway_service->checkout($pmgw, $vars, $debug);
					}
				}
				else
				{
					$this->checkout_model->payment_gateway_service->checkout($pmgw, $vars, $debug);
				}
				break;
			case "moneybookers":
			case "global_collect":
			case "paypal":
				if ($_SESSION["client"]["logged_in"] && !$vars["email"])
				{
					$vars["email"] = $_SESSION["client"]["email"];
				}
				if ($this->client_service->check_email_login($vars))
				{
					if ($this->checkout_model->check_promo())
					{
						$this->checkout_model->payment_gateway_service->checkout($pmgw, $vars, $debug);
					}
					else
					{
						unset($_SESSION["promotion_code"]);
						echo "
							<script>
								window.parent.ChgPromoMsg(0, 1);
							</script>
							";
						exit;
					}
				}
				elseif($debug)
				{
					var_dump("Error ".__LINE__." : ".$this->db->_error_message()." -- ".$this->db->last_query());
				}
				else
				{
					$browser = get_browser(null, true);
					$url = base_url()."checkout/payment_result/0";
					if ($browser["javascript"])
					{
						echo "<script>top.document.location.href='$url';</script>";
						exit;
					}
					else
					{
						redirect($url);
					}
				}
				break;
		}
	}
	public function affiliate_tracking($so_obj, $soi_obj_list)
	{

		$this->load->library('service/affiliate_service');
		$this->load->library('service/platform_biz_var_service');

		$af_info = $this->affiliate_service->get_af_record();

		# calculate total price of cart
		$total_cart_price = 0;
		$total_item = 0;
		foreach($soi_obj_list as $soi_obj)
		{
			$total_cart_price += ($soi_obj->get_unit_price() * $soi_obj->get_qty());
			$total_item += $soi_obj->get_qty();
		}

		$so_no = $so_obj->get_so_no();
		if($af_info['af'])
		{
			switch($af_info['af'])
			{
				case 'LS':
					$to_currency="GBP";
					$ls_id="37439";
				case 'LSAU':
					if($af_info['af'] != 'LS')
					{
						$to_currency="AUD";
						$ls_id="37893";
					}
				case 'LSNZ':
					if($af_info['af'] != 'LS')
					{
						$to_currency="AUD";
						$ls_id="37893";
					}
					$valid_id = "/^[-a-zA-Z0-9._\/*]{34}$/";
					if(preg_match($valid_id, $_COOKIE["LS_siteID"]))
					{
						$ls_site_id = $_COOKIE["LS_siteID"];
						$ls_time_enter = $_COOKIE["LS_timeEntered"];

						if(count($soi_obj_list) > 0)
						{
							$pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>PLATFORMID));
							$ex_rate_obj = $this->exchange_rate_service->get(array("from_currency_id"=>$so_obj->get_currency_id(), "to_currency_id"=>$to_currency));
							$ex_rate = $ex_rate_obj->get_rate();

							foreach($soi_obj_list as $soi_obj)
							{
								$sku[$soi_obj->get_line_no()] = $soi_obj->get_prod_sku();
								$qty[$soi_obj->get_line_no()] = $soi_obj->get_qty();
								$vat = $soi_obj->get_amount() * $pbv_obj->get_vat_percent() / ($pbv_obj->get_vat_percent() + 100);
								$amount[$soi_obj->get_line_no()] = round(($soi_obj->get_amount() - $vat) * 100 * $ex_rate);
							}
							$skulist = implode("|", $sku);
							$qlist = implode("|", $qty);
							$amtlist = implode("|", $amount);
						}
						$this->template->add_js("https://track.linksynergy.com/ep?mid={$ls_id}&ord={$so_obj->get_so_no()}&skulist={$skulist}&qlist={$qlist}&amtlist={$amtlist}&cur={$to_currency}");
					}
					break;
				default:
			}
		}
	}

	public function response($pmgw, $debug=0)
	{
		if ($pmgw == "bibit" && $this->context_config_service->value_of("bibit_model") == "redirect")
		{
			$vars["orderKey"] = $this->input->get("orderKey");
			$vars["paymentStatus"] = $this->input->get("paymentStatus");
			$vars["paymentAmount"] = $this->input->get("paymentAmount");
			$vars["paymentCurrency"] = $this->input->get("paymentCurrency");
			$vars["mac"] = $this->input->get("mac");
		}
		else
		{
			$vars = $_POST;
		}
		$this->checkout_model->payment_gateway_service->response($pmgw, $vars, $debug);
	}

	public function payment_result($success="", $so_no="")
	{
		# reset the tracking script first, shopzilla etc will also be appended
		$data['tracking_script'] ="";

		# for tracking pixels
		$skuinfo[0]["brand"] = "";
		$skuinfo[0]["cat_name"] = "";
		$skuinfo[0]["sc_name"] = "";

		// Disable LP
		$data['no_lp'] = 1;

		#$success = 1;
		#$so_no="133557";	works in DEV and most likely LIVE
		# https://dev.valuebasket.com/checkout/payment_result/1/133557?debug=1

		$data["success"] = $success;
		$data["so_no"] = $so_no;
		$data["skuinfo"] = $this->get_skuinfo($so_no);

		if (($success != "1") && ($success != "0"))
		{
			show_404('page');
		}
		if($so_no == "" && $success == 1)
		{
			show_404('page');
		}

		$rightKey = false;
		if ($urlKey = $this->input->get("key"))
		{
//probably yandex
			include_once(APPPATH . "libraries/service/payment_gateway_redirect_yandex_service.php");
			$yandex_service = new Payment_gateway_redirect_yandex_service();
			if ($so_no)
			{
				$calculated_md5 = $yandex_service->get_encoded_url_key($so_no);
				if ($urlKey == $calculated_md5)
					$rightKey = true;
			}
		}

		if ($so_no)
		{
			if ($data["so"] = $this->checkout_model->so_service->get(array("so_no"=>$so_no)))
			{
				if ($_SESSION["client"]["id"] != $data["so"]->get_client_id() && (!$this->input->get("debug") && !$rightKey))
				{
					show_404('page');
				}
				$data["client"] = $this->client_service->get(array("id"=>$data["so"]->get_client_id()));
				$data["skuinfo"] = $this->get_skuinfo($data["so_no"]);	# tracking pixels

				$data["country"] = $this->checkout_model->region_service->country_dao->get(array("id"=>$data["so"]->get_delivery_country_id()));
				$data["courier"] = $this->checkout_model->so_service->get_pbv_srv()->get_dt_dao()->get(array("id"=>$data["so"]->get_delivery_type_id()));
				$data["so_items"] = $this->checkout_model->so_service->get_soi_dao()->get_items_w_name(array("so_no"=>$so_no));
				$data["so_ps"] = $this->checkout_model->so_service->get_sops_dao()->get(array("so_no"=>$so_no));
				$data["so_ext"] = $this->checkout_model->so_service->get_soext_dao()->get(array("so_no"=>$so_no));
			}
			else
			{
				show_404('page');
			}
		}

		if (($success && $so_no) || (!$success && $_SESSION["pmgw_message"]))
		{


			$data["message"] = $_SESSION["pmgw_message"];
			unset($_SESSION["pmgw_message"]);
			$data["step"] = 4;

			$data["origin_website"] = isset($_COOKIE['originw'])?$_COOKIE['originw']:($_COOKIE["LS_siteID"] != ''?13:11);
			$data["review"] = $_SESSION["review"];
			$data["adwords"] = "1";
		}

		$data["is_dev_site"] = $this->context_config_service->value_of("is_dev_site");

		# SBF#2185 putting salecycle code on both payment success n failure page
		$salecycle_enabled = FALSE;
		if ($salecycle_enabled)
		{

			$script_name = "";
			switch (PLATFORMCOUNTRYID)
			{
				case "GB": $script_name = "VALUEBASKET";   $account_no = 17061; break;
				case "AU": $script_name = "VALUEBASKETAU"; $account_no = 17211; break;

				# SBF#2117
				case "NZ": $script_name = "VALUEBASKETNZ"; $account_no = 17458; break;
				case "FR": $script_name = "VALUEBASKETFR"; $account_no = 17457; break;
				case "SG": $script_name = "VALUEBASKETSG"; $account_no = 17459; break;
				case "ES": $script_name = "VALUEBASKETES"; $account_no = 17607; break;
			}

			$script = <<<salecycle_script
			 <script type="text/javascript">
				try {var __scP=(document.location.protocol=="https:")?"https://":"http://";
				var __scS=document.createElement("script");__scS.type="text/javascript";
				__scS.src=__scP+"app.salecycle.com/capture/$script_name.js";
				document.getElementsByTagName("head")[0].appendChild(__scS);}catch(e){}
			</script>
salecycle_script;

			{# SBF#2185 1px by 1px SaleCycle PixelCapture.aspx should only appear on payment success page
				if ($script_name != "")
				{
					{
						if ($success && $so_no)
						{
							$script .= '<img src="https://app.salecycle.com/Import/PixelCapture.aspx?c='.$account_no.'&e='.$_SESSION["client"]["email"].'" />';
						}
					}

					$this->template->add_js($script, "print", FALSE, "body");
					// $this->template->add_js("/js/salecycle.js", "import", FALSE, "body");
				}
			}
		}

		if($success)
		{
			$af_info = $this->affiliate_service->get_af_record();
			$data["tracking_data"]["affiliate_name"] = $af_info["af"];

			$data["tracking_data"]["total_amount"] = $data["so"]->get_amount();
			$data["tracking_data"]["so"] = $data["so"];
			$data["tracking_data"]["soi"] = $data["so_items"];
			$data["tracking_data"]["sops"] = $data["so_ps"];
			$data["tracking_data"]["client_email"] = $_SESSION["client"]["email"];
		}

		if($success)
		{

			$is_new_customer = "new";	# or old
			$product_id		= "";
			$product_name	= "";
			$product_price	= "";
			$product_units	= "";
			# calculate total price of cart
			$total_cart_price = 0;
			$total_item = 0;
			$google_prodid = "";
			foreach($data["so_items"] as $key=>$soi_obj)
			{
				$total_cart_price += ($soi_obj->get_unit_price() * $soi_obj->get_qty());
				$total_item += $soi_obj->get_qty();

				$product_id 	.= "{$soi_obj->get_prod_sku()},";
				$product_name  	.= "{$soi_obj->get_name()},";
				$product_price	.= "{$soi_obj->get_unit_price()},";
				$product_units	.= "{$soi_obj->get_qty()},";
				$product_category	.= "{$soi_obj->get_cat_name()},";
			}
			$total_cart_price = number_format($total_cart_price, 2, ".", "");

			# append the default affiliate tracking codes
			$data['tracking_script'] .= $this->affiliate_tracking($data["so"], $data["so_items"]);

			# SBF#2247
			$adroll = true;
			if ($adroll)
			{
				unset($param);	// remove rubbish, as it might have been used earlier
				$param['price'] = $total_cart_price;
				$param['ORDER_ID'] = $so_no;
				// $param['SKU'] = $product_id;
				// $param['ORDER_VALUE'] = $total_cart_price;
				// $param['PRODUCT_CATEGORY'] = $product_category;
				// $param['COUNTRY'] = PLATFORMCOUNTRYID;
				// $param['CURRENCY'] = $data["so"]->get_currency_id();

				$this->adroll_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
				$data['tracking_script'] .= $this->adroll_tracking_script_service->get_variable_code("payment_success", $param);
			}

# 			SBF #2284 Tradedoubler variable js portion; only payment success page
			$this->tradedoubler_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
			$param_list = array();
			foreach($data["so_items"] as $key=>$soi_obj)
			{
				$param_list["id"]    = $soi_obj->get_prod_sku();
				$param_list["price"] = $soi_obj->get_unit_price();
				$param_list["currency"] = $data["so"]->get_currency_id();
				$param_list["name"]  = $soi_obj->get_name();
				$param_list["qty"]   = $soi_obj->get_qty();
				$product_list[]      = $param_list;
			}
			$param["order_id"] = $so_no;
			$param["order_value"] = $total_cart_price;
			$param["currency"] = $data["so"]->get_currency_id();

			$td_variable_code = $this->tradedoubler_tracking_script_service->get_variable_code("payment_success", $product_list, $param);
			$this->template->add_js($td_variable_code, "print");

#			SBF #2284 Tradedoubler FR pixel tag, SBF #2382 Tradedoubler ES , SBF #2645 Tradedoubler BE
			{
				$tduid = "";
				if (!empty($_SESSION["TRADEDOUBLER"]))
					{$tduid = $_SESSION["TRADEDOUBLER"];}
				$reportInfo = "";
				$reportInfo = urlencode($reportInfo);
				if (!empty($_COOKIE["TRADEDOUBLER"]))
					{$tduid = $_COOKIE["TRADEDOUBLER"];}
				switch (PLATFORMCOUNTRYID)
				{
					case "FR":
						$tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
						$this->template->add_js($tradedoubler_pixel_script, "print");
						break;

					case "ES";
						$tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
						$this->template->add_js($tradedoubler_pixel_script, "print");
						break;

					case "BE";
						$tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
						$this->template->add_js($tradedoubler_pixel_script, "print");
						break;

					default:
						break;
				}

				if (PLATFORMCOUNTRYID == "FR")
					{

					}
			}

			# SBF#2208 - www.shopperapproved.com
			$shopperapproved = true;
			if ($shopperapproved)
			{
				switch (PLATFORMCOUNTRYID)
				{
					case "GB":
					case "US":
					// case "AU":
					case "HK":
					case "IE":
					case "SG":
					case "MY":
					case "NZ":
						$add = true;
						break;

					#SBF2243
					case "BE":
					case "FR":
					case "ES":
						// BE FR n ES removed by SBF2274
	 					// $add = true;
	 					// break;
					default: $add = false;
	 					break;
				}

				if ($add)
					$data['tracking_script'] .= <<<shopperapproved
				<script type="text/javascript" src="https://www.shopperapproved.com/thankyou/sv-draw_js.php?site=6801"></script>
				<script src="https://www.shopperapproved.com/thankyou/opt-in.js" type="text/javascript"></script>
shopperapproved;
			}

			# SBF#5476 - ResellerRatings
			$resellerratings = true;
			if ($resellerratings)
			{
				switch (PLATFORMCOUNTRYID)
				{
					case "AU":
						$add = true;
						$sellerid = "48341";
						break;

					default: $add = false;
	 					break;
				}

				if ($add)
					$data['tracking_script'] .= <<<resellerratings

						<script type="text/javascript">
						var _rrES = {
						    seller_id: $sellerid,
						    email: "{$data["tracking_data"]["client_email"]}",
						    invoice: "$so_no"};
						(function() {
						    var s=document.createElement('script');s.type='text/javascript';s.async=true;
						    s.src="https://www.resellerratings.com/popup/include/popup.js";var ss=document.getElementsByTagName('script')[0];
						    ss.parentNode.insertBefore(s,ss);
						})();
						</script>
resellerratings;
			}

			# SBF#1942
			$shopzilla_fr = true;
			if ($shopzilla_fr)
			{
				$is_new_customer = 1; # new customer
				$is_new_customer = 0; # old customer

				$data['tracking_script'] .= <<<shopzilla_fr
					<script language="javascript">
					<!--
						/* shopzilla_fr Performance Tracking Data */
						var mid            = '271185';
						var cust_type      = '$customer_status';
						var order_value    = '$total_cart_price';
						var order_id       = '$so_no';
						var units_ordered  = '$total_item';
					//-->
					</script>
					<script language="javascript" src="https://www.shopzilla.com/css/roi_tracker.js"></script>
shopzilla_fr;
			}

			$become_eu = true;
			if ($become_eu)
			{
				$product_id		= trim($product_id, ",");
				$product_name	= trim($product_name, ",");
				$product_price	= trim($product_price, ",");
				$product_units	= trim($product_units, ",");

				$currency = $data["so"]->get_currency_id();
				$pangora_merchant_id = "59474";

				$data['tracking_script'] .= <<<become_eu
					<!-- Become Sales Tracking Script V 1.0.0 - All rights reserved -->
					<script language="JavaScript">
					var pg_pangora_merchant_id='$pangora_merchant_id';
					var pg_order_id='$so_no';
					var pg_cart_size=' $total_item';
					var pg_cart_value=' $total_cart_price';
					var pg_currency='$currency';
					var pg_customer_flag=' $is_new_customer';
					var pg_product_id=' $product_id';
					var pg_product_name=' $product_name';
					var pg_product_price=' $product_price';
					var pg_product_units=' $product_units';
					</script>
					<script language="JavaScript" src="https://clicks.pangora.com/
					sales-tracking/salesTracker.js"></script>
					<noscript><img src="https://clicks.pangora.com/
					sales-tracking/$pangora_merchant_id/salesPixel.do" /></noscript>
become_eu;
			}

			# SBF#1972
			$shopping_com = false;
			if ($shopping_com)
			{
				$data['tracking_script'] .= <<<shopping_com_part1
					<script type="text/javascript">
					// shopping_com
					var _roi = _roi || [];

					_roi.push(['_setMerchantId', 	'513170']); // required
					_roi.push(['_setOrderId', 		'$so_no']); // unique customer order ID
					_roi.push(['_setOrderAmount', 	'$total_cart_price']); // order total without tax and shipping
					_roi.push(['_setOrderNotes', 	'']); // notes on order, up to 50 characters
shopping_com_part1;

				foreach($data["so_items"] as $key=>$soi_obj)
				{
					$data['tracking_script'] .= <<<shopping_com_part2
						_roi.push(['_addItem',
						'{$soi_obj->get_prod_sku()}', 		// (Merchant sku)
						'{$soi_obj->get_name()}', 			// (Product name)
						'{$data["skuinfo"]["cat_name"]}',	// (Category id)
						'{$data["skuinfo"]["cat_name"]}', 	// (Category name)
						'{$soi_obj->get_unit_price()}', 	// (Unit price)
						'{$soi_obj->get_qty()}' 			// (Item quantity)
						]);
shopping_com_part2;
				}

				$data['tracking_script'] .= <<<shopping_com_part3
				_roi.push(['_trackTrans']);
				</script>
				<script type="text/javascript" src="https://stat.dealtime.com/ROI/ROI2.js"></script>
shopping_com_part3;
			}

			//criteo script
			$enable_mediaforge_country = array('GB', 'AU', 'FR', 'ES');
			if(in_array(PLATFORMCOUNTRYID, $enable_mediaforge_country))
			{
#				mediaforge - added by SBF#1902
				$enable_mediaforge = true;
				if ($enable_mediaforge)
				{
					if (PLATFORMCOUNTRYID == 'GB') $account_no = 1038;
					if (PLATFORMCOUNTRYID == 'AU') $account_no = 1059;
					if (PLATFORMCOUNTRYID == 'FR') $account_no = 1411; #SBF#2229
					if (PLATFORMCOUNTRYID == 'ES') $account_no = 1519; #SBF#2404
#					function add_js($script, $type = 'import', $defer = FALSE, $position = "header")
					$this->template->add_js("//tags.mediaforge.com/js/$account_no?orderNumber=$so_no&price=$total_cart_price", "import", FALSE, "body");
				}

#				criteo - removed by SBF#1902
				$enable_criteo = false;
				if ($enable_criteo)
				{
					if($data['is_http'])
					{
						$this->template->add_js("http://static.criteo.net/criteo_ld3.js");
					}
					else
					{
						$this->template->add_js("https://static.criteo.net/criteo_ld3.js");
					}
					foreach($data["so_items"] as $key=>$soi_obj)
					{
						if($key < 2)
						{
							$i = $key + 1;
							$criteo_tag .= '&i'.$i.'='.$soi_obj->get_prod_sku().'&p'.$i.'='.$soi_obj->get_unit_price().'&q'.$i.'='.$soi_obj->get_qty();
						}
					}
					$criteo_script =
					'
						document.write(\'<div id=\"cto_tr_7719984_ac\" style=\"display:none\">\');
						document.write(\'<div class=\"ctoWidgetServer\">https:\/\/sslwidget.criteo.com\/pvx\/<\/div>\');
						document.write(\'<div class=\"ctoDataType\">transaction<\/div>\');
						document.write(\'<div class=\"ctoParams\">wi=7719984&t='.$so_no.'&s=1'.$criteo_tag.'<\/div>\');
						document.write(\'<\/div>\');
					';
					$this->template->add_js($criteo_script, 'embed');
				}
			}
		}

		// meta tag
		$data['lang_text'] = $this->_get_language_file('', 'checkout', 'payment_result');
		if($success)
		{
			$this->template->add_title($data['lang_text']['payment_accepted']);
			$data['lang_text']['header'] = $data['lang_text']['payment_success'];
		}
		else
		{
			$this->template->add_title($data['lang_text']['payment_failure']);
			$data['lang_text']['header'] = $data['lang_text']['payment_unsuccess'];
		}
		if (PLATFORMCOUNTRYID != "ES")
			$data['display_cs_phone_no'] = true;
		else
			$data['display_cs_phone_no'] = false;
		$this->template->add_meta(array('name'=>'description','content'=>$data['lang_text']['meta_desc']));
		$this->template->add_meta(array('name'=>'keywords', 'content'=>$data['lang_text']['meta_keyword']));
		$this->affiliate_service->remove_af_record();
		$this->load_tpl('content', 'checkout/payment_result', $data, TRUE, FALSE);
	}

	public function is_allowed_postal($country_code, $postal_code)
	{
		$output = "0";	# allowed postal code

		$proceed = $this->postal_validator->is_valid
		(
			array
			(
  				"LangCountryPair" 		=> $country_code,
        		"PostalCode" 			=> $postal_code,
			)
		);

		if ($proceed)
		{
			if (!$this->country_service->is_allowed_postal($country_code, $postal_code))
				$output = "1";	# blocked postal code
		}
		else
			$output = "2";	# invalid postal code

		echo $output;
	}

	public function order_confirm($pmgw, $debug=0)
	{
		# for tracking pixels
		$skuinfo[0]["brand"] = "";
		$skuinfo[0]["cat_name"] = "";
		$skuinfo[0]["sc_name"] = "";

		if ($pmgw == "paypal")
		{
			$vars["so_no"] = $this->input->get("so_no");

			$vars["token"] = $this->input->get("token");
			$vars["PayerID"] = $this->input->get("PayerID");
			$vars["confirm"] = 1;
			$data = $this->checkout_model->payment_gateway_service->response($pmgw, $vars, $debug);

			$data["so_no"] = $vars["so_no"];
			$data["token"] = $vars["token"];
			$data["PayerID"] = $vars["PayerID"];
			$data["debug"] = $debug;

			$data["delivery_country"] = $this->region_service->country_dao->get(array("id"=>$data["so"]->get_delivery_country_id()));
			$data["courier"] = $this->so_service->get_pbv_srv()->get_dt_dao()->get(array("id"=>$data["so"]->get_delivery_type_id()));
			$data["so_items"] = $this->so_service->get_soi_dao()->get_items_w_name(array("so_no"=>$vars["so_no"]), array("lang_id" => get_lang_id()));
//			var_dump($data["so_items"]);
			$data["client"] = $this->client_service->get(array("id"=>$data["so"]->get_client_id()));

			// Disable LP
			$data['no_lp'] = 1;

			$data["skuinfo"] = $this->get_skuinfo($vars["so_no"]);
			#$data["skuinfo"] = $this->get_sku_info("133586"); var_dump($data["skuinfo"]);	die();

			$data['lang_text'] = $this->_get_language_file('', 'checkout', 'order_confirm');
			$this->load_view('checkout/order_confirm_paypal', $data);
		}
	}
}

/* End of file checkout.php */
/* Location: ./app/public_controllers/checkout.php */