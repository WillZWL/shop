<?php
include_once 'Base_vo.php';

class Price_extend_vo extends Base_vo
{

	//class variable
	private $sku;
	private $platform_id;
	private $title;
	private $note;
	private $ext_desc;
	private $ext_ref_1;
	private $ext_ref_2;
	private $ext_ref_3;
	private $ext_ref_4;
	private $ext_qty;
	private $ext_item_id;
	private $ext_condition;
	private $ext_status;
	private $fulfillment_centre_id;
	private $amazon_reprice_name;
	private $handling_time;
	private $action;
	private $remark;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("sku", "platform_id");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
		return $this;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
		return $this;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_title($value)
	{
		$this->title = $value;
		return $this;
	}

	public function get_note()
	{
		return $this->note;
	}

	public function set_note($value)
	{
		$this->note = $value;
		return $this;
	}

	public function get_ext_desc()
	{
		return $this->ext_desc;
	}

	public function set_ext_desc($value)
	{
		$this->ext_desc = $value;
		return $this;
	}

	public function get_ext_ref_1()
	{
		return $this->ext_ref_1;
	}

	public function set_ext_ref_1($value)
	{
		$this->ext_ref_1 = $value;
		return $this;
	}

	public function get_ext_ref_2()
	{
		return $this->ext_ref_2;
	}

	public function set_ext_ref_2($value)
	{
		$this->ext_ref_2 = $value;
		return $this;
	}

	public function get_ext_ref_3()
	{
		return $this->ext_ref_3;
	}

	public function set_ext_ref_3($value)
	{
		$this->ext_ref_3 = $value;
		return $this;
	}

	public function get_ext_ref_4()
	{
		return $this->ext_ref_4;
	}

	public function set_ext_ref_4($value)
	{
		$this->ext_ref_4 = $value;
		return $this;
	}

	public function get_ext_qty()
	{
		return $this->ext_qty;
	}

	public function set_ext_qty($value)
	{
		$this->ext_qty = $value;
		return $this;
	}

	public function get_ext_item_id()
	{
		return $this->ext_item_id;
	}

	public function set_ext_item_id($value)
	{
		$this->ext_item_id = $value;
		return $this;
	}

	public function get_ext_condition()
	{
		return $this->ext_condition;
	}

	public function set_ext_condition($value)
	{
		$this->ext_condition = $value;
		return $this;
	}

	public function get_ext_status()
	{
		return $this->ext_status;
	}

	public function set_ext_status($value)
	{
		$this->ext_status = $value;
		return $this;
	}

	public function get_fulfillment_centre_id()
	{
		return $this->fulfillment_centre_id;
	}

	public function set_fulfillment_centre_id($value)
	{
		$this->fulfillment_centre_id = $value;
		return $this;
	}

	public function get_amazon_reprice_name()
	{
		return $this->amazon_reprice_name;
	}

	public function set_amazon_reprice_name($value)
	{
		$this->amazon_reprice_name = $value;
		return $this;
	}

	public function get_action()
	{
		return $this->action;
	}

	public function set_action($value)
	{
		$this->action = $value;
		return $this;
	}

	public function get_remark()
	{
		return $this->remark;
	}

	public function set_remark($value)
	{
		$this->remark = $value;
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

	public function set_handling_time($value)
	{
		$this->handling_time = $value;
		return $this;
	}

	public function get_handling_time()
	{
		return $this->handling_time;
	}
}
?>