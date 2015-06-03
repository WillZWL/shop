<?php
include_once 'Base_vo.php';

class V_gen_sourcing_list_vo extends Base_vo
{

	//class variable
	private $item_sku;
	private $platform_qty;
	private $required_qty;

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

	public function get_platform_qty()
	{
		return $this->platform_qty;
	}

	public function set_platform_qty($value)
	{
		$this->platform_qty = $value;
		return $this;
	}

	public function get_required_qty()
	{
		return $this->required_qty;
	}

	public function set_required_qty($value)
	{
		$this->required_qty = $value;
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