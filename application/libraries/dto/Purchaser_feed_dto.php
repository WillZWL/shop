<?php
include_once 'Base_dto.php';

class Purchaser_feed_dto extends Base_dto
{

	//class variable
	private $so_no;
	private $item_sku;
	private $qty;
	private $currency_id;
	private $email;
	private $delivery_postcode;
	private $title;
	private $forename;
	private $surname;
	private $price;
	private $order_create_date;
	private $client_id;
	private $delivery_country_id;

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

	public function get_item_sku()
	{
		return $this->item_sku;
	}

	public function set_item_sku($value)
	{
		$this->item_sku = $value;
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

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
		return $this;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function set_email($value)
	{
		$this->email = $value;
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

	public function get_title()
	{
		return $this->title==""?"Mr":$this->title;
	}

	public function set_title($value)
	{
		$this->title = $value;
		return $this;
	}

	public function get_forename()
	{
		return $this->forename;
	}

	public function set_forename($value)
	{
		$this->forename = $value;
		return $this;
	}

	public function get_surname()
	{
		return $this->surname;
	}

	public function set_surname($value)
	{
		$this->surname = $value;
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

	public function get_order_create_date()
	{
		return $this->order_create_date;
	}

	public function set_order_create_date($value)
	{
		$this->order_create_date = $value;
		return $this;
	}

	public function get_client_id()
	{
		return $this->client_id;
	}

	public function set_client_id($value)
	{
		$this->client_id = $value;
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

}

/* End of file lstrans_dto.php */
/* Location: ./system/application/libraries/dto/lstrans_dto.php */