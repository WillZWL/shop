<?php

include_once 'Base_dto.php';

Class Order_history_username_dto extends Base_dto
{
	private $status;
	private $create_on;
	private $username;

	public function __construct()
	{
		parent::__construct();
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

	public function get_username()
	{
		return $this->username;
	}

	public function set_username($value)
	{
		$this->username = $value;
	}
}

?>