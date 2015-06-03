<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Po_item_prodname_dto extends Base_dto
{
	//instance variable
	private $line_number;
	private $sku;
	private $name;
	private $order_qty;
	private $shipped_qty;
	private $unit_price;
	private $status;

	//instance method
	public function get_line_number()
	{
		return $this->line_number;
	}

	public function set_line_number($value)
	{
		$this->line_number = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_order_qty()
	{
		return $this->order_qty;
	}

	public function set_order_qty($value)
	{
		$this->order_qty = $value;
	}

	public function get_shipped_qty()
	{
		return $this->shipped_qty;
	}

	public function set_shipped_qty($value)
	{
		$this->shipped_qty = $value;
	}

	public function get_unit_price()
	{
		return $this->unit_price;
	}

	public function set_unit_price($value)
	{
		$this->unit_price = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
	}

	public function get_create_at()
	{
		return $this->create_at;
	}

	public function set_create_at($value)
	{
		$this->create_at = $value;
	}

	public function get_create_by()
	{
		return $this->create_by;
	}

	public function set_create_by($value)
	{
		$this->create_by = $value;
	}

	public function get_modify_on()
	{
		return $this->modify_on;
	}

	public function set_modify_on($value)
	{
		$this->modify_on = $value;
	}

}

?>