<?php
include_once 'Base_dto.php';

class Refund_hist_uname_dto extends Base_dto
{

	//class variable
	private $id;
	private $refund_id;
	private $status = 'CS';
	private $reason = '0';
	private $notes;
	private $name;
	private $reason_cat;
	private $app_status;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;
	private $description;

	//instance method
	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
	}

	public function get_reason_cat()
	{
		return $this->reason_cat;
	}

	public function set_reason_cat($value)
	{
		$this->reason_cat = $value;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_name($value)
	{
		$this->username = $value;
	}

	public function get_name()
	{
		return $this->username;
	}

	public function get_refund_id()
	{
		return $this->refund_id;
	}

	public function set_refund_id($value)
	{
		$this->refund_id = $value;
	}

	public function get_app_status()
	{
		return $this->app_status;
	}

	public function set_app_status($value)
	{
		$this->app_status = $value;
	}


	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_reason()
	{
		return $this->reason;
	}

	public function set_reason($value)
	{
		$this->reason = $value;
	}

	public function get_notes()
	{
		return $this->notes;
	}

	public function set_notes($value)
	{
		$this->notes = $value;
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
}
?>