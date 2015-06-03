<?php
include_once 'Base_vo.php';

class So_bank_transfer_vo extends Base_vo
{
	//class variable
	private $id;
	private $so_no;
	private $sbt_status;
	private $net_diff_status;
	private $ext_ref_no;
	private $received_amt_localcurr = '0.00';
	private $bank_account_id;
	private $bank_charge;
	private $notes;
	private $received_date = '0000-00-00 00:00:00';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//primary key
	private $primary_key = array("id");

	//auto increment
	private $increment_field = "id";

	//instance method
	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
		return $this;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
		return $this;
	}

	public function get_sbt_status()
	{
		return $this->sbt_status;
	}

	public function set_sbt_status($value)
	{
		$this->sbt_status = $value;
		return $this;
	}

	public function get_net_diff_status()
	{
		return $this->net_diff_status;
	}

	public function set_net_diff_status($value)
	{
		$this->net_diff_status = $value;
		return $this;
	}

	public function get_ext_ref_no()
	{
		return $this->ext_ref_no;
	}

	public function set_ext_ref_no($value)
	{
		$this->ext_ref_no = $value;
		return $this;
	}

	public function get_received_amt_localcurr()
	{
		return $this->received_amt_localcurr;
	}

	public function set_received_amt_localcurr($value)
	{
		$this->received_amt_localcurr = $value;
		return $this;
	}

	public function get_bank_account_id()
	{
		return $this->bank_account_id;
	}

	public function set_bank_account_id($value)
	{
		$this->bank_account_id = $value;
		return $this;
	}

	public function get_bank_charge()
	{
		return $this->bank_charge;
	}

	public function set_bank_charge($value)
	{
		$this->bank_charge = $value;
		return $this;
	}

	public function get_received_date_localtime()
	{
		return $this->received_date_localtime;
	}

	public function set_received_date_localtime($value)
	{
		$this->received_date_localtime = $value;
		return $this;
	}

	public function get_notes()
	{
		return $this->notes;
	}

	public function set_notes($value)
	{
		$this->notes = $value;
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