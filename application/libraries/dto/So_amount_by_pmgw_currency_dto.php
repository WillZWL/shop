<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class So_amount_by_pmgw_currency_dto extends Base_dto
{
	//class variable
	private $so_count;
	private $so_amount;
	private $currency_id;
	private $payment_gateway_id;
	private $pmgw_name;
	private $platform_country_id;

	//instance method
	public function get_so_count()
	{
		return $this->so_count;
	}

	public function set_so_count($value)
	{
		$this->so_count = $value;
	}

	public function get_so_amount()
	{
		return $this->so_amount;
	}

	public function set_so_amount($value)
	{
		$this->so_amount = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_payment_gateway_id()
	{
		return $this->payment_gateway_id;
	}

	public function set_payment_gateway_id($value)
	{
		$this->payment_gateway_id = $value;
	}

	public function get_pmgw_name()
	{
		return $this->pmgw_name;
	}

	public function set_pmgw_name($value)
	{
		$this->pmgw_name = $value;
	}

	public function get_platform_country_id()
	{
		return $this->platform_country_id;
	}

	public function set_platform_country_id($value)
	{
		$this->platform_country_id = $value;
	}
}
/* End of file so_amount_by_pmgw_currency_dto.php */
/* Location: ./system/application/libraries/dto/so_amount_by_pmgw_currency_dto.php */