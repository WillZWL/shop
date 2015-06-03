<?php
include_once "Base_dto.php";

class Voucher_report_item_list_dto extends Base_dto
{
	private $platform_id;
	private $biz_type;
	private $so_no;
	private $order_date;
	private $email;
	private $voucher_code;

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

	public function get_biz_type()
	{
		return $this->biz_type;
	}

	public function set_biz_type($value)
	{
		$this->biz_type = $value;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_order_date()
	{
		return $this->order_date;
	}

	public function set_order_date($value)
	{
		$this->order_date = $value;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function set_email($value)
	{
		$this->email = $value;
	}

	public function get_voucher_code()
	{
		return $this->voucher_code;
	}

	public function set_voucher_code($value)
	{
		$this->voucher_code = $value;
	}
}