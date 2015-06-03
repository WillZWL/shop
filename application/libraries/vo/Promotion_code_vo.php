<?php
include_once 'Base_vo.php';

class Promotion_code_vo extends Base_vo
{

	//class variable
	private $code;
	private $description;
	private $disc_type;
	private $over_amount;
	private $over_amount_1;
	private $discount_1;
	private $over_amount_2;
	private $discount_2;
	private $over_amount_3;
	private $discount_3;
	private $over_amount_4;
	private $discount_4;
	private $over_amount_5;
	private $discount_5;
	private $region_id;
	private $country_id;
	private $currency_id;
	private $free_item_sku;
	private $cat_id;
	private $sub_cat_id;
	private $sub_sub_cat_id;
	private $brand_id;
	private $relevant_prod;
	private $email;
	private $disc_level = 'ALL';
	private $disc_level_value;
	private $expire_date;
	private $redemption = '-1';
	private $total_redemption = '-1';
	private $no_taken = '0';
	private $status = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("code");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_code()
	{
		return $this->code;
	}

	public function set_code($value)
	{
		$this->code = $value;
		return $this;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
		return $this;
	}

	public function get_disc_type()
	{
		return $this->disc_type;
	}

	public function set_disc_type($value)
	{
		$this->disc_type = $value;
		return $this;
	}

	public function get_over_amount()
	{
		return $this->over_amount;
	}

	public function set_over_amount($value)
	{
		$this->over_amount = $value;
		return $this;
	}

	public function get_over_amount_1()
	{
		return $this->over_amount_1;
	}

	public function set_over_amount_1($value)
	{
		$this->over_amount_1 = $value;
		return $this;
	}

	public function get_discount_1()
	{
		return $this->discount_1;
	}

	public function set_discount_1($value)
	{
		$this->discount_1 = $value;
		return $this;
	}

	public function get_over_amount_2()
	{
		return $this->over_amount_2;
	}

	public function set_over_amount_2($value)
	{
		$this->over_amount_2 = $value;
		return $this;
	}

	public function get_discount_2()
	{
		return $this->discount_2;
	}

	public function set_discount_2($value)
	{
		$this->discount_2 = $value;
		return $this;
	}

	public function get_over_amount_3()
	{
		return $this->over_amount_3;
	}

	public function set_over_amount_3($value)
	{
		$this->over_amount_3 = $value;
		return $this;
	}

	public function get_discount_3()
	{
		return $this->discount_3;
	}

	public function set_discount_3($value)
	{
		$this->discount_3 = $value;
		return $this;
	}

	public function get_over_amount_4()
	{
		return $this->over_amount_4;
	}

	public function set_over_amount_4($value)
	{
		$this->over_amount_4 = $value;
		return $this;
	}

	public function get_discount_4()
	{
		return $this->discount_4;
	}

	public function set_discount_4($value)
	{
		$this->discount_4 = $value;
		return $this;
	}

	public function get_over_amount_5()
	{
		return $this->over_amount_5;
	}

	public function set_over_amount_5($value)
	{
		$this->over_amount_5 = $value;
		return $this;
	}

	public function get_discount_5()
	{
		return $this->discount_5;
	}

	public function set_discount_5($value)
	{
		$this->discount_5 = $value;
		return $this;
	}

	public function get_region_id()
	{
		return $this->region_id;
	}

	public function set_region_id($value)
	{
		$this->region_id = $value;
		return $this;
	}

	public function get_country_id()
	{
		return $this->country_id;
	}

	public function set_country_id($value)
	{
		$this->country_id = $value;
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

	public function get_free_item_sku()
	{
		return $this->free_item_sku;
	}

	public function set_free_item_sku($value)
	{
		$this->free_item_sku = $value;
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

	public function get_relevant_prod()
	{
		return $this->relevant_prod;
	}

	public function set_relevant_prod($value)
	{
		$this->relevant_prod = $value;
		return $this;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function set_email($value)
	{
		$this->email = $value;
		return $this;
	}

	public function get_disc_level()
	{
		return $this->disc_level;
	}

	public function set_disc_level($value)
	{
		$this->disc_level = $value;
		return $this;
	}

	public function get_disc_level_value()
	{
		return $this->disc_level_value;
	}

	public function set_disc_level_value($value)
	{
		$this->disc_level_value = $value;
		return $this;
	}

	public function get_expire_date()
	{
		return $this->expire_date;
	}

	public function set_expire_date($value)
	{
		$this->expire_date = $value;
		return $this;
	}

	public function get_redemption()
	{
		return $this->redemption;
	}

	public function set_redemption($value)
	{
		$this->redemption = $value;
		return $this;
	}

	public function get_total_redemption()
	{
		return $this->total_redemption;
	}

	public function set_total_redemption($value)
	{
		$this->total_redemption = $value;
		return $this;
	}

	public function get_no_taken()
	{
		return $this->no_taken;
	}

	public function set_no_taken($value)
	{
		$this->no_taken = $value;
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