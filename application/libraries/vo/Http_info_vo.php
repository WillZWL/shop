<?php
include_once 'Base_vo.php';

class Http_info_vo extends Base_vo
{

	//class variable
	private $name;
	private $type = 'P';
	private $server;
	private $username;
	private $password;
	private $application_id;
	private $signature;
	private $token;
	private $remark;
	private $status = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("name", "type");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
		return $this;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
		$this->type = $value;
		return $this;
	}

	public function get_server()
	{
		return $this->server;
	}

	public function set_server($value)
	{
		$this->server = $value;
		return $this;
	}

	public function get_username()
	{
		return $this->username;
	}

	public function set_username($value)
	{
		$this->username = $value;
		return $this;
	}

	public function get_password()
	{
		return $this->password;
	}

	public function set_password($value)
	{
		$this->password = $value;
		return $this;
	}

	public function get_application_id()
	{
		return $this->application_id;
	}

	public function set_application_id($value)
	{
		$this->application_id = $value;
		return $this;
	}

	public function get_signature()
	{
		return $this->signature;
	}

	public function set_signature($value)
	{
		$this->signature = $value;
		return $this;
	}

	public function get_token()
	{
		return $this->token;
	}

	public function set_token($value)
	{
		$this->token = $value;
		return $this;
	}

	public function get_remark()
	{
		return $this->remark;
	}

	public function set_remark($value)
	{
		$this->remark = $value;
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