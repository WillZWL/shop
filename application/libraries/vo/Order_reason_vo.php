<?php
include_once "base_vo.php";

class Order_reason_vo extends Base_vo
{

	public function __construct()
	{
		parent::Base_vo();
	}

	//class variable
	private $reason_id;
	private $reason;
	private $reason_display_name;
	private $priority = '0';
	private $option_in_special = '0';
	private $option_in_manual = '0';
	private $option_in_phone = '0';
	private $status = '0';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;

	//primary key
	private $primary_key = array("reason_id");

	//auo increment
	private $increment_field = "reason_id";

	//instance method
	public function get_reason_id()
	{
		return $this->reason_id;
	}

	public function set_reason_id($value)
	{
		$this->reason_id = $value;
		return $this;
	}

	public function get_reason()
	{
		return $this->reason;
	}

	public function set_reason($value)
	{
		$this->reason = $value;
		return $this;
	}

	public function get_reason_display_name()
	{
		return $this->reason_display_name;
	}

	public function set_reason_display_name($value)
	{
		$this->reason_display_name = $value;
		return $this;
	}

	public function get_priority()
	{
		return $this->priority;
	}

	public function set_priority($value)
	{
		$this->priority = $value;
		return $this;
	}

	public function get_option_in_special()
	{
		return $this->option_in_special;
	}

	public function set_option_in_special($value)
	{
		$this->option_in_special = $value;
		return $this;
	}

	public function get_option_in_manual()
	{
		return $this->option_in_manual;
	}

	public function set_option_in_manual($value)
	{
		$this->option_in_manual = $value;
		return $this;
	}

	public function get_option_in_phone()
	{
		return $this->option_in_phone;
	}

	public function set_option_in_phone($value)
	{
		$this->option_in_phone = $value;
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

/* End of file order_reason_vo.php */
/* Location: ./app/libraries/vo/order_reason_vo.php */