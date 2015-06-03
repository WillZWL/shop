<?php
include_once 'Base_dto.php';

class Lstrans_dto extends Base_dto
{

	//class variable
	private $id;
	private $so_no;
	private $item_sku;
	private $qty;
	private $amount;
	private $currency_id;
	private $payment = 'NA';
	private $is_sent;
	private $conv_site_ref;
	private $ls_time_entered;
	private $pay_date;
	private $email;
	private $delivery_postcode;
	private $prod_name;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

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

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
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

	public function get_payment()
	{
		return $this->payment;
	}

	public function set_payment($value)
	{
		$this->payment = $value;
		return $this;
	}

	public function get_is_sent()
	{
		return $this->is_sent;
	}

	public function set_is_sent($value)
	{
		$this->is_sent = $value;
		return $this;
	}

	public function get_conv_site_ref()
	{
		return $this->conv_site_ref;
	}

	public function set_conv_site_ref($value)
	{
		$this->conv_site_ref = $value;
		return $this;
	}

	public function get_ls_time_entered()
	{
		return $this->ls_time_entered;
	}

	public function set_ls_time_entered($value)
	{
		$this->ls_time_entered = $value;
		return $this;
	}

	public function get_pay_date()
	{
		return str_replace(" ", "/", $this->pay_date);
	}

	public function set_pay_date($value)
	{
		$this->pay_date = $value;
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

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
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

}

/* End of file lstrans_dto.php */
/* Location: ./system/application/libraries/dto/lstrans_dto.php */