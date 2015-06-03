<?php
include_once 'Base_dto.php';

class Dynamic_shipment_status_dto extends Base_dto
{
	//class variable
	private $so_no;
	private $pay_date;
	private $order_status;
	private $aftership_status;
	private $last_update_time;
	private $comment;
	private $dispatch_date;

	//instance method
	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_pay_date()
	{
		return $this->pay_date;
	}

	public function set_pay_date($value)
	{
		$this->pay_date = $value;
	}

	public function get_order_status()
	{
		return $this->order_status;
	}

	public function set_order_status($value)
	{
		$this->order_status = $value;
	}

	public function get_aftership_status()
	{
		return $this->aftership_status;
	}

	public function set_aftership_status($value)
	{
		$this->aftership_status = $value;
	}

	public function get_last_update_time()
	{
		return $this->last_update_time;
	}

	public function set_last_update_time($value)
	{
		$this->last_update_time = $value;
	}

	public function get_comment()
	{
		return $this->comment;
	}

	public function set_comment($value)
	{
		$this->comment = $value;
	}

	public function get_dispatch_date()
	{
		return $this->dispatch_date;
	}

	public function set_dispatch_date($value)
	{
		$this->dispatch_date = $value;
	}
}

/* End of file dynamic_shipment_status_dto.php */
/* Location: ./system/application/libraries/dto/dynamic_shipment_status_dto.php */