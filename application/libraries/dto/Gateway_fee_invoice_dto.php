<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Gateway_fee_invoice_dto extends Base_dto
{
	private $type;
	private $txn_time;
	private $from_currency;
	private $from_amount;
	private $gateway_id;
	private $batch_id;
	private $to_currency;
	private $to_amount;
	private $difference;
	private $percentage;
	private $txn_ref;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
		$this->type = $value;
	}

	public function get_txn_time()
	{
		return $this->txn_time;
	}

	public function set_txn_time($value)
	{
		$this->txn_time = $value;
	}

	public function get_from_currency()
	{
		return $this->from_currency;
	}

	public function set_from_currency($value)
	{
		$this->from_currency = $value;
	}

	public function get_from_amount()
	{
		return $this->from_amount;
	}

	public function set_from_amount($value)
	{
		$this->from_amount = $value;
	}

	public function get_gateway_id()
	{
		return $this->gateway_id;
	}

	public function set_gateway_id($value)
	{
		$this->gateway_id = $value;
	}

	public function get_batch_id()
	{
		return $this->batch_id;
	}

	public function set_batch_id($value)
	{
		$this->batch_id = $value;
	}

	public function get_to_currency()
	{
		return $this->to_currency;
	}

	public function set_to_currency($value)
	{
		$this->to_currency = $value;
	}

	public function get_to_amount()
	{
		return $this->to_amount;
	}

	public function set_to_amount($value)
	{
		$this->to_amount = $value;
	}

	public function get_difference()
	{
		return $this->difference;
	}

	public function set_difference($value)
	{
		$this->difference = $value;
	}

	public function get_percentage()
	{
		return $this->percentage;
	}

	public function set_percentage($value)
	{
		$this->percentage = $value;
	}

	public function get_txn_ref()
	{
		return $this->txn_ref;
	}

	public function set_txn_ref($value)
	{
		$this->txn_ref = $value;
	}
}

/* End of file so_fee_invoice_dto.php */
/* Location: ./system/application/libraries/dto/so_fee_invoice_dto.php */