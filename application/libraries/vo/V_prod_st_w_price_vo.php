<?php
include_once 'Base_vo.php';

class V_prod_st_w_price_vo extends Base_vo
{

	//class variable
	private $sku;
	private $prod_grp_cd;
	private $version_id;
	private $colour_id;
	private $prod_name;
	private $platform_id;
	private $platform_region_id;
	private $platform_country_id;
	private $shiptype = '0';
	private $shiptype_name;
	private $vat_percent = '0.00';
	private $payment_charge_percent = '0.00';
	private $declared_pcent;
	private $duty_pcent;
	private $cc_code;
	private $cc_desc;
	private $admin_fee;
	private $freight_cost = '0';
	private $delivery_cost = '0';
	private $supplier_cost;
	private $purchaser_updated_date = '0000-00-00 00:00:00';
	private $delivery_charge = '0';
	private $prod_weight;
	private $free_delivery_limit = '0';
	private $quantity = '0';
	private $clearance = '0';
	private $website_quantity = '0';
	private $proc_status = '0';
	private $website_status = 'I';
	private $sourcing_status = 'A';
	private $cat_id;
	private $sub_cat_id;
	private $sub_sub_cat_id;
	private $brand_id;
	private $image;
	private $supplier_id;
	private $freight_cat_id;
	private $ean;
	private $mpn;
	private $upc;
	private $prod_status = '1';
	private $display_quantity = '0';
	private $youtube_id;
	private $platform_default_shiptype;
	private $platform_commission;
	private $platform_currency_id;
	private $language_id = 'en';
	private $price;
	private $current_platform_price = '0.00';
	private $default_platform_converted_price;
	private $platform_code;

	//primary key
	private $primary_key = array();

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
		return $this;
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
		return $this;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
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

	public function get_shiptype()
	{
		return $this->shiptype;
	}

	public function set_shiptype($value)
	{
		$this->shiptype = $value;
		return $this;
	}

	public function get_shiptype_name()
	{
		return $this->shiptype_name;
	}

	public function set_shiptype_name($value)
	{
		$this->shiptype_name = $value;
		return $this;
	}

	public function get_vat_percent()
	{
		return $this->vat_percent;
	}

	public function set_vat_percent($value)
	{
		$this->vat_percent = $value;
		return $this;
	}

	public function get_payment_charge_percent()
	{
		return $this->payment_charge_percent;
	}

	public function set_payment_charge_percent($value)
	{
		$this->payment_charge_percent = $value;
		return $this;
	}

	public function get_declared_pcent()
	{
		return $this->declared_pcent;
	}

	public function set_declared_pcent($value)
	{
		$this->declared_pcent = $value;
		return $this;
	}

	public function get_duty_pcent()
	{
		return $this->duty_pcent;
	}

	public function set_duty_pcent($value)
	{
		$this->duty_pcent = $value;
		return $this;
	}

	public function get_cc_code()
	{
		return $this->cc_code;
	}

	public function set_cc_code($value)
	{
		$this->cc_code = $value;
		return $this;
	}

	public function get_cc_desc()
	{
		return $this->cc_desc;
	}

	public function set_cc_desc($value)
	{
		$this->cc_desc = $value;
		return $this;
	}

	public function get_admin_fee()
	{
		return $this->admin_fee;
	}

	public function set_admin_fee($value)
	{
		$this->admin_fee = $value;
		return $this;
	}

	public function get_freight_cost()
	{
		return $this->freight_cost;
	}

	public function set_freight_cost($value)
	{
		$this->freight_cost = $value;
		return $this;
	}

	public function get_delivery_cost()
	{
		return $this->delivery_cost;
	}

	public function set_delivery_cost($value)
	{
		$this->delivery_cost = $value;
		return $this;
	}

	public function get_supplier_cost()
	{
		return $this->supplier_cost;
	}

	public function set_supplier_cost($value)
	{
		$this->supplier_cost = $value;
		return $this;
	}

	public function get_purchaser_updated_date()
	{
		return $this->purchaser_updated_date;
	}

	public function set_purchaser_updated_date($value)
	{
		$this->purchaser_updated_date = $value;
		return $this;
	}

	public function get_delivery_charge()
	{
		return $this->delivery_charge;
	}

	public function set_delivery_charge($value)
	{
		$this->delivery_charge = $value;
		return $this;
	}

	public function get_prod_weight()
	{
		return $this->prod_weight;
	}

	public function set_prod_weight($value)
	{
		$this->prod_weight = $value;
		return $this;
	}

	public function get_free_delivery_limit()
	{
		return $this->free_delivery_limit;
	}

