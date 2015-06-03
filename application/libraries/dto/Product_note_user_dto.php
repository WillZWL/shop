<?php

class Product_note_user_dto extends Base_dto
{

	private $sku;
	private $platform_id;
	private $note;
	private $create_on;
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;
	private $username;

	public function __construct()
	{
		parent::__construct();
	}

		public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
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

	public function get_create_at()
	{
		return $this->create_at;
	}

	public function set_create_at($value)
	{
		$this->create_at = $value;
	}

	public function get_create_by()
	{
		return $this->create_by;
	}

	public function set_create_by($value)
	{
		$this->create_by = $value;
	}

	public function get_modify_on()
	{
		return $this->modify_on;
	}

	public function set_modify_on($value)
	{
		$this->modify_on = $value;
	}

	public function get_modify_at()
	{
		return $this->modify_at;
	}

	public function set_modify_at($value)
	{
		$this->modify_at = $value;
	}

	public function get_modify_by()
	{
		return $this->modify_by;
	}

	public function set_modify_by($value)
	{
		$this->modify_by = $value;
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