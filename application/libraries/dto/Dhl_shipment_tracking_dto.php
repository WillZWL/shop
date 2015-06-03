<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Dhl_shipment_tracking_dto extends Base_dto {

	//class variable
	private $sh_no;
	private $tracking_no;
	private $delivery_name;
	private $delivery_address;
	private $delivery_city;
	private $delivery_postcode;
	private $delivery_country_id;
	private $cc_desc;
	private $amount;
	private $so_no;
	private $currency_id;

	private $acount_number;
	private $customer_ref;
	private $product_code;
	private $delivery_state;

	//instance method
	public function get_sh_no()
	{
		return $this->sh_no;
	}

	public function set_sh_no($value)
	{
		$this->sh_no = $value;
	}

	public function get_tracking_no()
	{
		return $this->tracking_no;
	}

	public function set_tracking_no($value)
	{
		$this->tracking_no = $value;
	}

	public function get_delivery_name()
	{
		return $this->delivery_name;
	}

	public function set_delivery_name($value)
	{
		$this->delivery_name = $value;
	}

	public function get_delivery_address()
	{
		return $this->delivery_address;
	}

	public function set_delivery_address($value)
	{
		$this->delivery_address = $value;
	}

	public function get_delivery_city()
	{
		return $this->delivery_city;
	}

	public function set_delivery_city($value)
	{
		$this->delivery_city = $value;
	}

	public function get_delivery_postcode()
	{
		return $this->delivery_postcode;
	}

	public function set_delivery_postcode($value)
	{
		$this->delivery_postcode = $value;
	}

	public function get_delivery_country_id()
	{
		return $this->delivery_country_id;
	}

	public function set_delivery_country_id($value)
	{
		$this->delivery_country_id = $value;
	}

	public function get_cc_desc()
	{
		return $this->cc_desc;
	}

	public function set_cc_desc($value)
	{
		$this->cc_desc = $value;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_acount_number()
	{
		return $this->acount_number;
	}

	public function set_acount_number($value)
	{
		$this->acount_number = $value;
	}

	public function get_customer_ref()
	{
		return $this->customer_ref;
	}

	public function set_customer_ref($value)
	{
		$this->customer_ref = $value;
	}

	public function get_product_code()
	{
		return $this->product_code;
	}

	public function set_product_code($value)
	{
		$this->product_code = $value;
	}

	public function get_delivery_state()
	{
		return $this->delivery_state;
	}

	public function set_delivery_state($value)
	{
		$this->delivery_state = $value;
	}




}

/* End of file gen_dhl_shipment_tracking_feed.php */
/* Location: ./system/application/libraries/dto/gen_dhl_shipment_tracking_feed.php */