<?php
include_once 'Base_vo.php';

class Flex_pmgw_transactions_vo extends Base_vo
{

	//class variable
	private $so_no;
	private $payment_gateway_id;
	private $txn_id;
	private $payment_type;
	private $txn_time;
	private $currency_id;
	private $amount;
	private $commission;
	private $ext_ref;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//primary key
	private $primary_key = array("so_no", "payment_type");

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

	public function get_payment_gateway_id()
	{
		return $this->payment_gateway_id;
	}

	public function set_payment_gateway_id($value)
	{
		$this->payment_gateway_id = $value;
		return $this;
	}

	public function get_txn_id()
	{
		return $this->txn_id;
	}

	public function set_txn_id($value)
	{
		$this->txn_id = $value;
		return $this;
	}

	public function get_payment_type()
	{
		return $this->payment_type;
	}

	public function set_payment_type($value)
	{
		$this->payment_type = $value;
		return $this;
	}

	public function get_txn_time()
	{
		return $this->txn_time;
	}

	public function set_txn_time($value)
	{
		$this->txn_time = $value;
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

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
		return $this;
	}

	public function get_commission()
	{
		return $this->commission;
	}

	public function set_commission($value)
	{
		$this->commission = $value;
		return $this;
	}

	public function get_ext_ref()
	{
		return $this->ext_ref;
	}

	public function set_ext_ref($value)
	{
		$this->ext_ref = $value;
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