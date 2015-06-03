<?php
include_once 'Base_vo.php';

class Display_qty_class_vo extends Base_vo
{

	//class variable
	private $id;
	private $price;
	private $price2;
	private $qty;
	private $qty2;
	private $drop_qty = '0';
	private $default_factor = '1.00';
	private $status = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//primary key
	private $primary_key = array("id");

	//auo increment
	private $increment_field = "id";

	//instance method
	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
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

	public function get_price2()
	{
		return $this->price2;
	}

	public function set_price2($value)
	{
		$this->price2 = $value;
		return $this;
	}

	public function get_qty()
	{
		return $this->qty;
	}

	public function set_qty($value)
	{
		$this->qty = $value;
		return $this;
	}

	public function get_qty2()
	{
		return $this->qty2;
	}

	public function set_qty2($value)
	{
		$this->qty2 = $value;
		return $this;
	}

	public function get_drop_qty()
	{
		return $this->drop_qty;
	}

	public function set_drop_qty($value)
	{
		$this->drop_qty = $value;
		return $this;
	}

	public function get_default_factor()
	{
		return $this->default_factor;
	}

	public function set_default_factor($value)
	{
		$this->default_factor = $value;
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