<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Special_order_report_dto extends Base_dto
{
	private $biz_type;
	private $platform_id;
	private $pmgw_name;
	private $bill_country_id;
	private $txn_id;
	private $client_id;
	private $so_no;
	private $prod_name;
	private $cat_name;
	private $item_sku;
	private $dispatch_date;
	private $order_create_date;
	private $amount;
	private $delivery_type_id;
	private $currency_id;
	private $request_by;
	private $approval_date;
	private $approved_by;
	private $reason;
	private $courier_id;
	private $tracking_no;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_biz_type()
	{
		return $this->biz_type;
	}

	public function set_biz_type($value)
	{
		$this->biz_type = $value;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

	public function get_pmgw_name()
	{
		return $this->pmgw_name;
	}

	public function set_pmgw_name($value)
	{
		$this->pmgw_name = $value;
	}

	public function get_bill_country_id()
	{
		return $this->bill_country_id;
	}

	public function set_bill_country_id($value)
	{
		$this->bill_country_id = $value;
	}

	public function get_txn_id()
	{
		return $this->txn_id;
	}

	public function set_txn_id($value)
	{
		$this->txn_id = $value;
	}

	public function get_client_id()
	{
		return $this->client_id;
	}

	public function set_client_id($value)
	{
		$this->client_id = $value;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_cat_name($value)
	{
		$this->cat_name = $value;
	}

	public function get_item_sku()
	{
		return $this->item_sku;
	}

	public function set_item_sku($value)
	{
		$this->item_sku = $value;
	}

	public function get_dispatch_date()
	{
		return $this->dispatch_date;
	}

	public function set_dispatch_date($value)
	{
		$this->dispatch_date = $value;
	}

	public function get_order_create_date()
	{
		return $this->order_create_date;
	}

	public function set_order_create_date($value)
	{
		$this->order_create_date = $value;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
	}

	public function get_delivery_type_id()
	{
		return $this->delivery_type_id;
	}

	public function set_delivery_type_id($value)
	{
		$this->delivery_type_id = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_request_by()
	{
		return $this->request_by;
	}

	public function set_request_by($value)
	{
		$this->request_by = $value;
	}

	public function get_approval_date()
	{
		return $this->approval_date;
	}

	public function set_approval_date($value)
	{
		$this->approval_date = $value;
	}

	public function get_approved_by()
	{
		return $this->approved_by;
	}

	public function set_approved_by($value)
	{
		$this->approved_by = $value;
	}

	public function get_reason()
	{
		return $this->reason;
	}

	public function set_reason($value)
	{
		$this->reason = $value;
	}

	public function get_courier_id()
	{
		return $this->courier_id;
	}

	public function set_courier_id($value)
	{
		$this->courier_id = $value;
	}

	public function get_tracking_no()
	{
		return $this->tracking_no;
	}

	public function set_tracking_no($value)
	{
		$this->tracking_no = $value;
	}
}

?>