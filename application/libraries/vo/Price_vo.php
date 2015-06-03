<?php
include_once 'Base_vo.php';

class Price_vo extends Base_vo
{

	//class variable
	private $sku;
	private $platform_id;
	private $default_shiptype = '0';
	private $price = '0.00';
	private $status;
	private $allow_express = 'N';
	private $is_advertised = 'N';
	private $ext_mapping_code;
	private $latency = '0';
	private $oos_latency;
	private $listing_status = 'N';
	private $platform_code;
	private $max_order_qty = '100';
	private $auto_price = 'N';
	private $fixed_rrp = 'Y';
	private $rrp_factor;
	private $delivery_scenarioid = '1';
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

	public function get_default_shiptype()
	{
		return $this->default_shiptype;
	}

	public function set_default_shiptype($value)
	{
		$this->default_shiptype = $value;
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

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
		return $this;
	}

	public function get_allow_express()
	{
		return $this->allow_express;
	}

	public function set_allow_express($value)
	{
		$this->allow_express = $value;
		return $this;
	}

	public function get_is_advertised()
	{
		return $this->is_advertised;
	}

	public function set_is_advertised($value)
	{
		$this->is_advertised = $value;
		return $this;
	}

	public function get_ext_mapping_code()
	{
		return $this->ext_mapping_code;
	}

	public function set_ext_mapping_code($value)
	{
		$this->ext_mapping_code = $value;
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

	public function get_oos_latency()
	{
		return $this->oos_latency;
	}

	public function set_oos_latency($value)
	{
		$this->oos_latency = $value;
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

	public function get_platform_code()
	{
		return $this->platform_code;
	}

	public function set_platform_code($value)
	{
		$this->platform_code = $value;
		return $this;
	}

	public function get_max_order_qty()
	{
		return $this->max_order_qty;
	}

	public function set_max_order_qty($value)
	{
		$this->max_order_qty = $value;
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

	public function get_fixed_rrp()
	{
		return $this->fixed_rrp;
	}

	public function set_fixed_rrp($value)
	{
		$this->fixed_rrp = $value;
		return $this;
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

	public function get_delivery_scenarioid()
	{
		return $this->delivery_scenarioid;
	}

	public function set_delivery_scenarioid($value)
	{
		$this->delivery_scenarioid = $value;
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