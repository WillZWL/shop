<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";


class Po_supplier_name_dto extends Base_dto
{
	//instance variable
	private $po_number;
	private $supplier_name;
	private $supplier_invoice_number;
	private $delivery_mode;
	private $status;
	private $currency;
	private $amount;
	private $eta;
	private $currency_name;
	private $purchase_detail;
	private $po_message;
	private $create_on;
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;


	//instance method
	public function get_po_number()
	{
		return $this->po_number;
	}

	public function set_po_number($value)
	{
		$this->po_number = $value;
	}

	public function get_supplier_name()
	{
		return $this->supplier_name;
	}

	public function set_supplier_name($value)
	{
		$this->name = $value;
	}

	public function get_supplier_invoice_number()
	{
		return $this->supplier_invoice_number;
	}

	public function set_supplier_invoice_number($value)
	{
		$this->supplier_invoice_number = $value;
	}

	public function get_delivery_mode()
	{
		return $this->delivery_mode;
	}

	public function set_delivery_mode($value)
	{
		$this->delivery_mode = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_currency()
	{
		return $this->currency;
	}

	public function set_currency($value)
	{
		$this->currency = $value;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
	}

	public function get_eta()
	{
		return $this->eta;
	}

	public function set_eta($value)
	{
		$this->eta = $value;
	}

	public function get_currency_name()
	{
		return $this->currency_name;
	}

	public function set_currency_name($value)
	{
		$this->currency_name = $value;
	}

	public function get_purchase_detail()
	{
		return $this->purchase_detail;
	}

	public function set_purchase_detail($value)
	{
		$this->purchase_detail = $value;
	}

	public function get_po_message()
	{
		return $this->po_message;
	}

	public function set_po_message($value)
	{
		$this->po_message = $value;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
	}

	public function get_create_at()
	{
		return $this->create_at;
	}

	public function set_create_at($value)
	{
		$this->create_at = $value;
	}

	public function get_create_by()
	{
		return $this->create_by;
	}

	public function set_create_by($value)
	{
		$this->create_by = $value;
	}

	public function get_modify_on()
	{
		return $this->modify_on;
	}

	public function set_modify_on($value)
	{
		$this->modify_on = $value;
	}

	public function get_modify_at()
	{
		return $this->modify_at;
	}

	public function set_modify_at($value)
	{
		$this->modify_at = $value;
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