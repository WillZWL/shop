<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Festive_item_dto extends Base_dto
{
	private $sku;
	private $website_quantity;
	private $website_status;
	private $price;
	private $prod_name;
	private $quantity;
	private $order;
	private $image_file_ext;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_order()
	{
		return $this->order;
	}

	public function set_order($value)
	{
		$this->order = $value;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}
	public function get_quantity()
	{
		return $this->quantity;
	}

	public function set_quantity($value)
	{
		$this->quantity = $value;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function set_price($value)
	{
		$this->price = $value;
	}

	public function get_website_status()
	{
		return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
	}

	public function get_website_quantity()
	{
		return $this->website_quantity;
	}

	public function set_website_quantity($value)
	{
		$this->website_quantity = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_image_file_ext()
	{
		return $this->image_file_ext;
	}

	public function set_image_file_ext($value)
	{
		$this->image_file_ext = $value;
	}
}
/* End of file base_dto.php */
/* Location: ./system/application/libraries/dto/base_dto.php */
