<?php
include_once 'Base_vo.php';

class So_item_vo extends Base_vo
{

	//class variable
	private $so_no;
	private $line_no;
	private $prod_sku;
	private $prod_name;
	private $ext_item_cd;
	private $qty;
	private $unit_price;
	private $vat_total;
	private $gst_total;
	private $amount;
	private $website_status;
	private $warranty_in_month;
	private $status = '0';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("so_no", "line_no", "prod_sku");

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

	public function get_line_no()
	{
		return $this->line_no;
	}

	public function set_line_no($value)
	{
		$this->line_no = $value;
		return $this;
	}

	public function get_prod_sku()
	{
		return $this->prod_sku;
	}

	public function set_prod_sku($value)
	{
		$this->prod_sku = $value;
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

	public function get_ext_item_cd()
	{
		return $this->ext_item_cd;
	}

	public function set_ext_item_cd($value)
	{
		$this->ext_item_cd = $value;
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

	public function get_unit_price()
	{
		return $this->unit_price;
	}

	public function set_unit_price($value)
	{
		$this->unit_price = $value;
		return $this;
	}

	public function get_vat_total()
	{
		return $this->vat_total;
	}

	public function set_vat_total($value)
	{
		$this->vat_total = $value;
		return $this;
	}

	public function get_gst_total()
	{
		return $this->gst_total;
	}

	public function set_gst_total($value)
	{
		$this->gst_total = $value;
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

	public function get_website_status()
	{
		return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
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

	public function get_warranty_in_month()
	{
		return $this->warranty_in_month;
	}

	public function set_warranty_in_month($value)
	{
		$this->warranty_in_month = $value;
		return $this;
	}
}
?>