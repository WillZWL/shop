<?php
include_once 'Base_vo.php';

class Banner_vo extends Base_vo
{

	//class variable
	private $cat_id;
	private $type = '0';
	private $usage = 'PV';
	private $image_file;
	private $flash_file;
	private $link;
	private $link_type = 'E';
	private $status = 'A';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("cat_id", "type", "usage");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
		return $this;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
		$this->type = $value;
		return $this;
	}

	public function get_usage()
	{
		return $this->usage;
	}

	public function set_usage($value)
	{
		$this->usage = $value;
		return $this;
	}

	public function get_image_file()
	{
		return $this->image_file;
	}

	public function set_image_file($value)
	{
		$this->image_file = $value;
		return $this;
	}

	public function get_flash_file()
	{
		return $this->flash_file;
	}

	public function set_flash_file($value)
	{
		$this->flash_file = $value;
		return $this;
	}

	public function get_link()
	{
		return $this->link;
	}

	public function set_link($value)
	{
		$this->link = $value;
		return $this;
	}

	public function get_link_type()
	{
		return $this->link_type;
	}

	public function set_link_type($value)
	{
		$this->link_type = $value;
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

}
?>