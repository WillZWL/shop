<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_voucher.php";

class Pmgw_google_service extends Pmgw_voucher
{
	private $wc_srv;
	private $pc_dao;
	private $payment_info;
	private $checkout_url;
	private $website_url;
	private $response_url;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Weight_cat_service.php");
		$this->set_wc_srv(new Weight_cat_service());
		include_once(APPPATH."libraries/dao/Platform_courier_dao.php");
		$this->set_pc_dao(new Platform_courier_dao());
	}

	public function init($vars)
	{
		$pbv_srv = $this->get_pbv_srv();
		$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$vars["platform_id"]));
		$vars["currency_id"] = $platform_obj->get_platform_currency_id();
		$so_srv = $this->get_so_srv();
		$so_obj = $so_srv->cart_to_so($vars);
		if ($so_obj === FALSE)
		{
			return FALSE;
		}
		$this->so = $so_obj;
		$_SESSION["so_no"] = $this->so->get_so_no();
		$this->payment_info["so_no"] = $this->so->get_so_no();
		$this->payment_info["curr"] = $this->so->get_currency_id();
		$this->set_checkout_url($vars["checkout_url"]);
		$this->set_website_url($vars["website_url"]);
		$this->set_response_url($vars["response_url"]);
		$this->payment_info["promo"] = $vars["promo"];
	}

	public function checkout($debug=0)
	{
		if ($debug && !$this->get_config()->value_of("payment_debug_allow"))
		{
			$debug = 0;
		}

		include_once(APPPATH.'libraries/service/Googlecheckout/googlecart.php');
		include_once(APPPATH.'libraries/service/Googlecheckout/googleitem.php');
		include_once(APPPATH.'libraries/service/Googlecheckout/googleshipping.php');
		include_once(APPPATH.'libraries/service/Googlecheckout/googletax.php');
		include_once(BASEPATH."libraries/Encrypt.php");
		$encrypt = new CI_Encrypt();
		$http = $this->get_http();
		$http_obj = $this->get_hi_dao()->get(array("name"=>$debug?"GOOGLE_PG_TEST":"GOOGLE_PG"));
		$srv_type = ($debug)?"sandbox":"merchant";
		$cart = new GoogleCart($http_obj->get_username(), $encrypt->decode($http_obj->get_password()), $srv_type, $this->payment_info["curr"]);

		$origin_website = isset($_COOKIE['originw'])?$_COOKIE['originw']:($_COOKIE["LS_siteID"] != ''?13:11);
		$cart->SetMerchantPrivateData(new MerchantPrivateData(array(
																	"so_no" => $this->payment_info["so_no"],
																	"origin_website"=>$origin_website,
																	"ls_siteid"=>$_COOKIE["LS_siteID"],
																	"ls_timeentered"=>$_COOKIE["LS_timeEntered"]
																	)));

		$total_count = 1;
		$so_srv = $this->get_so_srv();
		$pbv_srv = $this->get_pbv_srv();
		$soi_list = $so_srv->get_soi_dao()->get_items_w_name(array("so_no"=>$this->payment_info["so_no"]), array("orderby"=>"line_no"));
		$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$this->so->get_platform_id()));

		$totalweight = 0;
		$vat_percent = $platform_obj->get_vat_percent()/100;

		foreach($soi_list as $soi_obj)
		{
			$item = new GoogleItem($soi_obj->get_name(), "", ($cur_qty=$soi_obj->get_qty()), $soi_obj->get_unit_price()/(1+$vat_percent));
			$item->SetMerchantItemId($soi_obj->get_prod_sku());
			$cart->AddItem($item);
			$totalweight += $cur_qty*$soi_obj->get_item_weight();
		}

