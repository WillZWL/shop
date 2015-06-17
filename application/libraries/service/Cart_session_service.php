<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Cart_session_service extends Base_service
{
	const ALLOW_AND_IS_NORMAL_ITEM = 1;
	const ALLOW_AND_IS_PREORDER = 10;
	const ALLOW_AND_IS_ARRIVING = 20;
	const SAME_PREORDER_ITEM = 30;
	const SAME_ARRIVING_ITEM = 35;
	const SAME_NORMAL_ITEM = 40;
	const DECISION_POINT = 50;
	const NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM = 60;
	const NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM = 70;
	const DIFFERENT_PREORDER_ITEM = 80;
	const DIFFERENT_ARRIVING_ITEM = 85;
	const UNKNOWN_ITEM_STATUS = 100;

	private $delivery_charge = 0;
	private $del_country_id = "";
	private $del_state = "";
	private $del_postcode = "";
	private $email = NULL;
	private $delivery_mode = NULL;
	protected $exchange_rate_service;

	public function __construct()
	{
		include_once "Weight_cat_service.php";
		$this->wc_svc = new Weight_cat_service();

		include_once "Product_service.php";
		$this->prod_svc = new Product_service();

		include_once "Promotion_code_service.php";
		$this->promo_cd_svc = new Promotion_code_service();

		include_once "Delivery_service.php";
		$this->del_svc = new Delivery_service();

		include_once "Currency_service.php";
		$this->curr_svc = new Currency_service();

		include_once(APPPATH."helpers/price_helper.php");

		include_once(APPPATH . "libraries/service/Exchange_rate_service.php");
		$this->set_exchange_rate_service(new Exchange_rate_service());
	}

	public function add($sku, $qty, $platform = NULL)
	{
		if (is_null($platform)) {
			$platform = defined(PLATFORMID) ? PLATFORMID : "WSUS";
		}

		$qty = (int)$qty;

		if ($qty <= 0) {
			$this->remove($sku, $platform);
		} elseif ($prod_list = $this->prod_svc->get_dao()->get_bundle_components_overview(array("vpi.prod_sku"=>$sku, "vpo.platform_id"=>$platform, "p.status"=>2, "p.website_status <>"=>'O'))) {
			$success = 1;
			foreach ($prod_list as $prod) {
				$ws_qty = $prod->get_website_quantity();
				$ws_status = $prod->get_website_status();
				$status = $prod->get_prod_status();
				$listing_status = $prod->get_listing_status();
				$price = $prod->get_price();
				$expect_delivery_date = $prod->get_expected_delivery_date();
				$warranty_in_month = $prod->get_warranty_in_month();

				if (!($ws_qty != "O" && $status == '2' && $listing_status == 'L' && $ws_qty > 0 && ($price * 1 > 0 || $this->prod_svc->is_trial_software($prod->get_sku())))) {
					$this->remove($sku, $platform);
					$success = 0;
					break;
				} elseif (!isset($max_qty) || $ws_qty < $max_qty) {
					$max_qty = $ws_qty;
				}
			}

			if ($success) {
				$this->put_cart_sku($platform, $sku, $qty > $max_qty?$max_qty:$qty, $ws_status, $expect_delivery_date, $warranty_in_month);
				$this->set_cart_cookie($platform);

				return TRUE;
			}
		}
		return FALSE;
	}

	public function put_cart_sku($platform, $sku, $qty, $website_status = null, $expect_delivery_date, $warranty_in_month)
	{
		$_SESSION["cart"][$platform][$sku] = $this->cart_format($platform, $sku, $qty, $website_status, $expect_delivery_date, $warranty_in_month);
		// var_dump($_SESSION["cart"][$platform]);
	}

	public function cart_format($platform, $sku, $qty, $website_status = null, $expect_delivery_date, $warranty_in_month)
	{
		return array("qty" => $qty,
					"website_status" => $website_status,
					"expect_delivery_date" => $expect_delivery_date,
					"warranty_in_month" => $warranty_in_month);
	}

	public function add_special($sku, $qty, $price="", $platform = NULL)
	{
		if (is_null($platform))
		{
			$platform = defined(PLATFORMID)?PLATFORMID:"WSUS";
		}

		$prod = $this->prod_svc->get_dao()->get(array("sku"=>$sku));

		if ($prod)
		{
			$_SESSION["cart"][$platform][$sku] = array("qty"=>$qty,"price"=>$price);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function modify($sku, $qty, $platform = NULL)
	{

		if (is_null($platform))
		{
			$platform = defined(PLATFORMID)?PLATFORMID:"WSUS";
		}

		if (is_numeric($qty))
		{
			$qty = $qty*1;

			if ($qty <= 0)
			{
				$this->remove($sku, $platform);
			}
			elseif ($prod_list = $this->prod_svc->get_dao()->get_bundle_components_overview(array("vpi.prod_sku"=>$sku, "vpo.platform_id"=>$platform, "p.status"=>2, "p.website_status <>"=>'O')))
			{
				$success = 1;
				foreach ($prod_list as $prod)
				{
					$ws_qty = $prod->get_website_quantity();
					$ws_status = $prod->get_website_status();
					$status = $prod->get_prod_status();
					$listing_status = $prod->get_listing_status();
					$price = $prod->get_price();
					$expect_delivery_date = $prod->get_expected_delivery_date();
					$warranty_in_month = $prod->get_warranty_in_month();

					if (!($ws_qty != "O" && $status == '2' && $listing_status == 'L' && $ws_qty > 0 && ($price*1 > 0 || $this->prod_svc->is_trial_software($prod->get_sku())))){
						$this->remove($sku, $platform);
						$success = 0;
						break;
					}
					elseif (!isset($max_qty) || $ws_qty < $max_qty)
					{
						$max_qty = $ws_qty;
					}
				}

				if ($success)
				{
					$this->put_cart_sku($platform, $sku, $qty > $max_qty?$max_qty:$qty, $ws_status, $expect_delivery_date, $warranty_in_month);
				}
			}
			else
			{
				$this->remove($sku, $platform);
			}
			$this->set_cart_cookie($platform);
		}
	}

	public function modify_special($sku, $qty, $price = NULL, $platform = NULL)
	{

		if (is_null($platform))
		{
			$platform = defined(PLATFORMID)?PLATFORMID:"WSUS";
		}

		$prod = $this->prod_svc->get_dao()->get(array("sku"=>$sku));

		if ($prod)
		{
			if ($qty == 0)
			{
				$this->remove($sku, $platform);
			}
			else
			{
				if (is_null($price))
				{
					$_SESSION["cart"][$platform][$sku]["qty"] = $qty;
				}
				else
				{
					$_SESSION["cart"][$platform][$sku] = array("qty"=>$qty,"price"=>$price);
				}
			}
		}
	}
/**********************************************
**  check_battery_inside_cart_valid_or_not
**  return false if it meet the requirement of SBF#4480
**  return the minimum order amount, e.g. 100USD
***********************************************/
	public function check_battery_inside_cart_valid_or_not($total_amount, $platform = NULL)
	{
        $reference_amount = 100;			//use only when there is no situable currency in the reference_value
        $reference_currency = "USD";		//use only when there is no situable currency in the reference_value
		$reference_value = array("USD" => 100
								, "EUR" => 80
								, "GBP" => 60
								, "HKD" => 1
								, "AUD" => 110
								, "MYR" => 320
								, "NZD" => 115
								, "PHP" => 4400
								, "PLN" => 310
								, "RUB" => 3500
								, "SGD" => 125
								, "CHF" => 90
								);
		$battery_cat_list = "(393, 396, 487, 525, 202, 225, 377)";
        $item_list = "";
        $cart_sku_list = array();

		if (is_null($platform))
		{
			$platform = defined(PLATFORMID)?PLATFORMID:"WEBGB";
		}

        if ($_SESSION["cart"])
        {
            foreach ($_SESSION["cart"][$platform] as $key => $item)
            {
                if ($item_list != "")
                    $item_list .= ",";
                $item_list .= "'" . $key . "'";
                $cart_sku_list[] = $key;
            }
            if ($item_list != "")
                $item_list = "(" . $item_list . ")";

            $battery_sku_list = $this->prod_svc->get_dao()->check_battery_inside_cart($battery_cat_list, $item_list);
//        print $this->prod_svc->get_dao()->db->last_query();

            if ($battery_sku_list)
            {
				if (!array_key_exists(PLATFORMCURR, $reference_value))
                {
//do a conversion to ref USD
                    $converted_ref_amount = $this->convert_amount($reference_amount, $reference_currency, PLATFORMCURR);
                }
				else
				    $converted_ref_amount = $reference_value[PLATFORMCURR];
				// it contains battery, further check, if there is any sku in the cart other than the battery sku
                foreach ($cart_sku_list as $sku)
                {
                    if (!in_array($sku, $battery_sku_list))
                    {
						//final check the amount
                        if ($total_amount >= $converted_ref_amount)
                        {
							//cart contain SKU other than battery and total amount >= 100USD
                            return false;
                        }
                        else
                        {
//cart contain SKU other than battery but total amount < 100USD
                            return $converted_ref_amount;
                        }
                    }
                }
//cart contain battery only
                return $converted_ref_amount;
            }
            else
            {
//cart does not contain battery
                return false;
            }
        }
        return false;
	}

	public function set_exchange_rate_service($ex_rate_srv)
	{
		$this->exchange_rate_service = $ex_rate_srv;
	}

	public function get_exchange_rate_service()
	{
		return $this->exchange_rate_service;
	}

	protected function convert_amount($amount, $from_currency, $to_currency)
	{
		$ex_rate_obj = $this->exchange_rate_service->get(array("from_currency_id" => $from_currency, "to_currency_id" => $to_currency));
		if ($ex_rate_obj)
		{
			$ex_rate = $ex_rate_obj->get_rate();
			return number_format(($amount * $ex_rate), 2, '.', '');
		}
		return $amount;
	}

	public function is_allow_to_add($sku, $qty, $platform = NULL)
	{
		$product_obj = $this->prod_svc->get_dao()->get(array("sku" => $sku));
		if ( ! $product_obj) {
			return self::UNKNOWN_ITEM_STATUS;
		}

		//we don't check "out of stock item", it suppose not to be added
		$website_status = $product_obj->get_website_status();
		if (count($_SESSION["cart"][$platform]) == 0) {
			//no item in cart
			if ($website_status == 'I') {
				return self::ALLOW_AND_IS_NORMAL_ITEM;
			} elseif ($website_status == 'P') {
				return self::ALLOW_AND_IS_PREORDER;
			} elseif ($website_status == 'A') {
				return self::ALLOW_AND_IS_ARRIVING;
			}

			return self::UNKNOWN_ITEM_STATUS;
		} else {
			foreach ($_SESSION["cart"][$platform] as $key => $value) {
				if ($sku != $key) {
					continue;
				} else {
					if ($website_status == "P") {
						return self::SAME_PREORDER_ITEM;
					} elseif ($website_status == "A") {
						return self::SAME_ARRIVING_ITEM;
					} elseif ($website_status == "I") {
						return self::SAME_NORMAL_ITEM;
					}
				}
			}
			foreach ($_SESSION["cart"][$platform] as $key => $value) {
				$stored_website_status = $value["website_status"];
				// var_dump($stored_website_status);
				// var_dump($website_status);
				if (($stored_website_status == "I")
					&& (($website_status == "P") || ($website_status == "A")))
				{
					return self::NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM;
				} elseif ((($stored_website_status == "P") || ($stored_website_status == "A"))
						&& ($website_status == "I"))
				{
					return self::NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM;
				} elseif ((($stored_website_status == "P") && ($website_status == "P"))
						|| (($stored_website_status == "P") && ($website_status == "A")))
				{
					return self::DIFFERENT_PREORDER_ITEM;
				} elseif ((($stored_website_status == "A") && ($website_status == "A"))
						|| (($stored_website_status == "A") && ($website_status == "P")))
				{
					return self::DIFFERENT_ARRIVING_ITEM;
				}
			}
			return self::ALLOW_AND_IS_NORMAL_ITEM;
		}
	}

	public function get_cart($platform = NULL)
	{
		if (is_null($platform))
		{
			$platform = defined(PLATFORMID)?PLATFORMID:"WSUS";
		}

		return $_SESSION["cart"][$platform];
	}

	public function remove($sku, $platform = NULL)
	{
		if (is_null($platform))
		{
			$platform = defined(PLATFORMID)?PLATFORMID:"WSUS";
		}

		unset($_SESSION["cart"][$platform][$sku]);
		unset($_SESSION["ra_items"][$platform][$sku]);
		if (isset($_SESSION["cart_from_url"][$platform][$sku]))
		{
			unset($_SESSION["cart_from_url"][$platform][$sku]);
		}
		$this->set_cart_cookie($platform);
		return true;
	}

	public function set_cart_cookie($platform)
	{
		include_once(APPPATH . "hooks/country_selection.php");
		$country_selection = new Country_selection();
		//	set the cart cookie, to rebuild cart if domain changes
		$country_selection->set_cart_cookie(base64_encode(serialize($_SESSION["cart"][$platform])));
	}

	public function check_cart($cart_list = array(), $platform = NULL, $lang_id = "en", $renew_cart = 0)
	{

		if (is_null($platform))
		{
			$platform = defined(PLATFORMID)?PLATFORMID:"WSUS";
		}

		$new_cart = $remove = $replace = array();

		foreach ($cart_list as $sku=>$value)
		{
			if (is_array($value))
			{
				$qty = $value["qty"];
				$website_status = $value["website_status"];
			}
			else
			{
				$qty = $value;
			}
			$need_adj = 0;
			if ($prod_list = $this->prod_svc->get_dao()->get_bundle_components_overview(array("vpi.prod_sku"=>$sku, "vpo.platform_id"=>$platform, "p.status"=>2, "p.website_status <>"=>'O')))
			{

				foreach ($prod_list as $prod)
				{
					$ws_qty = $prod->get_website_quantity();
					$ws_status = $prod->get_website_status();
					$status = $prod->get_prod_status();
					$listing_status = $prod->get_listing_status();
					$price = $prod->get_price();
					if (!($ws_qty != "O" && $status == '2' && $listing_status == 'L' && $ws_qty > 0 && ($price*1 > 0 || $this->prod_svc->is_trial_software($prod->get_sku()))))
					{
						$need_adj = 1;
						break;
					}
				}
			}
			if ($need_adj)
			{
				$need_remove = 0;
				list($grp_cd, $version, $colour) = explode("-", $sku);
				$where["vpo.prod_grp_cd"] = $grp_cd;
				$where["vpo.colour_id"] = $colour;
				$where["vpo.platform_id"] = $platform;
				$where["vpo.prod_status"] = 2;
				$where["vpo.listing_status"] = 'L';
				$where["pc.lang_id"] = get_lang_id();
				$option = array("limit"=>1);
				if ($obj = $this->prod_svc->get_dao()->get_prod_overview_wo_cost_w_content_name($where, $option))
				{
					$price = $obj->get_price();
					$new_sku = $obj->get_sku();
					$website_status = $obj->get_website_status();
					if ($price*1 > 0 || $this->prod_svc->is_trial_software($new_sku))
					{
						$replace[$sku] = $new_sku;
						$new_cart[$new_sku] = array("qty" => $qty,
													 "website_status" => $website_status);
					}
					else
					{
						$need_remove = 1;
					}
				}
				else
				{
					$need_remove = 1;
				}

				if ($need_remove)
				{
					$where2["p.sku"] = $sku;
					$where2["pc.lang_id"] = get_lang_id();
					if ($obj = $this->prod_svc->get_dao()->get_prod_w_content_name($where2, $option))
					{
						$remove[] = $obj->get_name();
					}
				}
			}
			else
			{
				if (is_array($value))
				{
					$new_cart[$sku] = $value;
				}
				else
				{
					$new_cart[$sku] = $qty;
				}
			}
		}
		return ($renew_cart || empty($remove)) ? array("cart"=>$new_cart) : array("remove"=>$remove);
	}

	public function get_detail($platform = NULL, $get_dc=1, $get_detail=0, $get_weight=0, $special=0, $vat_exempt=0, $free_delivery=0, $customised_dc=0, $promotion_code="",$need_vat=0, $delivery_country=NULL)
	{
		if (is_null($platform)) {
			$platform = defined(PLATFORMID) ? PLATFORMID : "WSUS";
		}

		include_once(APPPATH."libraries/service/Class_factory_service.php");
		$cf_srv = new Class_factory_service();
		if ( ! ($price_srv = $cf_srv->get_platform_price_service($platform))) {
			return FALSE;
		}

		if ($promotion_code == "" && $_SESSION["promotion_code"]) {
			$promotion_code = $_SESSION["promotion_code"];
		}

		$ret["cart"] =  array();
		$total_cost = $total = 0;
		$weight = 0;
		$bundle_discount_list = $item_sku = array();
		if (count($_SESSION["cart"][$platform])) {
			if ($_SESSION["cart_from_url"][$platform]) {
				$bundle_discount_list = $this->prod_svc->get_bd_dao()->check_bundle_discount(array_keys($_SESSION["cart_from_url"][$platform]));
			}

			$i = 0;

			foreach ($_SESSION["cart"][$platform] as $key => $value) {
				$qty = $value["qty"];

				$subcost = 0;
				$subtotal = 0;
				$subvat = 0;
				$pobj = $price_srv->get_dao()->get_list_with_bundle_checking($key, $platform, "Product_cost_dto", $special, get_lang_id());

				$fdl = 0;
				$pobj_count = 0;
				if ($pobj) {
					$item_sku[$key] = $qty;

					foreach ($pobj as $o) {
						// $p_svc = $price_srv->get_price_service_from_dto($o);

						$sku = $o->get_sku();
						$name = $o->get_bundle_name();
						$bundle_discount = 0;

						if (is_array($value)) {
							$cur_price = $value["price"];
							$o->set_price($cur_price);
							$pqty = $value["qty"];
						} else {
							$pqty = $value;
						}
						// $price_srv->calc_freight_cost($o, $p_svc, ($currency_id = $o->get_platform_currency_id()));
						$price_srv->calc_logistic_cost($o);
						$price_srv->calculate_profit($o);

						$weight += $pqty * $o->get_prod_weight();

						if ($o->get_sku() == $key && $bundle_discount_list[$key]) {
							$bundle_discount = $bundle_discount_list[$key]*1;
							$item_price = number_format(random_markup($o->get_price()) * (100 - $bundle_discount) / 100, 2, ".", "");
							$cur_cost = $o->get_cost();
							$subcost += $cur_cost;
						} else {
							if (($item_price = $price_srv->get_item_price($o)) !== FALSE) {
								$cur_cost = $o->get_cost();
								$subcost += $cur_cost;
							} else {
								return FALSE;
							}
						}

						if (!$special) {
							$cur_price = $item_price;
						}

						$subtotal += $cur_price;
						$subvat += ($cur_vat = $o->get_vat() * (100 - $o->get_discount()) / 100);

						$fdl = $o->get_free_delivery_limit();
						if ($get_detail) {
							if ($ret["detail"][$i][$sku]) {
								//modify by thomas, reserve for multiple product of same sku on bundle
								$pqty = $ret["detail"][$i][$sku]["qty"] + $pqty;
							}
							if ($vat_exempt == 0) {
								$ret["detail"][$i][$sku] = array("sku"=>$sku, "qty"=>$pqty,"name"=>$o->get_prod_name(),"price"=>$cur_price, "discount"=>$bundle_discount?$bundle_discount:$o->get_discount(), "total"=>$cur_price*$pqty, "vat_total"=>$cur_vat*$pqty, "cost"=>$cur_cost*$pqty, "product_cost_obj"=>$o);
							} else {
								$ret["detail"][$i][$sku] = array("sku"=>$sku, "qty"=>$pqty,"name"=>$o->get_prod_name(),"price"=>$cur_price - $cur_vat, "discount"=>$bundle_discount?$bundle_discount:$o->get_discount(), "total"=>($cur_price - $cur_vat)*$pqty, "vat_total"=>0, "cost"=>$cur_cost*$pqty - $cur_vat*$pqty, "product_cost_obj"=>$o);
							}
						}
					}
					if (is_array($value)) {
						$qty = $value["qty"];
					} else {
						$qty = $value;
					}

					// $o->get_component_order() > -1 is bundle
					if (($components = $o->get_component_order()) > -1) {
						if (!isset($round_up)) {
							$round_up = $this->curr_svc->round_up_of($currency_id);
						}
						$new_subtotal = price_round_up($subtotal, $round_up);
						$subtotal_diff = $new_subtotal - $subtotal;
						if ($subtotal_diff) {
							$subtotal = $new_subtotal;
							if ($get_detail) {
								$ar_adj_price = average_divide($subtotal_diff, $components+1);
								$adj_count = 0;
								foreach ($ret["detail"][$i] as $sku=>$data) {
									$cur_adj_price = $adj_count == 0?$ar_adj_price["first"]:$ar_adj_price["rest"];
									$ret["detail"][$i][$sku]["price"] += $cur_adj_price;
									$ret["detail"][$i][$sku]["total"] += $cur_adj_price * $qty;
									$adj_count++;
								}
							}
						}
					}

					$rstotal = $qty * $subtotal;
					$rscost = $qty * $subcost;
					$total += $rstotal;

					if ($vat_exempt == 0) {
						$ret["cart"][$i] = array(
							"sku" => $key,
							"image" => $o->get_image(),
							"qty" => $qty,
							"name" => $name,
							"price" => $subtotal,
							"total" => $rstotal,
							"cost" => $rscost,
							"vat_total" => $subvat * $qty,
							"product_cost_obj" => $o,
							"website_status" => $value["website_status"]
						);
					} else {
							$ret["cart"][$i] = array(
								"sku" => $key,
								"image" => $o->get_image(),
								"qty" => $qty,
								"name" => $name,
								"price" => $subtotal - $subvat,
								"total" => $rstotal - $subvat * $qty,
								"cost" => $rscost - $subvat * $qty,
								"vat_total" => 0,
								"product_cost_obj" => $o
							);
					}

					$i++;
					$pobj_count++;
				}
				if ($pobj_count == 0 && $special) {
					if ($prod_price_obj = $this->prod_svc->get_dao()->get_product_with_price($key, $platform)) {
						$rstotal = $prod_price_obj->get_price()* $value;
						$total += $rstotal;
						$ret["cart"][$i] = array(
								"sku" => $key,
								"image" => $prod_price_obj->get_image(),
								"qty" => $value,
								"name" => $prod_price_obj->get_name(),
								"price" => $prod_price_obj->get_price(),
								"total" => $rstotal,
								"cost" => 0,
								"vat_total" => 0,
								"product_cost_obj" => $o
							);
						$i++;
					}
				}
			}

			if ($this->get_del_country_id() == "") {
				$this->set_del_country_id($_SESSION["client"]["del_country_id"] ? $_SESSION["client"]["del_country_id"] : PLATFORMCOUNTRYID);
			}

			if ($this->get_del_state() == "") {
				$this->set_del_state($_SESSION["client"]["del_state"]);
			}

			if ($this->get_del_postcode() == "") {
				$this->set_del_postcode($_SESSION["client"]["del_postcode"]);
			}

			if (is_null($this->get_email()) && $_SESSION["client"]["email"]) {
				$this->set_email($_SESSION["client"]["email"]);
			}

			if ($promotion_code) {
				$this->promo_cd_svc->promo_code = $promotion_code;
				$this->promo_cd_svc->platform_id = $platform;
				$this->promo_cd_svc->amount = $total;
				$this->promo_cd_svc->item_list = $item_sku;
				$this->promo_cd_svc->country_id = $this->get_del_country_id();
				$this->promo_cd_svc->email = $this->get_email();
				$this->promo_cd_svc->set_delivery_mode(is_null($this->get_delivery_mode())?$this->del_svc->get_default_delivery():$this->get_delivery_mode());

				$ret["promo"] = $this->promo_cd_svc->check_promotion_code();
				if ($ret["promo"]["disc_amount"] && $get_detail) {
					$this->amend_cart_detail($ret["detail"], $ret["cart"], $ret["promo"]["disc_item_list"]?$ret["promo"]["disc_item_list"]:$item_sku, $ret["promo"]);
				}

				if ($ret["promo"]["free_delivery"]) {
					$free_delivery = 1;
					$free_delivery_mode = $ret["promo"]["promotion_code_obj"]->get_disc_level_value();
					$this->del_svc->set_promotion_disc_level_value($ret["promo"]["promotion_code_obj"]->get_disc_level_value());
				} elseif ($ret["promo"]["free_item"]) {
					$ret["cart"][$i] = $ret["promo"]["free_item"];
					$free_sku = $ret["promo"]["free_item"]["sku"];
					$free_qty = $ret["promo"]["free_item"]["qty"];
					$free_pobj = $price_srv->get_dao()->get_list_with_bundle_checking($free_sku, $platform, "Product_cost_dto", 0, get_lang_id());
					foreach ($free_pobj as $free_o) {
						$p_svc = $price_srv->get_price_service_from_dto($free_o);
						$price_srv->calc_logistic_cost($free_o);
						$price_srv->calculate_profit($free_o);
						$ret["cart"][$i]["product_cost_obj"] = $free_o;
						$ret["detail"][$i][$free_sku] = array("sku"=>$free_sku, "qty"=>$free_qty,"name"=>$free_o->get_prod_name(),"price"=>0, "discount"=>0, "total"=>0, "vat_total"=>0, "cost"=>$free_o->get_cost()*$free_qty, "product_cost_obj"=>$free_o);
					}
					$i++;
				}

				// recalculate the cost after promotion code applied
				if ($ret['cart'] && $ret['promo']['valid']) {
					foreach ($ret['cart'] as $key=>$val) {
						if ($val) {
							$new_price = ($val['total'])/$val['qty'];
							$val['product_cost_obj']->set_price($new_price);
							$p_svc = $price_srv->get_price_service_from_dto($val['product_cost_obj']);
							$price_srv->calc_logistic_cost($val['product_cost_obj']);
							$price_srv->calculate_profit($val['product_cost_obj']);

							$ret['cart'][$key]['price'] = $ret['cart'][$key]['price'];
							$ret['cart'][$key]['cost'] = $val['product_cost_obj']->get_cost()*$val['qty'];
							if ($vat_exempt == 0) {
								$ret['cart'][$key]['vat_total'] = $val['product_cost_obj']->get_vat()*$val['qty'];
							} else {
								$ret['cart'][$key]['vat_total'] = 0;
							}
						}
					}
				}

				// recalculate the cost after promotion code applied
				if ($ret['detail'] && $ret['promo']['valid']) {
					foreach ($ret['detail'] as $key=>$obj) {
						if ($obj) {
							foreach ($obj as $sku=>$val) {
								$new_price = $val['total']/$val['qty'];
								$val['product_cost_obj']->set_price($new_price);
								$p_svc = $price_srv->get_price_service_from_dto($val['product_cost_obj']);
								$price_srv->calc_logistic_cost($val['product_cost_obj']);
								$price_srv->calculate_profit($val['product_cost_obj']);
								//$ret['detail'][$key][$sku]['price'] = $new_price;
								$ret['detail'][$key][$sku]['cost'] = $ret['detail'][$key][$sku]['product_cost_obj']->get_cost()*$ret['detail'][$key][$sku]['qty'];
								if ($vat_exempt == 0) {
									$ret['detail'][$key][$sku]['vat_total'] = $val['product_cost_obj']->get_vat()*$val['qty'];
								} else {
									$ret['detail'][$key][$sku]['vat_total'] = 0;
								}
							}
						}
					}
				}
			}
			$over_free_delivery_limit = FALSE;
			if ($total - $ret["promo"]["disc_amount"] > $fdl) {
				$over_free_delivery_limit = TRUE;
			}
			if ($get_dc) {
				$this->del_svc->item_list = $item_sku;
				$this->del_svc->platform_id = $platform;
				$this->del_svc->delivery_country_id = $this->get_del_country_id();
				$this->del_svc->delivery_state = $this->get_del_state();
				$this->del_svc->delivery_postcode = $this->get_del_postcode();
				$this->del_svc->vat_percent = @call_user_func(array($o, "get_vat_percent"));
				$this->del_svc->free_delivery = $free_delivery;
				$this->del_svc->over_free_delivery_limit = $over_free_delivery_limit;
				$this->del_svc->customised_dc = $customised_dc;
				$this->del_svc->weight = $weight;
				$this->del_svc->special = $special;
				$dc = $this->del_svc->get_delivery_options();
				/*
				if ($free_delivery_mode && !isset($dc["dc"][$free_delivery_mode]))
				{
					$ret["promo"] = array("valid"=>0, "error"=>"FD", "error_code"=>$free_delivery_mode);
				}
				*/
				$ret["dc"] = $dc["dc"];
				$ret["dc_default"] = $dc["dc_default"];
				$this->set_delivery_charge($this->del_svc->get_delivery_charge());
			}
			if ($get_weight) {
				$ret["weight"] = $weight;
			}

			if (is_null($delivery_country)) {
				if (defined('PLATFORMCOUNTRYID')) {
					$delivery_country = PLATFORMCOUNTRYID;
				} else {
					$delivery_country = '';
				}
			}

			// SBF #3249, Comment out this GST function (#2236)
			for($i = 0; $i < count($ret['cart']); $i++) {
				$ret['cart'][$i]['gst'] = 0;
			}

			if ($ret['detail']) {
				for($i = 0; $i < count($ret['detail']); $i++) {
					if ($ret['detail'][$i]) {
						foreach ($ret['detail'][$i] as $sku => $val) {
							$ret['detail'][$i][$sku]['gst'] = 0;
						}
					}
				}
			}
		}
		return $ret;
	}

	public function get($platform = NULL, $foobar = '')
	{
		if (is_null($platform))
		{
			$platform = PLATFORMID;
		}

		include_once(APPPATH."libraries/service/Class_factory_service.php");
		$cf_srv = new Class_factory_service();
		$price_srv = $cf_srv->get_platform_price_service($platform);

		$ret = array();
		$total = 0;
		$count = 0;
		if (isset($_SESSION['cart'][$platform]) && count($_SESSION["cart"][$platform])) {
			foreach ($_SESSION["cart"][$platform] as $key=>$val)
			{
				$subtotal = 0;
				$subvat = 0;
				$pobj = $price_srv->get_dao()->get_list_with_bundle_checking($key, $platform, "Product_cost_dto", 0, get_lang_id());
				$fdl = 0;
				foreach ($pobj as $o)
				{
					$name = $o->get_bundle_name();

					$p_svc = $price_srv->get_price_service_from_dto($o);
					if (is_array($val))
					{
						//$platform_price_svc->set_price($value["price"]);
						if (isset($val["price"]))
							$o->set_price($val["price"]);
						$pqty = $val["qty"];
					}
					else
					{
						$pqty = $val;
					}

					//$price_srv->calc_freight_cost($o, $p_svc, ($currency_id = $o->get_platform_currency_id()));
					$price_srv->calc_logistic_cost($o);
					$price_srv->calculate_profit($o);

					$weight += $pqty * $o->get_prod_weight();
					$subtotal += $price_srv->get_item_price($o);
					$subvat += ($cur_vat = $o->get_vat() * (100 - $o->get_discount()) / 100);
					$fdl = $o->get_free_delivery_limit();
				}

				$count += $pqty;
				if ($o)
				{
					if (($components = $o->get_component_order()) > -1)
					{
						if (!isset($round_up))
						{
							$round_up = $this->curr_svc->round_up_of($currency_id);
						}
						$new_subtotal = price_round_up($subtotal, $round_up);
						$subtotal_diff = $new_subtotal - $subtotal;
						if ($subtotal_diff)
						{
							$subtotal = $new_subtotal;
						}
					}
				}

				$rstotal = $pqty * $subtotal;
				$total += $rstotal;
			}
		}
		return array("count"=>$count, "total"=>$total);
	}

	public function amend_cart_detail(&$detail, &$cart, $disc_item_list, $ar_promo)
	{
		$disc_amount = $ar_promo["disc_amount"];
		$promotion_code_obj = $ar_promo["promotion_code_obj"];
		$discount = $ar_promo["discount"];

		$number_of_separated_items = 0;
		foreach ($cart as $line_idx=>$item_data)
		{
			if ($item_data["product_cost_obj"]->get_component_order() == -1)
			{
				$number_of_separated_items += $item_data["qty"];
			}
			else
			{
				$number_of_separated_items += (($item_data["product_cost_obj"]->get_component_order() + 1) * 1) * $item_data["qty"];
			}
		}

		$ar_i_adj_price = average_divide($disc_amount, $number_of_separated_items);

		foreach ($cart as $line_idx=>$item_data)
		{
			$apply_promotion = FALSE;
			$sku = $item_data["sku"];
			$product_cost_obj = $item_data["product_cost_obj"];

			if (($promotion_code_obj->get_disc_type() == "FD") || ($promotion_code_obj->get_disc_type() == "FI"))
			{
				$apply_promotion = TRUE;
			}
			else
			{
				$check_value = '';
				$promo_value = explode(',', $promotion_code_obj->get_disc_level_value());

				switch ($promotion_code_obj->get_disc_level())
				{
					case "PD":
						$check_value = $sku;
						break;
					case "CAT":
						$check_value = $product_cost_obj->get_cat_id();
						break;
					case "SCAT":
						$check_value = $product_cost_obj->get_sub_cat_id();
						break;
					case "SSCAT":
						$check_value = $product_cost_obj->get_sub_sub_cat_id();
						break;
					case "BN":
						$check_value = $product_cost_obj->get_brand_id();
						break;
					default:
						$promo_value = array();  // Set to empty array
						break;
				}

				if (count($promo_value) == 0)
				{
					$apply_promotion = TRUE;
				}
				else
				{
					if (in_array($check_value, $promo_value))
					{
						$apply_promotion = TRUE;
					}
				}
			}

			if (($qty = $disc_item_list[$sku]) && ($apply_promotion === TRUE))
			{
//				$components = count($detail[$line_idx]);
//				$i_disc_amt = $line_idx == 0?$ar_i_adj_price["first"]:$ar_i_adj_price["rest"];
//				$ar_adj_price = average_divide($i_disc_amt, $components*$qty);

				$adj_count = 0;
				foreach ($detail[$line_idx] as $d_sku=>$item_detail)
				{
					if ($promotion_code_obj->get_disc_type() == "A")
					{
						if (($adj_count == 0) && ($line_idx == 0))
						{
							$promo_disc_amt = $ar_i_adj_price["first"] + ($qty - 1) * $ar_i_adj_price["rest"];
						}
						else
						{
							$promo_disc_amt = $qty * $ar_i_adj_price["rest"];
						}
					}
					else
					{
						$promo_disc_amt = number_format($detail[$line_idx][$d_sku]["total"] * $discount/100, 2, '.', '');
					}

					$detail[$line_idx][$d_sku]["promo_disc_amt"] = $promo_disc_amt;
					$detail[$line_idx][$d_sku]["total"] -= $promo_disc_amt;
					$cart[$line_idx]["total"] -= $promo_disc_amt;
					$cart[$line_idx]["promo_disc_amt"] = $promo_disc_amt;
					$adj_count++;
				}
			}
		}
	}

	public function empty_cart()
	{
		unset($_SESSION["cart"]);
	}

	public function get_delivery_charge()
	{
		return $this->default_delivery_charge;
	}

	private function set_delivery_charge($value)
	{
		$this->delivery_charge = $value;
	}

	public function get_del_country_id()
	{
		return $this->del_country_id;
	}

	public function set_del_country_id($value)
	{
		$this->del_country_id = $value;
	}

	public function get_del_state()
	{
		return $this->del_state;
	}

	public function set_del_state($value)
	{
		$this->del_state = $value;
	}

	public function get_del_postcode()
	{
		return $this->del_postcode;
	}

	public function set_del_postcode($value)
	{
		$this->del_postcode = $value;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function set_email($value)
	{
		$this->email = $value;
	}

	public function get_delivery_mode()
	{
		return $this->delivery_mode;
	}

	public function set_delivery_mode($value)
	{
		$this->delivery_mode = $value;
	}

}
