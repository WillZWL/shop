<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pmgw_report_paypal_au_dto extends Base_dto
{
	//class variable
	private $date;
	private $time;
	private $type;
	private $status;
	private $currency_id;
	private $amount;
	private $commission;
	private $net;
	private $from_email_address;
	private $txn_id = "";
	private $interal_txn_id;
	private $ref_txn_id;
	private $so_no;

	public function get_date()
	{
		return $this->date;
	}

	public function set_date($value)
	{
		$this->date = $value;
	}

	public function get_time()
	{
		return $this->time;
	}

	public function set_time($value)
	{
		$this->time = $value;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
		$this->type = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
	}

	public function get_commission()
	{
		return $this->commission;
	}

	public function set_commission($value)
	{
		$this->commission = $value;
	}

	public function get_net()
	{
		return $this->net;
	}

	public function set_net($value)
	{
		$this->net = $value;
	}

	public function get_from_email_address()
	{
		return $this->from_email_address;
	}

	public function set_from_email_address($value)
	{
		$this->from_email_address = $value;
	}

	public function get_txn_id()
	{
		return $this->txn_id;
	}

	public function set_txn_id($value)
	{
		$this->txn_id = $value;
	}

	public function get_internal_txn_id()
	{
		return $this->internal_txn_id;
	}

	public function set_internal_txn_id($value)
	{
		$this->internal_txn_id = $value;
	}


	public function get_ref_txn_id()
	{
		return $this->ref_txn_id;
	}

	public function set_ref_txn_id($value)
	{
		$this->ref_txn_id = $value;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}
}
/* End of file pmgw_report_paypal_au_dto.php */
/* Location: ./system/application/libraries/dto/pmgw_report_paypal_au_dto.php */

