<?php
include_once 'Base_dto.php';

class Preorder_list_dto extends Base_dto
{
	private $so_no;
	private $prod_sku;
	private $prod_name;
	private $qty;
	private $expect_delivery_date;
	private $create_on;
	private $current_expected_delivery_date;
	private $multiple_items_count;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_prod_sku()
	{
		return $this->prod_sku;
	}

	public function set_prod_sku($value)
	{
		$this->prod_sku = $value;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}

	public function get_qty()
	{
		return $this->qty;
	}

	public function set_qty($value)
	{
		$this->qty = $value;
	}

	public function get_expect_delivery_date()
	{
		return $this->expect_delivery_date;
	}

	public function set_expect_delivery_date($value)
	{
		$this->expect_delivery_date = $value;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
	}

	public function get_current_expected_delivery_date()
	{
		return $this->current_expected_delivery_date;
	}

	public function set_current_expected_delivery_date($value)
	{
		$this->current_expected_delivery_date = $value;
	}

	public function get_multiple_items_count()
	{
		return $this->multiple_items_count;
	}

	public function set_multiple_items_count($value)
	{
		$this->multiple_items_count = $value;
	}
}
?>