<?php
include_once 'Base_vo.php';

class Product_spec_details_vo extends Base_vo
{

	//class variable
	private $ps_id = '';
	private $cat_id;
	private $prod_sku;
	private $lang_id;
	private $cps_unit_id;
	private $text;
	private $start_value;
	private $start_standardize_value;
	private $end_value;
	private $end_standardize_value;
	private $status = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("ps_id", "cat_id", "prod_sku", "lang_id");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_ps_id()
	{
		return $this->ps_id;
	}

	public function set_ps_id($value)
	{
		$this->ps_id = $value;
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

	public function get_prod_sku()
	{
		return $this->prod_sku;
	}

	public function set_prod_sku($value)
	{
		$this->prod_sku = $value;
		return $this;
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	public function set_lang_id($value)
	{
		$this->lang_id = $value;
		return $this;
	}

	public function get_cps_unit_id()
	{
		return $this->cps_unit_id;
	}

	public function set_cps_unit_id($value)
	{
		$this->cps_unit_id = $value;
		return $this;
	}

	public function get_text()
	{
		return $this->text;
	}

	public function set_text($value)
	{
		$this->text = $value;
		return $this;
	}

	public function get_start_value()
	{
		return $this->start_value;
	}

	public function set_start_value($value)
	{
		$this->start_value = $value;
		return $this;
	}

	public function get_start_standardize_value()
	{
		return $this->start_standardize_value;
	}

	public function set_start_standardize_value($value)
	{
		$this->start_standardize_value = $value;
		return $this;
	}

	public function get_end_value()
	{
		return $this->end_value;
	}

	public function set_end_value($value)
	{
		$this->end_value = $value;
		return $this;
	}

	public function get_end_standardize_value()
	{
		return $this->end_standardize_value;
	}

	public function set_end_standardize_value($value)
	{
		$this->end_standardize_value = $value;
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