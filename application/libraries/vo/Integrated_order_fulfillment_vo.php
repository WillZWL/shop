<?php
include_once "base_vo.php";

class Integrated_order_fulfillment_vo extends Base_vo
{

	public function __construct()
	{
		parent::Base_vo();
	}

	//class variable
	private $so_no;
	private $line_no;
	private $sku = '';
	private $platform_id;
	private $platform_order_id;
	private $order_create_date;
	private $expect_delivery_date;
	private $product_name;
	private $website_status;
	private $delivery_name;
	private $delivery_country_id;
	private $delivery_type_id;
	private $payment_gateway_id;
	private $note;
	private $amount;
	private $refund_status = '0';
	private $hold_status = '0';
	private $qty;
	private $outstanding_qty;
	private $status = '1';
	private $delivery_postcode;
	private $rec_courier;
	private $split_so_group;

	//primary key
	private $primary_key = array("so_no", "line_no", "sku");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
		return $this;
	}

	public function get_line_no()
	{
		return $this->line_no;
	}

	public function set_line_no($value)
	{
		$this->line_no = $value;
		return $this;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
		return $this;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
		return $this;
	}

	public function get_platform_order_id()
	{
		return $this->platform_order_id;
	}

	public function set_platform_order_id($value)
	{
		$this->platform_order_id = $value;
		return $this;
	}

	public function get_order_create_date()
	{
		return $this->order_create_date;
	}

	public function set_order_create_date($value)
	{
		$this->order_create_date = $value;
		return $this;
	}

	public function get_expect_delivery_date()
	{
		return $this->expect_delivery_date;
	}

	public function set_expect_delivery_date($value)
	{
		$this->expect_delivery_date = $value;
		return $this;
	}

	public function get_product_name()
	{
		return $this->product_name;
	}

	public function set_product_name($value)
	{
		$this->product_name = $value;
		return $this;
	}

	public function get_website_status()
	{
		return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
		return $this;
	}

	public function get_delivery_name()
	{
		return $this->delivery_name;
	}

	public function set_delivery_name($value)
	{
		$this->delivery_name = $value;
		return $this;
	}

	public function get_delivery_country_id()
	{
		return $this->delivery_country_id;
	}

	public function set_delivery_country_id($value)
	{
		$this->delivery_country_id = $value;
		return $this;
	}

	public function get_delivery_type_id()
	{
		return $this->delivery_type_id;
	}

	public function set_delivery_type_id($value)
	{
		$this->delivery_type_id = $value;
		return $this;
	}

	public function get_payment_gateway_id()
	{
		return $this->payment_gateway_id;
	}

	public function set_payment_gateway_id($value)
	{
		$this->payment_gateway_id = $value;
		return $this;
	}

	public function get_note()
	{
		return $this->note;
	}

	public function set_note($value)
	{
		$this->note = $value;
		return $this;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
		return $this;
	}

	public function get_refund_status()
	{
		return $this->refund_status;
	}

	public function set_refund_status($value)
	{
		$this->refund_status = $value;
		return $this;
	}

	public function get_hold_status()
	{
		return $this->hold_status;
	}

	public function set_hold_status($value)
	{
		$this->hold_status = $value;
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

	public function get_outstanding_qty()
	{
		return $this->outstanding_qty;
	}

	public function set_outstanding_qty($value)
	{
		$this->outstanding_qty = $value;
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

	public function get_delivery_postcode()
	{
		return $this->delivery_postcode;
	}

	public function set_delivery_postcode($value)
	{
		$this->delivery_postcode = $value;
		return $this;
	}

	public function get_rec_courier()
	{
		return $this->rec_courier;
	}

	public function set_rec_courier($value)
	{
		$this->rec_courier = $value;
		return $this;
	}

	public function get_split_so_group()
	{
		return $this->split_so_group;
	}

	public function set_split_so_group($value)
	{
		$this->split_so_group = $value;
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

/* End of file integrated_order_fulfillment_vo.php */
/* Location: ./app/libraries/vo/integrated_order_fulfillment_vo.php */