	public function set_free_delivery_limit($value)
	{
		$this->free_delivery_limit = $value;
		return $this;
	}

	public function get_quantity()
	{
		return $this->quantity;
	}

	public function set_quantity($value)
	{
		$this->quantity = $value;
		return $this;
	}

	public function get_clearance()
	{
		return $this->clearance;
	}

	public function set_clearance($value)
	{
		$this->clearance = $value;
		return $this;
	}

	public function get_website_quantity()
	{
		return $this->website_quantity;
	}

	public function set_website_quantity($value)
	{
		$this->website_quantity = $value;
		return $this;
	}

	public function get_proc_status()
	{
		return $this->proc_status;
	}

	public function set_proc_status($value)
	{
		$this->proc_status = $value;
		return $this;
	}

	public function get_website_status()
	{
		return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
		return $this;
	}

	public function get_sourcing_status()
	{
		return $this->sourcing_status;
	}

	public function set_sourcing_status($value)
	{
		$this->sourcing_status = $value;
		return $this;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
		return $this;
	}

	public function get_sub_cat_id()
	{
		return $this->sub_cat_id;
	}

	public function set_sub_cat_id($value)
	{
		$this->sub_cat_id = $value;
		return $this;
	}

	public function get_sub_sub_cat_id()
	{
		return $this->sub_sub_cat_id;
	}

	public function set_sub_sub_cat_id($value)
	{
		$this->sub_sub_cat_id = $value;
		return $this;
	}

	public function get_brand_id()
	{
		return $this->brand_id;
	}

	public function set_brand_id($value)
	{
		$this->brand_id = $value;
		return $this;
	}

	public function get_image()
	{
		return $this->image;
	}

	public function set_image($value)
	{
		$this->image = $value;
		return $this;
	}

	public function get_supplier_id()
	{
		return $this->supplier_id;
	}

	public function set_supplier_id($value)
	{
		$this->supplier_id = $value;
		return $this;
	}

	public function get_freight_cat_id()
	{
		return $this->freight_cat_id;
	}

	public function set_freight_cat_id($value)
	{
		$this->freight_cat_id = $value;
		return $this;
	}

	public function get_ean()
	{
		return $this->ean;
	}

	public function set_ean($value)
	{
		$this->ean = $value;
		return $this;
	}

	public function get_mpn()
	{
		return $this->mpn;
	}

	public function set_mpn($value)
	{
		$this->mpn = $value;
		return $this;
	}

	public function get_upc()
	{
		return $this->upc;
	}

	public function set_upc($value)
	{
		$this->upc = $value;
		return $this;
	}

	public function get_prod_status()
	{
		return $this->prod_status;
	}

	public function set_prod_status($value)
	{
		$this->prod_status = $value;
		return $this;
	}

	public function get_display_quantity()
	{
		return $this->display_quantity;
	}

	public function set_display_quantity($value)
	{
		$this->display_quantity = $value;
		return $this;
	}

	public function get_youtube_id()
	{
		return $this->youtube_id;
	}

	public function set_youtube_id($value)
	{
		$this->youtube_id = $value;
		return $this;
	}

	public function get_platform_default_shiptype()
	{
		return $this->platform_default_shiptype;
	}

	public function set_platform_default_shiptype($value)
	{
		$this->platform_default_shiptype = $value;
		return $this;
	}

	public function get_platform_commission()
	{
		return $this->platform_commission;
	}

	public function set_platform_commission($value)
	{
		$this->platform_commission = $value;
		return $this;
	}

	public function get_platform_currency_id()
	{
		return $this->platform_currency_id;
	}

	public function set_platform_currency_id($value)
	{
		$this->platform_currency_id = $value;
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

	public function get_price()
	{
		return $this->price;
	}

	public function set_price($value)
	{
		$this->price = $value;
		return $this;
	}

	public function get_current_platform_price()
	{
		return $this->current_platform_price;
	}

	public function set_current_platform_price($value)
	{
		$this->current_platform_price = $value;
		return $this;
	}

	public function get_default_platform_converted_price()
	{
		return $this->default_platform_converted_price;
	}

	public function set_default_platform_converted_price($value)
	{
		$this->default_platform_converted_price = $value;
		return $this;
	}

	public function get_platform_code()
	{
		return $this->platform_code;
	}

	public function set_platform_code($value)
	{
		$this->platform_code = $value;
		return $this;
	}

	public function _get_primary_key()
	{
		return $this->primary_key;
	}

	public function _get_increment_field()
	{
		return $this->increment_field;
	}

}
?>