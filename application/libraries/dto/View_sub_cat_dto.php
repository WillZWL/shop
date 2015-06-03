<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class View_sub_cat_dto extends Base_dto
{
	private $sub_sub_cat_id;
	private $sub_cat_name;
	private $sub_cat_id;
	private $name;
	private $description;
	private $cat_name;
	private $cat_id;
	private $status;
	private $create_on;
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	public function get_sub_sub_cat_id()
	{
		return $this->sub_sub_cat_id;
	}

	public function set_sub_sub_cat_id($value)
	{
		$this->sub_sub_cat_id =$value;
	}

	public function get_sub_cat_id()
	{
		return $this->sub_cat_id;
	}

	public function set_sub_cat_id($value)
	{
		$this->sub_cat_id =$value;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id =$value;
	}

	public function get_sub_cat_name()
	{
		return $this->sub_cat_name;
	}

	public function set_sub_cat_name($value)
	{
		$this->sub_cat_name =$value;
	}

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_cat_name($value)
	{
		$this->cat_name =$value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name =$value;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
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