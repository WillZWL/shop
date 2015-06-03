<?php

include_once 'Base_dto.php';

Class Order_note_username_dto extends Base_dto
{
	private $note;
	private $create_on;
	private $username;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_note()
	{
		return $this->note;
	}

	public function set_note($value)
	{
		$this->note = $value;
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