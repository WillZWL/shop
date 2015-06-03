<?php
include_once 'Base_vo.php';

class Fd_section_sub_cat_vo extends Base_vo
{

	//class variable
	private $id;
	private $fdsc_id;
	private $left_image;
	private $bg_image;
	private $right_image;
	private $right_link;
	private $display_order = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("id");

	//auo increment
	private $increment_field = "id";

	//instance method
	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
		return $this;
	}

	public function get_fdsc_id()
	{
		return $this->fdsc_id;
	}

	public function set_fdsc_id($value)
	{
		$this->fdsc_id = $value;
		return $this;
	}

	public function get_left_image()
	{
		return $this->left_image;
	}

	public function set_left_image($value)
	{
		$this->left_image = $value;
		return $this;
	}

	public function get_bg_image()
	{
		return $this->bg_image;
	}

	public function set_bg_image($value)
	{
		$this->bg_image = $value;
		return $this;
	}

	public function get_right_image()
	{
		return $this->right_image;
	}

	public function set_right_image($value)
	{
		$this->right_image = $value;
		return $this;
	}

	public function get_right_link()
	{
		return $this->right_link;
	}

	public function set_right_link($value)
	{
		$this->right_link = $value;
		return $this;
	}

	public function get_display_order()
	{
		return $this->display_order;
	}

	public function set_display_order($value)
	{
		$this->display_order = $value;
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