//		if ($this->payment_info["promo"]["valid"] && ($this->payment_info["promo"]["promotion_code_obj"]->get_disc_type == "A" || $this->payment_info["promo"]["promotion_code_obj"]->get_disc_type == "P"))
		if ($this->payment_info["promo"]["valid"] && !$this->payment_info["promo"]["error"])
		{
			$item = new GoogleItem($this->payment_info["promo"]["promotion_code_obj"]->get_code()." - ".$this->payment_info["promo"]["promotion_code_obj"]->get_description(), "", "1" , ($this->payment_info["promo"]["disc_amount"]*-1)/(1+$vat_percent));
			$item->SetMerchantItemId($this->payment_info["promo"]["promotion_code_obj"]->get_code());
			$cart->AddItem($item);
		}
		$shiptax = "true";

		$country_charge_list = $this->get_wc_srv()->get_wcc_dao()->get_country_weight_charge($this->so->get_platform_id(), $this->so->get_weight());
		$cart->SetMerchantCalculations($this->get_response_url());

		if ($country_charge_list)
		{
			$i=0;
			foreach ($country_charge_list as $cc_obj)
			{
				$country_id = $cc_obj->get_country_id();

				if ($country_id == 'GB')
				{
					$tax_rule = new GoogleDefaultTaxRule("0", "false");
					$tax_rule->AddPostalArea($country_id, "JE*");
					$cart->AddDefaultTaxRules($tax_rule);
					$tax_rule = new GoogleDefaultTaxRule("0", "false");
					$tax_rule->AddPostalArea($country_id, "GY*");
					$cart->AddDefaultTaxRules($tax_rule);
				}

				$tax_rule = new GoogleDefaultTaxRule($vat_percent, $shiptax);
				$tax_rule->AddPostalArea($country_id);
				$cart->AddDefaultTaxRules($tax_rule);

				$deliverystyle = $country_id." ".$cc_obj->get_display_name();
				$shipping = new GoogleMerchantCalculatedShipping($deliverystyle, (($this->so->get_courier_id() == $cc_obj->get_courier_id() && $this->so->get_delivery_charge() == 0)?0:$cc_obj->get_amount())/(1+$vat_percent));

				$shipfilter = new GoogleShippingFilters();
				$shipfilter->AddAllowedPostalArea($country_id);
				$shipping->AddAddressFilters($shipfilter);

				$shiprestrict = new GoogleShippingFilters();
				$shiprestrict->AddAllowedPostalArea($country_id);
				$shipping->AddShippingRestrictions($shiprestrict);
				$cart->AddShipping($shipping);
				$i++;
			}
		}
		if ($i==0)
		{
			$pbv_srv = $this->get_pbv_srv();
			$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$this->so->get_platform_id()));

			$default_courier = $platform_obj->get_delivery_type();
			$courier_name = $pbv_srv->get_pc_dao()->get(array("platform_id"=>$this->so->get_platform_id(), "courier_id"=>$default_courier))->get_display_name();

			$country_id = "GB";

			$tax_rule = new GoogleDefaultTaxRule($vat_percent, $shiptax);
			$tax_rule->AddPostalArea($country_id);
			$cart->AddDefaultTaxRules($tax_rule);

			$deliverystyle = $country_id." ".$courier_name;
			$shipping = new GoogleMerchantCalculatedShipping($deliverystyle, 0);

			$shipfilter = new GoogleShippingFilters();
			$shipfilter->AddAllowedPostalArea($country_id);
			$shipping->AddAddressFilters($shipfilter);

			$shiprestrict = new GoogleShippingFilters();
			$shiprestrict->AddAllowedPostalArea($country_id);
			$shipping->AddShippingRestrictions($shiprestrict);
			$cart->AddShipping($shipping);
		}
