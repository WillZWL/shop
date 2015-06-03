<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Freight_cat_w_region_dto extends Base_dto {

	//class variable
	private $cat_id;
	private $cat_name;
	private $weight;
	private $charge;
	private $create_on;
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	function __construct(){
		parent::__construct();
	}

	//instance method
	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
	}

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_cat_name($value)
	{
		$this->cat_name = $value;
	}

	public function get_weight()
	{
		return $this->weight;
	}

	public function set_weight($value)
	{
		$this->weight = $value;
	}

	public function get_charge()
	{
		return $this->charge;
	}

	public function set_charge($value)
	{
		$this->charge = $value;
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

/* End of file freight_cat_w_region_dto.php */
/* Location: ./system/application/libraries/dto/freight_cat_w_region_dto.php */