<?php
include_once 'Base_vo.php';

class V_bundle_vo extends Base_vo
{

	//class variable
	private $bundle_sku;
	private $components;
	private $bundle_name;

	//primary key
	private $primary_key = array();

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_bundle_sku()
	{
		return $this->bundle_sku;
	}

	public function set_bundle_sku($value)
	{
		$this->bundle_sku = $value;
		return $this;
	}

	public function get_components()
	{
		return $this->components;
	}

	public function set_components($value)
	{
		$this->components = $value;
		return $this;
	}

	public function get_bundle_name()
	{
		return $this->bundle_name;
	}

	public function set_bundle_name($value)
	{
		$this->bundle_name = $value;
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