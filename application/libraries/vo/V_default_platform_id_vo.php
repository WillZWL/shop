<?php
include_once 'Base_vo.php';

class V_default_platform_id_vo extends Base_vo
{

	//class variable
	private $platform_id;

	//primary key
	private $primary_key = array();

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
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