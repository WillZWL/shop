<?php
include_once 'Base_vo.php';

class Exchange_rate_flex_vo extends Base_vo
{

	//class variable
	private $from_currency_id;
	private $to_currency_id;
	private $rate = '1.0000';
	private $approvial_status = '1';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("from_currency_id", "to_currency_id");

	//auo increment
	private $increment_field = "";

	//instance method
	public function get_from_currency_id()
	{
		return $this->from_currency_id;
	}

	public function set_from_currency_id($value)
	{
		$this->from_currency_id = $value;
		return $this;
	}

	public function get_to_currency_id()
	{
		return $this->to_currency_id;
	}

	public function set_to_currency_id($value)
	{
		$this->to_currency_id = $value;
		return $this;
	}

	public function get_rate()
	{
		return $this->rate;
	}

	public function set_rate($value)
	{
		$this->rate = $value;
		return $this;
	}

	public function get_approvial_status()
	{
		return $this->approvial_status;
	}

	public function set_approvial_status($value)
	{
		$this->approvial_status = $value;
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