<?php

include_once "Base_dto.php";

class Cat_stat_dto extends Base_dto
{
	private $id;
	private $name;
	private $description;
	private $level;
	private $status;
	private $cnt;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
	}

	public function get_level()
	{
		return $this->level;
	}

	public function set_level($value)
	{
		$this->level = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_cnt()
	{
		return $this->cnt;
	}

	public function set_cnt($value)
	{
		$this->cnt = $value;
	}

}

?>