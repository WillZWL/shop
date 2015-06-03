<?php
include_once 'Base_vo.php';

class Wms_warehouse_vo extends Base_vo
{

	//class variable
	private $type;
	private $warehouse_id;
	private $status;
	private $modify_on;

	//primary key
	private $primary_key = array();

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
		$this->type = $value;
		return $this;
	}

	public function get_warehouse_id()
	{
		return $this->warehouse_id;
	}

	public function set_warehouse_id($value)
	{
		$this->warehouse_id = $value;
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

	public function get_modify_on()
	{
		return $this->modify_on;
	}

	public function set_modify_on($value)
	{
		$this->modify_on = $value;
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