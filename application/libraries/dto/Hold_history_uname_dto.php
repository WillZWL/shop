<?php

class Hold_history_uname_dto extends Base_dto
{
	private $reason;
	private $uname;
	private $create_on;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_reason()
	{
		return $this->reason;
	}

	public function set_reason($value)
	{
		$this->reason = $value;
	}

	public function get_username()
	{
		return $this->username;
	}

	public function set_username($value)
	{
		$this->username = $value;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
	}

}

?>