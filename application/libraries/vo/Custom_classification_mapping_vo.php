<?php
include_once 'Base_vo.php';

class Custom_classification_mapping_vo extends Base_vo
{

	//class variable
	private $sub_cat_id;
	private $country_id;
	private $custom_class_id;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//primary key
	private $primary_key = array("sub_cat_id", "country_id");

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

	public function get_country_id()
	{
		return $this->country_id;
	}

	public function set_country_id($value)
	{
		$this->country_id = $value;
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