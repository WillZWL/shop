<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Product_cost_dto extends Base_dto
{

	private $platform_id;
	private $vat;
	private $vat_percent;
	private $duty;
	private $cc_code;
	private $cc_desc;
	private $declared_value;
	private $declared_pcent;
	private $payment_charge;
	private $admin_fee;
	private $supplier_cost;
	private $item_cost;
	private $purchaser_updated_date;
	private $logistic_cost;
	private $delivery_cost;
	private $freight_cost;
	private $complementary_acc_cost;
	private $platform_commission;
	private $sales_commission;
	private $master_sku;
	private $sku;
	private $prod_grp_cd;
	private $version_id;
	private $colour_id;
	private $item_vat;
	private $prod_name;
	private $shiptype;
	private $shiptype_name;
	private $cost;
	private $price;
	private $current_platform_price;
	private $default_platform_converted_price;
	private $duty_pcent;
	private $import_percent;
	private $payment_charge_percent;
	private $platform_currency_id;
	private $delivery_charge;
	private $platform_delivery_charge;
	private $default_delivery_charge;
	private $free_delivery_limit;
	private $quantity;
	private $display_quantity;
	private $inventory;
	private $clearance;
	private $listing_status;
	private $website_quantity;
	private $surplus_quantity;
	private $website_status;
	private $sourcing_status;
	private $platform_default_shiptype;
	private $platform_code;
	private $cat_id;
	private $sub_cat_id;
	private $sub_sub_cat_id;
	private $brand_id;
	private $category;
	private $sub_cat;
	private $sub_sub_category;
	private $brand_name;
	private $image;
	private $youtube_id;
	private $prod_weight;
	private $profit;
	private $margin;
	private $profit_raw;
	private $margin_raw;
	private $freight_cat_id;
	private $discount;
	private $detail_desc;
	private $prod_status;
	private $supplier_id;
	private $content_prod_name;
	private $ean;
	private $mpn;
	private $upc;
	private $feeds;
	private $int_price;
	private $latency;
	private $auto_price;
	private $suppfc_cost;
	private $whfc_cost;
	private $amazon_efn_cost;
	private $fccus_cost;
	private $extra_info;
	private $platform_region_id;
	private $platform_country_id;
	private $language_id;
	private $component_order;
	private $with_bundle;
	private $title;
	private $ext_ref_1;
	private $ext_ref_2;
	private $ext_ref_3;
	private $ext_ref_4;
	private $ext_qty;
	private $ext_item_id;
	private $ext_status;
	private $action;
	private $remark;
	private $fulfillment_centre_id;
	private $amazon_reprice_name;
	private $listing_fee;
	private $sub_cat_margin;
	private $forex_fee_percent;
	private $forex_fee;
	private $auto_total_charge;
	private $wms_inv;
	private $expected_delivery_date;
	private $fixed_rrp;
	private $rrp_factor;
	private $warranty_in_month;
	private $handling_time;
	private $ship_day;
	private $delivery_day;
	private $gsc_status;
	private $is_advertised;
	private $gsc_cm_id;
	private $gsc_product_name;
	private $gsc_ext_name;
	private $api_request_result;
	private $comment;
	private $ad_api_request_result;
	private $ad_status;
	private $lang_restricted;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_vat()
	{
		return $this->vat;
	}

	public function set_vat($value)
	{
		$this->vat = $value;
	}

	public function get_item_vat()
	{
		return $this->item_vat;
	}

	public function set_item_vat($value)
	{
		$this->item_vat = $value;
	}

	public function get_vat_percent()
	{
		return $this->vat_percent;
	}

	public function set_vat_percent($value)
	{
		$this->vat_percent = $value;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function get_duty()
	{
		return $this->duty;
	}

	public function set_duty($value)
	{
		$this->duty = $value;
	}

	public function get_cc_code()
	{
		return $this->cc_code;
	}

	public function set_cc_code($value)
	{
		$this->cc_code = $value;
	}

	public function get_cc_desc()
	{
		return $this->cc_desc;
	}

	public function set_cc_desc($value)
	{
		$this->cc_desc = $value;
	}

	public function get_declared_value()
	{
		return $this->declared_value;
	}

	public function set_declared_value($value)
	{
		$this->declared_value = $value;
	}

	public function get_declared_pcent()
	{
		return $this->declared_pcent;
	}

	public function set_declared_pcent($value)
	{
		$this->declared_pcent = $value;
	}

	public function get_payment_charge()
	{
		return $this->payment_charge;
	}

	public function set_payment_charge($value)
	{
		$this->payment_charge = $value;
	}

	public function get_admin_fee()
	{
		return $this->admin_fee;
	}

	public function set_admin_fee($value)
	{
		$this->admin_fee = $value;
	}

	public function get_supplier_cost()
	{
		return number_format($this->supplier_cost, 2, ".", "");
	}

	public function set_supplier_cost($value)
	{
		$this->supplier_cost = $value;
	}

	public function get_item_cost()
	{
		return number_format($this->item_cost, 2, ".", "");
	}

	public function set_item_cost($value)
	{
		$this->item_cost = $value;
	}

	public function get_purchaser_updated_date()
	{
		return $this->purchaser_updated_date;
	}

	public function set_purchaser_updated_date($value)
	{
		$this->purchaser_updated_date = $value;
	}

	public function get_logistic_cost()
	{
		return $this->logistic_cost;
	}

	public function set_logistic_cost($value)
	{
		$this->logistic_cost = $value;
	}

	public function get_delivery_cost()
	{
		return $this->delivery_cost;
	}

	public function set_delivery_cost($value)
	{
		$this->delivery_cost = $value;
	}

	public function get_freight_cost()
	{
		return $this->freight_cost;
	}

	public function set_freight_cost($value)
	{
		$this->freight_cost = $value;
	}

	public function get_complementary_acc_cost()
	{
		return $this->complementary_acc_cost;
	}

	public function set_complementary_acc_cost($value)
	{
		$this->complementary_acc_cost = $value;
	}

	public function get_listing_status()
	{
		return $this->listing_status;
	}

	public function set_listing_status($value)
	{
		$this->listing_status = $value;
	}

	public function get_platform_commission()
	{
		return $this->platform_commission;
	}

	public function set_platform_commission($value)
	{
		$this->platform_commission = $value;
	}

	public function get_sales_commission()
	{
		return $this->sales_commission;
	}

	public function set_sales_commission($value)
	{
		$this->sales_commission = $value;
	}

	public function get_master_sku()
	{
		return $this->master_sku;
	}

	public function set_master_sku($value)
	{
		$this->master_sku = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_prod_grp_cd()
	{
		return $this->prod_grp_cd;
	}

	public function set_prod_grp_cd($value)
	{
		$this->prod_grp_cd = $value;
		return $this;
	}

	public function get_version_id()
	{
		return $this->version_id;
	}

	public function set_version_id($value)
	{
		$this->version_id = $value;
		return $this;
	}

	public function get_colour_id()
	{
		return $this->colour_id;
	}

	public function set_colour_id($value)
	{
		$this->colour_id = $value;
		return $this;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}

	public function get_shiptype()
	{
		return $this->shiptype;
	}

	public function set_shiptype($value)
	{
		$this->shiptype = $value;
	}

	public function get_shiptype_name()
	{
		return $this->shiptype_name;
	}

	public function set_shiptype_name($value)
	{
		$this->shiptype_name = $value;
	}

	public function get_cost()
	{
		return $this->cost;
	}

	public function set_cost($value)
	{
		$this->cost = $value;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function set_price($value)
	{
		$this->price = $value;
	}

	public function get_current_platform_price()
	{
		return $this->current_platform_price;
	}

	public function set_current_platform_price($value)
	{
		$this->current_platform_price = $value;
	}

	public function get_default_platform_converted_price()
	{
		return $this->default_platform_converted_price;
	}

	public function set_default_platform_converted_price($value)
	{
		$this->default_platform_converted_price = $value;
	}

	public function get_duty_pcent()
	{
		return $this->duty_pcent;
	}

	public function set_duty_pcent($value)
	{
		$this->duty_pcent = $value;
	}

	public function get_import_percent()
	{
		return $this->import_percent;
	}

	public function set_import_percent($value)
	{
		$this->import_percent = $value;
	}

	public function get_payment_charge_percent()
	{
		return $this->payment_charge_percent;
	}

	public function set_payment_charge_percent($value)
	{
		$this->payment_charge_percent = $value;
	}

	public function get_platform_currency_id()
	{
		return $this->platform_currency_id;
	}

	public function set_platform_currency_id($value)
	{
		$this->platform_currency_id = $value;
	}

	public function get_delivery_charge()
	{
		return $this->delivery_charge;
	}

	public function set_delivery_charge($value)
	{
		$this->delivery_charge = $value;
	}

	public function get_free_delivery_limit()
	{
		return $this->free_delivery_limit;
	}

	public function set_free_delivery_limit($value)
	{
		$this->free_delivery_limit = $value;
	}

	public function get_quantity()
	{
		return $this->quantity;
	}

	public function set_quantity($value)
	{
		$this->quantity = $value;
	}

	public function get_inventory()
	{
		return $this->inventory;
	}

	public function set_inventory($value)
	{
		$this->inventory = $value;
	}

	public function get_clearance()
	{
		return $this->clearance;
	}

	public function set_clearance($value)
	{
		$this->clearance = $value;
	}

	public function get_display_quantity()
	{
		return $this->display_quantity;
	}

	public function set_display_quantity($value)
	{
		$this->display_quantity = $value;
	}

	public function get_website_quantity()
	{
		return $this->website_quantity;
	}

	public function set_website_quantity($value)
	{
		$this->website_quantity = $value;
	}

	public function get_surplus_quantity()
	{
		return $this->surplus_quantity;
	}

	public function set_surplus_quantity($value)
	{
		$this->surplus_quantity = $value;
		return $this;
	}

	public function get_website_status()
	{
		return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
	}

	public function get_sourcing_status()
	{
		return $this->sourcing_status;
	}

	public function set_sourcing_status($value)
	{
		$this->sourcing_status = $value;
	}

	public function get_platform_default_shiptype()
	{
		return $this->platform_default_shiptype;
	}

	public function set_platform_default_shiptype($value)
	{
		$this->platform_default_shiptype = $value;
	}

	public function get_default_delivery_charge()
	{
		return $this->default_delivery_charge;
	}

	public function set_default_delivery_charge($value)
	{
		$this->default_delivery_charge = $value;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
	}

	public function get_sub_cat_id()
	{
		return $this->sub_cat_id;
	}

	public function set_sub_cat_id($value)
	{
		$this->sub_cat_id = $value;
	}

	public function get_sub_sub_cat_id()
	{
		return $this->sub_sub_cat_id;
	}

	public function set_sub_sub_cat_id($value)
	{
		$this->sub_sub_cat_id = $value;
	}

	public function get_brand_id()
	{
		return $this->brand_id;
	}

	public function set_brand_id($value)
	{
		$this->brand_id = $value;
	}

	public function get_category()
	{
		return $this->category;
	}

	public function set_category($value)
	{
		$this->category = $value;
	}

	public function get_sub_category()
	{
		return $this->sub_category;
	}

	public function set_sub_category($value)
	{
		$this->sub_category = $value;
	}

	public function get_sub_sub_category()
	{
		return $this->sub_sub_category;
	}

	public function set_sub_sub_category($value)
	{
		$this->sub_sub_category = $value;
	}

	public function get_brand_name()
	{
		return $this->brand_name;
	}

	public function set_brand_name($value)
	{
		$this->brand_name = $value;
	}

	public function get_image()
	{
		return $this->image;
	}

	public function set_image($value)
	{
		$this->image = $value;
	}

	public function get_youtube_id()
	{
		return $this->youtube_id;
	}

	public function set_youtube_id($value)
	{
		$this->youtube_id = $value;
	}

	public function get_platform_delivery_charge()
	{
		return $this->platform_delivery_charge;
	}

	public function set_platform_delivery_charge($value)
	{
		$this->platform_delivery_charge = $value;
	}

	public function get_platform_code()
	{
		return $this->platform_code;
	}

	public function set_platform_code($value)
	{
		$this->platform_code = $value;
	}

	public function get_profit()
	{
		return $this->profit;
	}

	public function set_profit($value)
	{
		$this->profit = $value;
	}

	public function get_margin()
	{
		return $this->margin;
	}

	public function set_margin($value)
	{
		$this->margin = $value;
	}


	public function get_profit_raw()
	{
		return $this->profit_raw;
	}

	public function set_profit_raw($value)
	{
		$this->profit_raw = $value;
		return $this;
	}

	public function get_margin_raw()
	{
		return $this->margin_raw;
	}

	public function set_margin_raw($value)
	{
		$this->margin_raw = $value;
		return $this;
	}


	public function get_freight_cat_id()
	{
		return $this->freight_cat_id;
	}

	public function set_freight_cat_id($value)
	{
		$this->freight_cat_id = $value;
	}

	public function get_prod_weight()
	{
		return $this->prod_weight;
	}

	public function set_prod_weight($value)
	{
		$this->prod_weight = $value;
	}

	public function get_discount()
	{
		return $this->discount;
	}

	public function set_discount($value)
	{
		$this->discount = $value;
	}

	public function get_bundle_name()
	{
		return $this->bundle_name;
	}

	public function set_bundle_name($value)
	{
		$this->bundle_name = $value;
	}

	public function get_detail_desc()
	{
		return $this->detail_desc;
	}

	public function set_detail_desc($value)
	{
		$this->detail_desc = $value;
	}

	public function get_prod_status()
	{
		return $this->prod_status;
	}

	public function set_prod_status($value)
	{
		$this->prod_status = $value;
	}

	public function get_supplier_id()
	{
		return $this->supplier_id;
	}

	public function set_supplier_id($value)
	{
		$this->supplier_id = $value;
	}

	public function get_content_prod_name()
	{
		return $this->content_prod_name;
	}

	public function set_content_prod_name($value)
	{
		$this->content_prod_name = $value;
	}

	public function get_ean()
	{
		return $this->ean;
	}

	public function set_ean($value)
	{
		$this->ean = $value;
	}

	public function get_mpn()
	{
		return $this->mpn;
	}

	public function set_mpn($value)
	{
		$this->mpn = $value;
	}

	public function get_upc()
	{
		return $this->upc;
	}

	public function set_upc($value)
	{
		$this->upc = $value;
	}

	public function get_feeds()
	{
		return $this->feeds;
	}

	public function set_feeds($value)
	{
		$this->feeds = $value;
		return $this;
	}

	public function get_int_price()
	{
		return $this->int_price;
	}

	public function set_int_price($value)
	{
		$this->int_price = $value;
		return $this;
	}
	public function get_latency()
	{
		return $this->latency;
	}

	public function set_latency($value)
	{
		$this->latency = $value;
		return $this;
	}

	public function get_auto_price()
	{
		return $this->auto_price;
	}

	public function set_auto_price($value)
	{
		$this->auto_price = $value;
		return $this;
	}

	public function get_suppfc_cost()
	{
		return number_format($this->suppfc_cost, 2, ".", "");
	}

	public function set_suppfc_cost($value)
	{
		$this->suppfc_cost = $value;
		return $this;
	}

	public function get_whfc_cost()
	{
		return number_format($this->whfc_cost, 2, ".", "");
	}

	public function set_whfc_cost($value)
	{
		$this->whfc_cost = $value;
		return $this;
	}

	public function get_amazon_efn_cost()
	{
		return number_format($this->amazon_efn_cost, 2, ".", "");
	}

	public function set_amazon_efn_cost($value)
	{
		$this->amazon_efn_cost = $value;
		return $this;
	}

	public function get_fccus_cost()
	{
		return number_format($this->fccus_cost, 2, ".", "");
	}

	public function set_fccus_cost($value)
	{
		$this->fccus_cost = $value;
		return $this;
	}

	public function get_extra_info()
	{
		return $this->extra_info;
	}

	public function set_extra_info($value)
	{
		$this->extra_info = $value;
		return $this;
	}

	public function get_platform_region_id()
	{
		return $this->platform_region_id;
	}

	public function set_platform_region_id($value)
	{
		$this->platform_region_id = $value;
		return $this;
	}

	public function get_platform_country_id()
	{
		return $this->platform_country_id;
	}

	public function set_platform_country_id($value)
	{
		$this->platform_country_id = $value;
		return $this;
	}

	public function get_language_id()
	{
		return $this->language_id;
	}

	public function set_language_id($value)
	{
		$this->language_id = $value;
		return $this;
	}

	public function get_component_order()
	{
		return $this->component_order;
	}

	public function set_component_order($value)
	{
		$this->component_order = $value;
		return $this;
	}


	public function get_with_bundle()
	{
		return $this->with_bundle;
	}

	public function set_with_bundle($value)
	{
		$this->with_bundle = $value;
		return $this;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_title($value)
	{
		$this->title = $value;
		return $this;
	}

	public function get_ext_ref_1()
	{
		return $this->ext_ref_1;
	}

	public function set_ext_ref_1($value)
	{
		$this->ext_ref_1 = $value;
		return $this;
	}

	public function get_ext_ref_2()
	{
		return $this->ext_ref_2;
	}

	public function set_ext_ref_2($value)
	{
		$this->ext_ref_2 = $value;
		return $this;
	}

	public function get_ext_ref_3()
	{
		return $this->ext_ref_3;
	}

	public function set_ext_ref_3($value)
	{
		$this->ext_ref_3 = $value;
		return $this;
	}

	public function get_ext_ref_4()
	{
		return $this->ext_ref_4;
	}

	public function set_ext_ref_4($value)
	{
		$this->ext_ref_4 = $value;
		return $this;
	}

	public function get_ext_qty()
	{
		return $this->ext_qty;
	}

	public function set_ext_qty($value)
	{
		$this->ext_qty = $value;
		return $this;
	}

	public function get_ext_item_id()
	{
		return $this->ext_item_id;
	}

	public function set_ext_item_id($value)
	{
		$this->ext_item_id = $value;
		return $this;
	}

	public function get_ext_status()
	{
		return $this->ext_status;
	}

	public function set_ext_status($value)
	{
		$this->ext_status = $value;
		return $this;
	}

	public function get_action()
	{
		return $this->action;
	}

	public function set_action($value)
	{
		$this->action = $value;
		return $this;
	}

	public function get_remark()
	{
		return $this->remark;
	}

	public function set_remark($value)
	{
		$this->remark = $value;
	}

	public function get_fulfillment_centre_id()
	{
		return $this->fulfillment_centre_id;
	}

	public function set_fulfillment_centre_id($value)
	{
		$this->fulfillment_centre_id = $value;
	}

	public function get_amazon_reprice_name()
	{
		return $this->amazon_reprice_name;
	}

	public function set_amazon_reprice_name($value)
	{
		$this->amazon_reprice_name = $value;
	}

	public function get_listing_fee()
	{
		return $this->listing_fee;
	}

	public function set_listing_fee($value)
	{
		$this->listing_fee = $value;
	}

	public function get_sub_cat_margin()
	{
		return $this->sub_cat_margin;
	}

	public function set_sub_cat_margin($value)
	{
		$this->sub_cat_margin = $value;
	}

	public function get_forex_fee_percent()
	{
		return $this->forex_fee_percent;
	}

	public function set_forex_fee_percent($value)
	{
		$this->forex_fee_percent = $value;
	}

	public function get_forex_fee()
	{
		return $this->forex_fee;
	}

	public function set_forex_fee($value)
	{
		$this->forex_fee = $value;
	}

	public function get_auto_total_charge()
	{
		return $this->auto_total_charge;
	}

	public function set_auto_total_charge($value)
	{
		$this->auto_total_charge = $value;
	}

	public function get_wms_inv()
	{
		return $this->wms_inv;
	}

	public function set_wms_inv($value)
	{
		$this->wms_inv = $value;
	}

	public function get_expected_delivery_date()
	{
		return $this->expected_delivery_date;
	}

	public function set_expected_delivery_date($value)
	{
		$this->expected_delivery_date = $value;
	}

	public function get_fixed_rrp()
	{
		return $this->fixed_rrp;
	}

	public function set_fixed_rrp($value)
	{
		$this->fixed_rrp = $value;
	}

	public function get_rrp_factor()
	{
		return $this->rrp_factor;
	}

	public function set_rrp_factor($value)
	{
		$this->rrp_factor = $value;
		return $this;
	}

	public function get_warranty_in_month()
	{
		return $this->warranty_in_month;
	}

	public function set_warranty_in_month($value)
	{
		$this->warranty_in_month = $value;
		return $this;
	}

	public function get_value_to_declare()
	{
		return $this->value_to_declare;
	}

	public function set_value_to_declare($value)
	{
		$this->value_to_declare = $value;
	}

	public function get_handling_time()
	{
		return $this->handling_time;
	}

	public function set_handling_time($value)
	{
		$this->handling_time = $value;
	}

	public function get_ship_day()
	{
		return $this->ship_day;
	}

	public function set_ship_day($value)
	{
		$this->ship_day = $value;
	}

	public function get_delivery_day()
	{
		return $this->delivery_day;
	}

	public function set_delivery_day($value)
	{
		$this->delivery_day = $value;
	}

	public function get_gsc_status()
	{
		return $this->gsc_status;
	}

	public function set_gsc_status($value)
	{
		$this->gsc_status = $value;
	}

	public function get_is_advertised()
	{
		return $this->is_advertised;
	}

	public function set_is_advertised($value)
	{
		$this->is_advertised = $value;
	}

	public function get_gsc_cm_id()
	{
		return $this->gsc_cm_id;
	}

	public function set_gsc_cm_id($value)
	{
		$this->gsc_cm_id = $value;
	}

	public function get_gsc_product_name()
	{
		return $this->gsc_product_name;
	}

	public function set_gsc_product_name($value)
	{
		$this->gsc_product_name = $value;
	}

	public function get_gsc_ext_name()
	{
		return $this->gsc_ext_name;
	}

	public function set_gsc_ext_name($value)
	{
		$this->gsc_ext_name = $value;
	}

	public function get_api_request_result()
	{
		return $this->api_request_result;
	}

	public function set_api_request_result($value)
	{
		$this->api_request_result = $value;
	}

	public function get_comment()
	{
		return $this->comment;
	}

	public function set_comment($value)
	{
		$this->comment = $value;
	}

	public function get_ad_api_request_result()
	{
		return $this->ad_api_request_result;
	}

	public function set_ad_api_request_result($value)
	{
		$this->ad_api_request_result = $value;
	}

	public function get_ad_status()
	{
		return $this->ad_status;
	}

	public function set_ad_status($value)
	{
		$this->ad_status = $value;
	}

	public function get_lang_restricted()
	{
		return $this->lang_restricted;
	}

	public function set_lang_restricted($value)
	{
		$this->lang_restricted = $value;
	}

}
/* End of file product_cost_dto.php */
/* Location: ./system/application/libraries/dto/product_cost_dto.php */