//		$this->logheader["message"] = var_export($cart->GetXML(), true);
//		$this->logger->write_log($this->logheader);

		$cart->SetEditCartUrl($this->get_checkout_url());
		$cart->SetContinueShoppingUrl($this->get_website_url());
		$cart->SetRequestBuyerPhone(true);
		list($status, $error) = $cart->CheckoutServer2Server();
		echo "An error had ocurred: <br />HTTP Status: " . $status. ":";
		echo "<br />Error message:<br />";
		echo $error;
	}

	public function response($vars, $debug)
	{
		include_once(APPPATH.'libraries/service/Googlecheckout/googleresponse.php');
		include_once(APPPATH.'libraries/service/Googlecheckout/googlemerchantcalculations.php');
		include_once(APPPATH.'libraries/service/Googlecheckout/googleresult.php');
		include_once(APPPATH.'libraries/service/Googlecheckout/googlerequest.php');
		define('RESPONSE_HANDLER_ERROR_LOG_FILE', (rtrim($this->get_config()->value_of("data_path"), '/')).'/google_log/googleerror_'.date("Y-m-d").'.log');
		define('RESPONSE_HANDLER_LOG_FILE', (rtrim($this->get_config()->value_of("data_path"), '/')).'/google_log/googlemessage_'.date("Y-m-d").'.log');
		include_once(BASEPATH."libraries/Encrypt.php");
		$encrypt = new CI_Encrypt();
		$http = $this->get_http();
		$http_obj = $this->get_hi_dao()->get(array("name"=>$debug?"GOOGLE_PG_TEST":"GOOGLE_PG"));
		$merchant_id = $http_obj->get_username();
		$merchant_key = $encrypt->decode($http_obj->get_password());
		$server_type = ($debug)?"sandbox":"merchant";
		$currency = 'GBP';

		$Gresponse = new GoogleResponse($merchant_id, $merchant_key);
		$Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);
		$Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE, RESPONSE_HANDLER_LOG_FILE, L_ALL);
		$xml_response = isset($HTTP_RAW_POST_DATA)? $HTTP_RAW_POST_DATA:file_get_contents("php://input");
		list($root, $data) = $Gresponse->GetParsedXML($xml_response);
//		mail("tommy@econsultancygroup.net", $root, var_export($data, true));
		$Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);
