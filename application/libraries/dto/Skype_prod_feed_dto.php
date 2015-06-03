<?php

include_once "Base_dto.php";

class Skype_prod_feed_dto extends Base_dto
{
	private $sku;
	private $name;
	private $price;
	private $qty;
	private $in_stock;
	private $delivery_cost;

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

	public function get_price()
	{
		return $this->price;
	}

	public function set_price($value)
	{
		$this->price = $value;
	}

	public function get_qty()
	{
		return $this->qty;
	}

	public function set_qty($value)
	{
		$this->qty = $value;
	}

	public function get_in_stock()
	{
		return $this->in_stock;
	}

	public function set_in_stock($value)
	{
		$this->in_stock = $value;
	}

	public function get_delivery_cost()
	{
		return $this->delivery_cost;
	}

	public function set_delivery_cost($value)
	{
		$this->delivery_cost = $value;
	}
}