<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Ra_group_product_w_prodname_dto extends Base_dto
{
	//class variable
	private $ra_group_id;
	private $sku;
	private $name;
	private $priority;
	private $build_bundle;
	private $create_on;
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//instance method
	public function get_ra_group_id()
	{
		return $this->ra_group_id;
	}

	public function set_ra_group_id($value)
	{
		$this->ra_group_id = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_priority()
	{
		return $this->priority;
	}

	public function set_priority($value)
	{
		$this->priority = $value;
	}

	public function get_build_bundle()
	{
		return $this->build_bundle;
	}

	public function set_build_bundle($value)
	{
		$this->build_bundle = $value;
		return $this;
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

/* End of file ra_group_product_w_prodname_dto.php */
/* Location: ./system/application/libraries/dto/ra_group_product_w_prodname_dto.php */