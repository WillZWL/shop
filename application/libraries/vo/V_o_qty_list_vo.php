<?php
include_once 'Base_vo.php';

class V_o_qty_list_vo extends Base_vo
{

	//class variable
	private $item_sku;
	private $platform_id;
	private $o_qty;

	//primary key
	private $primary_key = array();

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_item_sku()
	{
		return $this->item_sku;
	}

	public function set_item_sku($value)
	{
		$this->item_sku = $value;
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

	public function get_o_qty()
	{
		return $this->o_qty;
	}

	public function set_o_qty($value)
	{
		$this->o_qty = $value;
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