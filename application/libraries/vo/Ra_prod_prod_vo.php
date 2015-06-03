<?php
include_once 'Base_vo.php';

class Ra_prod_prod_vo extends Base_vo
{

	//class variable
	private $sku;
	private $rcm_prod_id_1;
	private $rcm_prod_id_2;
	private $rcm_prod_id_3;
	private $rcm_prod_id_4;
	private $rcm_prod_id_5;
	private $rcm_prod_id_6;
	private $rcm_prod_id_7;
	private $rcm_prod_id_8;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("sku", "rcm_prod_id_1");

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

	public function get_rcm_prod_id_1()
	{
		return $this->rcm_prod_id_1;
	}

	public function set_rcm_prod_id_1($value)
	{
		$this->rcm_prod_id_1 = $value;
		return $this;
	}

	public function get_rcm_prod_id_2()
	{
		return $this->rcm_prod_id_2;
	}

	public function set_rcm_prod_id_2($value)
	{
		$this->rcm_prod_id_2 = $value;
		return $this;
	}

	public function get_rcm_prod_id_3()
	{
		return $this->rcm_prod_id_3;
	}

	public function set_rcm_prod_id_3($value)
	{
		$this->rcm_prod_id_3 = $value;
		return $this;
	}

	public function get_rcm_prod_id_4()
	{
		return $this->rcm_prod_id_4;
	}

	public function set_rcm_prod_id_4($value)
	{
		$this->rcm_prod_id_4 = $value;
		return $this;
	}

	public function get_rcm_prod_id_5()
	{
		return $this->rcm_prod_id_5;
	}

	public function set_rcm_prod_id_5($value)
	{
		$this->rcm_prod_id_5 = $value;
		return $this;
	}

	public function get_rcm_prod_id_6()
	{
		return $this->rcm_prod_id_6;
	}

	public function set_rcm_prod_id_6($value)
	{
		$this->rcm_prod_id_6 = $value;
		return $this;
	}

	public function get_rcm_prod_id_7()
	{
		return $this->rcm_prod_id_7;
	}

	public function set_rcm_prod_id_7($value)
	{
		$this->rcm_prod_id_7 = $value;
		return $this;
	}

	public function get_rcm_prod_id_8()
	{
		return $this->rcm_prod_id_8;
	}

	public function set_rcm_prod_id_8($value)
	{
		$this->rcm_prod_id_8 = $value;
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