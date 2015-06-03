<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Rolling_reserve_report_dto extends Base_dto
{
	private $so_no;
	private $batch_id;
	private $gateway_id;
	private $txn_id;
	private $txn_date;
	private $currency_id;
	private $order_amount;
	private $amount;
	private $percentage;
	private $status;
	private $hold_time;

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

	public function get_batch_id()
	{
		return $this->batch_id;
	}

	public function set_batch_id($value)
	{
		$this->batch_id = $value;
	}

	public function get_gateway_id()
	{
		return $this->gateway_id;
	}

	public function set_gateway_id($value)
	{
		$this->gateway_id = $value;
	}

	public function get_txn_id()
	{
		return $this->txn_id;
	}

	public function set_txn_id($value)
	{
		$this->txn_id = $value;
	}

	public function get_txn_date()
	{
		return $this->txn_date;
	}

	public function set_txn_date($value)
	{
		$this->txn_date = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_order_amount()
	{
		return $this->order_amount;
	}

	public function set_order_amount($value)
	{
		$this->order_amount = $value;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
	}

	public function get_percentage()
	{
		return $this->percentage;
	}

	public function set_percentage($value)
	{
		$this->percentage = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_hold_time()
	{
		return $this->hold_time;
	}

	public function set_hold_time($value)
	{
		$this->hold_time = $value;
	}
}

/* End of file so_fee_invoice_dto.php */
/* Location: ./system/application/libraries/dto/so_fee_invoice_dto.php */