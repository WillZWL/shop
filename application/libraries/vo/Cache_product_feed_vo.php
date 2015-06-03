<?php
include_once 'Base_vo.php';

class Cache_product_feed_vo extends Base_vo
{

	//class variable
	private $sku;
	private $platform_id;
	private $prod_name;
	private $prod_url;
	private $currency_id;
	private $price = '0.00';
	private $promotion_price = '0.00';
	private $bundle_price = '0.00';
	private $shipping_cost = '0.00';
	private $promo_text;
	private $listing_status = 'N';
	private $expiry_time = '0000-00-00 00:00:00';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("sku", "platform_id");

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

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
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

	public function get_prod_url()
	{
		return $this->prod_url;
	}

	public function set_prod_url($value)
	{
		$this->prod_url = $value;
		return $this;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
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

	public function get_promotion_price()
	{
		return $this->promotion_price;
	}

	public function set_promotion_price($value)
	{
		$this->promotion_price = $value;
		return $this;
	}

	public function get_bundle_price()
	{
		return $this->bundle_price;
	}

	public function set_bundle_price($value)
	{
		$this->bundle_price = $value;
		return $this;
	}

	public function get_shipping_cost()
	{
		return $this->shipping_cost;
	}

	public function set_shipping_cost($value)
	{
		$this->shipping_cost = $value;
		return $this;
	}

	public function get_promo_text()
	{
		return $this->promo_text;
	}

	public function set_promo_text($value)
	{
		$this->promo_text = $value;
		return $this;
	}

	public function get_listing_status()
	{
		return $this->listing_status;
	}

	public function set_listing_status($value)
	{
		$this->listing_status = $value;
		return $this;
	}

	public function get_expiry_time()
	{
		return $this->expiry_time;
	}

	public function set_expiry_time($value)
	{
		$this->expiry_time = $value;
		return $this;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
		return $this;
	}

	public function get_create_at()
	{
		return $this->create_at;
	}

	public function set_create_at($value)
	{
		$this->create_at = $value;
		return $this;
	}

	public function get_create_by()
	{
		return $this->create_by;
	}

	public function set_create_by($value)
	{
		$this->create_by = $value;
		return $this;
	}

	public function get_modify_on()
	{
		return $this->modify_on;
	}

	public function set_modify_on($value)
	{
		$this->modify_on = $value;
		return $this;
	}

	public function get_modify_at()
	{
		return $this->modify_at;
	}

	public function set_modify_at($value)
	{
		$this->modify_at = $value;
		return $this;
	}

	public function get_modify_by()
	{
		return $this->modify_by;
	}

	public function set_modify_by($value)
	{
		$this->modify_by = $value;
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