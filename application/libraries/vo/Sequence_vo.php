<?php
include_once 'Base_vo.php';

class Sequence_vo extends Base_vo
{

	//class variable
	private $seq_name;
	private $value;
	private $increment_level;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("seq_name");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_seq_name()
	{
		return $this->seq_name;
	}

	public function set_seq_name($value)
	{
		$this->seq_name = $value;
		return $this;
	}

	public function get_value()
	{
		return $this->value;
	}

	public function set_value($value)
	{
		$this->value = $value;
		return $this;
	}

	public function get_increment_level()
	{
		return $this->increment_level;
	}

	public function set_increment_level($value)
	{
		$this->increment_level = $value;
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