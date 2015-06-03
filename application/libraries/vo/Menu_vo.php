<?php
include_once 'Base_vo.php';

class Menu_vo extends Base_vo
{

	//class variable
	private $menu_id;
	private $menu_type = 'H';
	private $parent_id;
	private $level;
	private $menu_item_id;
	private $code;
	private $name;
	private $link_type = 'E';
	private $link;
	private $priority = '0';
	private $status = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("menu_id");

	//auo increment
	private $increment_field = "menu_id";

	//instance method
	public function get_menu_id()
	{
		return $this->menu_id;
	}

	public function set_menu_id($value)
	{
		$this->menu_id = $value;
		return $this;
	}

	public function get_menu_type()
	{
		return $this->menu_type;
	}

	public function set_menu_type($value)
	{
		$this->menu_type = $value;
		return $this;
	}

	public function get_parent_id()
	{
		return $this->parent_id;
	}

	public function set_parent_id($value)
	{
		$this->parent_id = $value;
		return $this;
	}

	public function get_level()
	{
		return $this->level;
	}

	public function set_level($value)
	{
		$this->level = $value;
		return $this;
	}

	public function get_menu_item_id()
	{
		return $this->menu_item_id;
	}

	public function set_menu_item_id($value)
	{
		$this->menu_item_id = $value;
		return $this;
	}

	public function get_code()
	{
		return $this->code;
	}

	public function set_code($value)
	{
		$this->code = $value;
		return $this;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
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

	public function get_link()
	{
		return $this->link;
	}

	public function set_link($value)
	{
		$this->link = $value;
		return $this;
	}

	public function get_priority()
	{
		return $this->priority;
	}

	public function set_priority($value)
	{
		$this->priority = $value;
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