//		$this->logheader["message"] = var_export($data, true);
//		$this->logger->write_log($this->logheader);

		switch ($root)
		{
			case "request-received":
			{
				break;
			}
			case "error":
			{
				break;
			}
			case "diagnosis":
			{
				break;
			}
			case "checkout-redirect":
			{
				break;
			}
			case "merchant-calculation-callback":
			{
				// Create the results and send it
				$merchant_calc = new GoogleMerchantCalculations($currency);
				$Gresponse->ProcessMerchantCalculations($merchant_calc);
				break;
			}
			case "new-order-notification":
			{
				$so_no = $data[$root]['shopping-cart']['merchant-private-data']['so_no']['VALUE'];
				$origin_website = $data[$root]['shopping-cart']['merchant-private-data']['origin_website']['VALUE'];
				$ls_siteid = $data[$root]['shopping-cart']['merchant-private-data']['ls_siteid']['VALUE'];
				$ls_timeentered = $data[$root]['shopping-cart']['merchant-private-data']['ls_timeentered']['VALUE'];

				$this->so = $this->get_so_srv()->get_dao()->get(array("so_no"=>$so_no));

				$this->so->set_amount($data[$root]['order-total']['VALUE']);
//				mail("tommy@econsultancygroup.net", $root, var_export($this->so, true));
				$del_charge = $data[$root]['order-adjustment']['shipping']['merchant-calculated-shipping-adjustment']['shipping-cost']['VALUE'] * ($this->so->get_vat_percent()/100+1);
				$this->so->set_delivery_charge(round($del_charge, 2));
				$ar_name = explode(" ", $data[$root]['order-adjustment']['shipping']['merchant-calculated-shipping-adjustment']['shipping-name']['VALUE']);
				unset($ar_name[0]);
				$display_name = implode(" ", $ar_name);

				$pc_obj= $this->get_pc_dao()->get(array("platform_id"=>$this->so->get_platform_id(), "display_name"=>$display_name));

				$this->so->set_courier_id($pc_obj->get_courier_id());
				$this->so->set_txn_id($data[$root]['google-order-number']['VALUE']);

				$client["email"] = $data[$root]['buyer-shipping-address']['email']['VALUE'];
				$client["del_name"] = $data[$root]['buyer-shipping-address']['structured-name']['first-name']['VALUE'] . " " . $data[$root]['buyer-shipping-address']['structured-name']['last-name']['VALUE'];
				$so["delivery_company"] = $client["del_company"] = $data[$root]['buyer-shipping-address']['company-name']['VALUE'];
				$so["delivery_name"] = $data[$root]['buyer-shipping-address']['contact-name']['VALUE'];
				$client["mobile"] = $data[$root]['buyer-shipping-address']['phone']['VALUE'];
				$client["del_address_1"] = $data[$root]['buyer-shipping-address']['address1']['VALUE'];
				$client["del_address_2"] = $data[$root]['buyer-shipping-address']['address2']['VALUE'];
				$so["delivery_address"] = trim($client["del_address_1"]."|".$client["del_address_2"]);
				$scc = $data[$root]['buyer-shipping-address']['country-code']['VALUE'];
/*				if ($scc == 'GB')
				{
					$scc = 'UK';
				}*/
				$so["delivery_country_id"] = $client["del_country_id"] = $scc;
				$so["delivery_city"] = $client["del_city"] = $data[$root]['buyer-shipping-address']['city']['VALUE'];
				$so["delivery_state"] = $client["del_state"] = $data[$root]['buyer-shipping-address']['region']['VALUE'];
				$so["delivery_postcode"] = $client["del_postcode"] = $data[$root]['buyer-shipping-address']['postal-code']['VALUE'];

				$so["bill_company"] = $data[$root]['buyer-billing-address']['company-name']['VALUE'];
				$so["bill_name"] = $data[$root]['buyer-billing-address']['contact-name']['VALUE'];
				$so["bill_phone"] = $data[$root]['buyer-billing-address']['phone']['VALUE'];
				$so["bill_address"] = trim($data[$root]['buyer-billing-address']['address1']['VALUE']."|".$data[$root]['buyer-billing-address']['address2']['VALUE']);
				$bcc = $data[$root]['buyer-billing-address']['country-code']['VALUE'];
/*				if ($bcc == 'GB')
				{
					$bcc = 'UK';
				}*/
				$so["bill_country_id"] = $bcc;
				$so["bill_city"] = $data[$root]['buyer-billing-address']['city']['VALUE'];
				$so["bill_state"] = $data[$root]['buyer-billing-address']['region']['VALUE'];
				$so["bill_postcode"] = $data[$root]['buyer-billing-address']['postal-code']['VALUE'];

				include_once(APPPATH."helpers/object_helper.php");
				$client_dao = $this->get_client_dao();
				include_once(BASEPATH."libraries/Encrypt.php");
				$encrypt = new CI_Encrypt();

				if ($client_obj = $client_dao->get(array("email"=>$client["email"])))
				{
					set_value($client_obj, $client);
					$client_dao->update($client_obj);
				}
				else
				{
					$client_obj = $client_dao->get();
					$client["forename"] = $data[$root]['buyer-shipping-address']['structured-name']['first-name']['VALUE'];
					$client["surname"] = $data[$root]['buyer-shipping-address']['structured-name']['last-name']['VALUE'];
					if (empty($client["forename"]))
					{
						$client["forename"] = $so["delivery_name"];
					}
					$client["company_name"] = $client["del_company"];
					$client["address_1"] = $client["del_address_1"];
					$client["address_2"] = $client["del_address_2"];
					$client["postcode"] = $client["del_postcode"];
					$client["city"] = $client["del_city"];
					$client["state"] = $client["del_state"];
					$client["country_id"] = $client["del_country_id"];
					set_value($client_obj, $client);
//					$client_obj->set_platform_id($this->so->get_platform_id());
					$client_obj->set_password($encrypt->encode(mktime()));
					$client_obj->set_subscriber(0);
					$client_obj->set_party_subscriber(0);
					$client_obj->set_status(1);
					$client_dao->insert($client_obj);
				}
				$this->so->set_client_id($client_obj->get_id());
				set_value($this->so, $so);
				$this->get_so_srv()->get_dao()->update($this->so);
				$ps_obj = $this->get_so_srv()->get_sops_dao()->get(array("so_no"=>$so_no));
				$ps_obj->set_payment_status('P');
				$this->get_so_srv()->get_sops_dao()->update($ps_obj);

				// Tracking
				$soext_vo = $this->get_so_srv()->get_soext_dao()->get();
				$soext_vo->set_so_no($this->so->get_so_no());
				$soext_vo->set_conv_site_id($origin_website);
				$soext_vo->set_conv_status(0);

				if($ls_siteid != '' && $ls_siteid !='siteID')
				{
					$soext_vo->set_conv_site_ref($ls_siteid);
					$soext_vo->set_ls_time_entered($ls_timeentered);
				}

				$this->get_so_srv()->get_soext_dao()->insert($soext_vo);

				$Gresponse->SendAck();
				break;
			}
			case "order-state-change-notification":
			{
				$new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
				$new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];

				switch($new_financial_state)
				{
					case 'REVIEWING':
					{
						break;
					}
					case 'CHARGEABLE':
					{
						//$Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
						//$Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
						break;
					}
					case 'CHARGING':
					{
						break;
					}
					case 'CHARGED':
					{
						$this->so = $this->get_so_srv()->get_dao()->get_so_w_pmgw(array("txn_id"=>$data[$root]['google-order-number']['VALUE'], "payment_gateway_id"=>"google"), array("limit"=>1));
						if ($this->so->get_status() == 1)
						{
							$pbv_srv = $this->get_pbv_srv();
							$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$this->so->get_platform_id()));

							$this->so->set_expect_delivery_date(date("Y-m-d H:i:s", time()+$platform_obj->get_latency_in_stock()*86400));
							$this->so->set_status(3);

							$this->get_so_srv()->get_dao()->update($this->so);
							$ps_obj = $this->get_so_srv()->get_sops_dao()->get(array("so_no"=>$this->so->get_so_no()));
							$ps_obj->set_payment_status('S');
							$ps_obj->set_pay_date(date("Y-m-d H:i:s"));
							$this->get_so_srv()->get_sops_dao()->update($ps_obj);

							$so_srv=$this->get_so_srv();

							// Tracking
							$soext_obj = $so_srv->get_soext_dao()->get(array("so_no"=>$this->so->get_so_no()));
							$soext_obj->set_conv_status(1);
							$so_srv->get_soext_dao()->update($soext_obj);

							if($soext_obj->get_conv_site_ref() != '' && $soext_obj->get_conv_site_ref() !='siteID')
							{
								// Insert ls_transaction
								$this->add_ls_transaction($soext_obj);
							}

							if ($promo_code = $this->so->get_promotion_code())
							{
								$this->update_promo($promo_code);
							}

							// Fire Event START
							$this->fire_success_event();
							// Fire Event END
						}

						break;
					}
					case 'PAYMENT_DECLINED':
					{
						break;
					}
					case 'CANCELLED':
					{
						break;
					}
					case 'CANCELLED_BY_GOOGLE':
					{
						//$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
						//    "Sorry, your order is cancelled by Google", true);
						break;
					}
					default:
						break;
					$Gresponse->SendAck();
				}
				switch($new_fulfillment_order)
				{
					case 'NEW':
					{
						break;
					}
						case 'PROCESSING':
					{
						break;
					}
					case 'DELIVERED':
					{
						break;
					}
					case 'WILL_NOT_DELIVER':
					{
						break;
					}
				default:
					break;
				}
				break;
			}
			case "charge-amount-notification":
			{
				//$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
				//    <carrier>, <tracking-number>, <send-email>);
				//$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
				$Gresponse->SendAck();
				break;
			}
			case "chargeback-amount-notification":
			{
				$Gresponse->SendAck();
				break;
			}
			case "refund-amount-notification":
			{
				$Gresponse->SendAck();
				break;
			}
			case "risk-information-notification":
			{

				//RiskScoreChecker($root,$data);
				$Gresponse->SendAck();
				break;
			}
			default:
				$Gresponse->SendBadRequestStatus("Invalid or not supported Message");
				break;
		}

	}

	public function get_wc_srv()
	{
		return $this->wc_srv;
	}

	public function set_wc_srv($value)
	{
		$this->wc_srv = $value;
	}

	public function get_checkout_url()
	{
		return $this->checkout_url;
	}

	public function set_checkout_url($value)
	{
		$this->checkout_url = $value;
	}

	public function get_website_url()
	{
		return $this->website_url;
	}

	public function set_website_url($value)
	{
		$this->website_url = $value;
	}

	public function get_response_url()
	{
		return $this->response_url;
	}

	public function set_response_url($value)
	{
		$this->response_url = $value;
	}

	public function get_pc_dao()
	{
		return $this->pc_dao;
	}

	public function set_pc_dao($value)
	{
		$this->pc_dao = $value;
	}
}

/* End of file pmgw_google_service.php */
/* Location: ./system/application/libraries/service/Pmgw_google_service.php */