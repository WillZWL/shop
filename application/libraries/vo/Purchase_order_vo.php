<?php
include_once 'Base_vo.php';

class Purchase_order_vo extends Base_vo
{

	//class variable
	private $po_number = '';
	private $supplier_id;
	private $supplier_invoice_number;
	private $delivery_mode;
	private $status;
	private $currency;
	private $amount = '0.00';
	private $eta = '0000-00-00';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("po_number");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_po_number()
	{
		return $this->po_number;
	}

	public function set_po_number($value)
	{
		$this->po_number = $value;
		return $this;
	}

	public function get_supplier_id()
	{
		return $this->supplier_id;
	}

	public function set_supplier_id($value)
	{
		$this->supplier_id = $value;
		return $this;
	}

	public function get_supplier_invoice_number()
	{
		return $this->supplier_invoice_number;
	}

	public function set_supplier_invoice_number($value)
	{
		$this->supplier_invoice_number = $value;
		return $this;
	}

	public function get_delivery_mode()
	{
		return $this->delivery_mode;
	}

	public function set_delivery_mode($value)
	{
		$this->delivery_mode = $value;
		return $this;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
		return $this;
	}

	public function get_currency()
	{
		return $this->currency;
	}

	public function set_currency($value)
	{
		$this->currency = $value;
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

	public function get_eta()
	{
		return $this->eta;
	}

	public function set_eta($value)
	{
		$this->eta = $value;
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