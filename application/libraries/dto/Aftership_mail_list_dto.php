<?php
include_once 'Base_dto.php';

class Aftership_mail_list_dto extends Base_dto
{
	//class variable
	private $so_no;
	private $bill_name;
	private $date_to_delivery;
	private $no_of_partial_shipment;
	private $courier_id;
	private $expect_ship_days;
	private $expect_del_days ;
	private $dispatch_date;
	private $order_create_date;
	private $aftership_status;
	private $aftership_checkpoint;
	private $aftership_token;
	private $pay_date;

	public function __construct()
	{
		parent::__construct();
	}

	//instance method
	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_bill_name()
	{
		return $this->bill_name;
	}

	public function set_bill_name($value)
	{
		$this->bill_name = $value;
	}

	public function get_date_to_delivery()
	{
		return $this->date_to_delivery;
	}

	public function set_date_to_delivery($value)
	{
		$this->date_to_delivery = $value;
	}

	public function get_no_of_partial_shipment()
	{
		return $this->no_of_partial_shipment;
	}

	public function set_no_of_partial_shipment($value)
	{
		$this->no_of_partial_shipment = $value;
	}

	public function get_courier_id()
	{
		return $this->courier_id;
	}

	public function set_courier_id($value)
	{
		$this->courier_id = $value;
	}

	public function get_expect_ship_days()
	{
		return $this->expect_ship_days;
	}

	public function set_expect_ship_days($value)
	{
		$this->expect_ship_days = $value;
	}

	public function get_expect_del_days()
	{
		return $this->expect_del_days;
	}

	public function set_expect_del_days($value)
	{
		$this->expect_del_days = $value;
	}

	public function get_dispatch_date()
	{
		return $this->dispatch_date;
	}

	public function set_dispatch_date($value)
	{
		$this->dispatch_date = $value;
	}

	public function get_order_create_date()
	{
		return $this->order_create_date;
	}

	public function set_order_create_date($value)
	{
		$this->order_create_date = $value;
	}

	public function get_aftership_checkpoint()
	{
		return $this->aftership_checkpoint;
	}

	public function set_aftership_checkpoint($value)
	{
		$this->aftership_checkpoint = $value;
	}

	public function get_aftership_status()
	{
		return $this->aftership_status;
	}

	public function set_aftership_status($value)
	{
		$this->aftership_status = $value;
	}

	public function get_aftership_token()
	{
		return $this->aftership_token;
	}

	public function set_aftership_token($value)
	{
		$this->aftership_token = $value;
	}

	public function get_pay_date()
	{
		return $this->pay_date;
	}

	public function set_pay_date($value)
	{
		$this->pay_date = $value;
	}
}
?>