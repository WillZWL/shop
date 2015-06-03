<?php
include_once 'Base_vo.php';

class Sub_cat_platform_var_vo extends Base_vo
{

	//class variable
	private $sub_cat_id;
	private $platform_id;
	private $currency_id;
	private $platform_commission;
	private $dlvry_chrg;
	private $custom_class_id;
	private $fixed_fee = '0.00';
	private $profit_margin = '0.00';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//primary key
	private $primary_key = array("sub_cat_id", "platform_id");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_sub_cat_id()
	{
		return $this->sub_cat_id;
	}

	public function set_sub_cat_id($value)
	{
		$this->sub_cat_id = $value;
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

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
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

	public function get_dlvry_chrg()
	{
		return $this->dlvry_chrg;
	}

	public function set_dlvry_chrg($value)
	{
		$this->dlvry_chrg = $value;
		return $this;
	}

	public function get_custom_class_id()
	{
		return $this->custom_class_id;
	}

	public function set_custom_class_id($value)
	{
		$this->custom_class_id = $value;
		return $this;
	}

	public function get_fixed_fee()
	{
		return $this->fixed_fee;
	}

	public function set_fixed_fee($value)
	{
		$this->fixed_fee = $value;
		return $this;
	}

	public function get_profit_margin()
	{
		return $this->profit_margin;
	}

	public function set_profit_margin($value)
	{
		$this->profit_margin = $value;
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