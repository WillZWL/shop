<?php
include_once 'Base_dto.php';

class Checkout_dto extends Base_dto
{

	//class variable
	private $platform_id;
	private $payment_gateway_id;

	//instance method
	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

	public function get_payment_gateway_id()
	{
		return $this->payment_gateway_id;
	}

	public function set_payment_gateway_id($value)
	{
		$this->payment_gateway_id = $value;
	}
}

/* End of file checkout_dto.php */
/* Location: ./system/application/libraries/dto/checkout_dto.php */