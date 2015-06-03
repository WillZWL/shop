<?php
include_once 'Base_vo.php';

class Category_mapping_vo extends Base_vo
{

	//class variable
	private $ext_party;
	private $level;
	private $id;
	private $ext_id;
	private $ext_name;
	private $lang_id = '';
	private $country_id = '';
	private $product_name = null;
	private $status = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("ext_party", "level", "id", "lang_id", "country_id");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_ext_party()
	{
		return $this->ext_party;
	}

	public function set_ext_party($value)
	{
		$this->ext_party = $value;
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

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
		return $this;
	}

	public function get_ext_id()
	{
		return $this->ext_id;
	}

	public function set_ext_id($value)
	{
		$this->ext_id = $value;
		return $this;
	}

	public function get_ext_name()
	{
		return $this->ext_name;
	}

	public function set_ext_name($value)
	{
		$this->ext_name = $value;
		return $this;
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	public function set_lang_id($value)
	{
		$this->lang_id = $value;
		return $this;
	}

	public function get_country_id()
	{
		return $this->country_id;
	}

	public function set_country_id($value)
	{
		$this->country_id = $value;
		return $this;
	}

	public function get_product_name()
	{
		return $this->product_name;
	}

	public function set_product_name($value)
	{
		$this->product_name = $value;
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