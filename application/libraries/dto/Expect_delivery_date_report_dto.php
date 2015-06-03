<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Expect_delivery_date_report_dto extends Base_dto
{
	private $platform_id;
	private $so_no;
	private $prod_name;
	private $item_sku;
	private $request_by;
	private $request_date;
	private $approval_date;
	private $approved_by;
	private $reason;

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

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

	public function get_payment_gateway()
	{
		return $this->payment_gateway_id;
	}

	public function set_payment_gateway($value)
	{
		$this->payment_gateway_id = $value;
	}

	public function get_platform_order_id()
	{
		return $this->platform_order_id;
	}

	public function set_platform_order_id($value)
	{
		$this->platform_order_id = $value;
	}

	public function get_ebay_id()
	{
		return $this->ext_client_id;
	}

	public function set_ebay_id($value)
	{
		$this->ext_client_id = $value;
	}

	public function get_transaction_no()
	{
		return $this->txn_id;
	}

	public function set_transaction($value)
	{
		$this->txn_id = $value;
	}

	public function get_order_amount()
	{
		return $this->amount;
	}

	public function set_order_amount($value)
	{
		$this->amount = $value;
	}

	public function get_create_date()
	{
		return date('d/m/Y h:i:s', strtotime($this->order_create_date));
	}

	public function set_create_date($value)
	{
		$this->order_create_date = $value;
	}

	public function get_edd()
	{
		return date('d/m/Y', strtotime($this->expect_delivery_date));
	}

	public function set_edd($value)
	{
		$this->expect_delivery_date = $value;
	}

	public function get_client_name()
	{
		return $this->bill_name;
	}

	public function set_client_name($value)
	{
		$this->bill_name = $value;
	}

	public function get_client_email()
	{
		return $this->email;
	}

	public function set_client_email($value)
	{
		$this->emial = $value;
	}

	public function get_contact_no()
	{
		return $this->contact_no;
	}

	public function set_contact_no($value)
	{
		$this->contact_no = $value;
	}

	public function get_shipped_on()
	{
		return date('d/m/Y h:i:s', strtotime($this->dispatch_date));
	}

	public function set_shipped_on($value)
	{
		$this->dispatch_date = $value;
	}

	public function get_order_status()
	{
		switch ($this->status) {
			case '0':
				$this->status = 'Inactive';
				break;
			case '1':
				$this->status = 'New';
				break;
			case '2':
				$this->status = 'Paid';
				break;
			case '3':
				$this->status = 'Fulfilment AKA Credit Checked';
				break;
			case '4':
				$this->status = 'Partial Allocated';
				break;
			case '5':
				$this->status = 'Full Allocated';
				break;
			case '6':
				$this->status = 'Shipped';
				break;
			default:
				break;
		}
		return $this->status;
	}

	public function set_order_status($value)
	{
		$this->status = $value;
	}

	public function get_hold_status()
	{
		switch ($this->hold_status) {
			case '0':
				$this->hold_status = 'No';
				break;
			case '1':
				$this->hold_status = 'Requested';
				break;
			case '2':
				$this->hold_status = 'Manager Requested';
				break;
			case '3':
				$this->hold_status = 'APS need Payment order in Sales - APS area';
				break;
			case '10':
				$this->hold_status = 'Permanent Hold';
				break;
			default:
				break;
		}
		return $this->hold_status;
	}

	public function set_hold_status($value)
	{
		$this->hold_status = $value;
	}

	public function get_refund_status()
	{
		switch ($this->refund_status) {
			case '0':
				$this->refund_status = 'No';
				break;
			case '1':
				$this->refund_status = 'Requested';
				break;
			case '2':
				$this->refund_status = 'Logistic Approved';
				break;
			case '3':
				$this->refund_status = 'CS Approved';
				break;
			case '4':
				$this->refund_status = 'Refunded';
				break;
			default:
				break;
		}
		return $this->refund_status;
	}

	public function set_refund_status($value)
	{
		$this->refund_status = $value;
	}

	public function get_priority_score()
	{
		return $this->score;
	}

	public function set_priority_score($value)
	{
		$this->score = $value;
	}

	public function get_modify_by()
	{
		return $this->modify_by;
	}

	public function set_modify_by($value)
	{
		$this->modify_by = $value;
	}
}

?>