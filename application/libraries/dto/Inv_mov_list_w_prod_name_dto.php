<?php
include_once 'Base_dto.php';

class Inv_mov_list_w_prod_name_dto extends Base_dto
{

	//class variable
	private $trans_id;
	private $ship_ref;
	private $sku;
	private $qty = '0';
	private $type;
	private $from_location;
	private $to_location;
	private $reason;
	private $status;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;
	private $prod_name;
	private $description;

	//instance method
	public function get_trans_id()
	{
		return $this->trans_id;
	}

	public function set_trans_id($value)
	{
		$this->trans_id = $value;
		return $this;
	}

	public function get_ship_ref()
	{
		return $this->ship_ref;
	}

	public function set_ship_ref($value)
	{
		$this->ship_ref = $value;
		return $this;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
		return $this;
	}

	public function get_qty()
	{
		return $this->qty;
	}

	public function set_qty($value)
	{
		$this->qty = $value;
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

	public function get_from_location()
	{
		return $this->from_location;
	}

	public function set_from_location($value)
	{
		$this->from_location = $value;
		return $this;
	}

	public function get_to_location()
	{
		return $this->to_location;
	}

	public function set_to_location($value)
	{
		$this->to_location = $value;
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

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
		return $this;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
		return $this;
	}
}

/* End of file inv_mov_list_w_prod_name_dto.php */
/* Location: ./system/application/libraries/dto/inv_mov_list_w_prod_name_dto.php */