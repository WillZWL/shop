<?php
include_once "base_vo.php";

class Ra_product_vo extends Base_vo
{
	public function __construct()
	{
		parent::Base_vo();
	}

	//class variable
	private $sku;
	private $rcm_group_id_1;
	private $rcm_group_id_2;
	private $rcm_group_id_3;
	private $rcm_group_id_4;
	private $rcm_group_id_5;
	private $rcm_group_id_6;
	private $rcm_group_id_7;
	private $rcm_group_id_8;
	private $rcm_group_id_9;
	private $rcm_group_id_10;
	private $rcm_group_id_11;
	private $rcm_group_id_12;
	private $rcm_group_id_13;
	private $rcm_group_id_14;
	private $rcm_group_id_15;
	private $rcm_group_id_16;
	private $rcm_group_id_17;
	private $rcm_group_id_18;
	private $rcm_group_id_19;
	private $rcm_group_id_20;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("sku");

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

	public function get_rcm_group_id_1()
	{
		return $this->rcm_group_id_1;
	}

	public function set_rcm_group_id_1($value)
	{
		$this->rcm_group_id_1 = $value;
		return $this;
	}

	public function get_rcm_group_id_2()
	{
		return $this->rcm_group_id_2;
	}

	public function set_rcm_group_id_2($value)
	{
		$this->rcm_group_id_2 = $value;
		return $this;
	}

	public function get_rcm_group_id_3()
	{
		return $this->rcm_group_id_3;
	}

	public function set_rcm_group_id_3($value)
	{
		$this->rcm_group_id_3 = $value;
		return $this;
	}

	public function get_rcm_group_id_4()
	{
		return $this->rcm_group_id_4;
	}

	public function set_rcm_group_id_4($value)
	{
		$this->rcm_group_id_4 = $value;
		return $this;
	}

	public function get_rcm_group_id_5()
	{
		return $this->rcm_group_id_5;
	}

	public function set_rcm_group_id_5($value)
	{
		$this->rcm_group_id_5 = $value;
		return $this;
	}

	public function get_rcm_group_id_6()
	{
		return $this->rcm_group_id_6;
	}

	public function set_rcm_group_id_6($value)
	{
		$this->rcm_group_id_6 = $value;
		return $this;
	}

	public function get_rcm_group_id_7()
	{
		return $this->rcm_group_id_7;
	}

	public function set_rcm_group_id_7($value)
	{
		$this->rcm_group_id_7 = $value;
		return $this;
	}

	public function get_rcm_group_id_8()
	{
		return $this->rcm_group_id_8;
	}

	public function set_rcm_group_id_8($value)
	{
		$this->rcm_group_id_8 = $value;
		return $this;
	}

	public function get_rcm_group_id_9()
	{
		return $this->rcm_group_id_9;
	}

	public function set_rcm_group_id_9($value)
	{
		$this->rcm_group_id_9 = $value;
		return $this;
	}

	public function get_rcm_group_id_10()
	{
		return $this->rcm_group_id_10;
	}

	public function set_rcm_group_id_10($value)
	{
		$this->rcm_group_id_10 = $value;
		return $this;
	}

	public function get_rcm_group_id_11()
	{
		return $this->rcm_group_id_11;
	}

	public function set_rcm_group_id_11($value)
	{
		$this->rcm_group_id_11 = $value;
		return $this;
	}

	public function get_rcm_group_id_12()
	{
		return $this->rcm_group_id_12;
	}

	public function set_rcm_group_id_12($value)
	{
		$this->rcm_group_id_12 = $value;
		return $this;
	}

	public function get_rcm_group_id_13()
	{
		return $this->rcm_group_id_13;
	}

	public function set_rcm_group_id_13($value)
	{
		$this->rcm_group_id_13 = $value;
		return $this;
	}

	public function get_rcm_group_id_14()
	{
		return $this->rcm_group_id_14;
	}

	public function set_rcm_group_id_14($value)
	{
		$this->rcm_group_id_14 = $value;
		return $this;
	}

	public function get_rcm_group_id_15()
	{
		return $this->rcm_group_id_15;
	}

	public function set_rcm_group_id_15($value)
	{
		$this->rcm_group_id_15 = $value;
		return $this;
	}

	public function get_rcm_group_id_16()
	{
		return $this->rcm_group_id_16;
	}

	public function set_rcm_group_id_16($value)
	{
		$this->rcm_group_id_16 = $value;
		return $this;
	}

	public function get_rcm_group_id_17()
	{
		return $this->rcm_group_id_17;
	}

	public function set_rcm_group_id_17($value)
	{
		$this->rcm_group_id_17 = $value;
		return $this;
	}


	public function get_rcm_group_id_18()
	{
		return $this->rcm_group_id_18;
	}

	public function set_rcm_group_id_18($value)
	{
		$this->rcm_group_id_18 = $value;
		return $this;
	}

	public function get_rcm_group_id_19()
	{
		return $this->rcm_group_id_19;
	}

	public function set_rcm_group_id_19($value)
	{
		$this->rcm_group_id_19 = $value;
		return $this;
	}

	public function get_rcm_group_id_20()
	{
		return $this->rcm_group_id_20;
	}

	public function set_rcm_group_id_20($value)
	{
		$this->rcm_group_id_20 = $value;
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

/* End of file ra_product_vo.php */
/* Location: ./app/libraries/vo/ra_product_vo.php */