<?php
include_once 'Base_dto.php';

Class So_w_payment_dto extends Base_dto
{
	private $order_create_date;
	private $platform_id;
	private $bill_country_id;
	private $delivery_country_id;
	private $country_by_ip;
	private $so_no;
	private $amount;
	private $card_type;
	private $fail_reason;

	public function set_order_create_date($value)
	{
		$this->order_create_date = $value;
		return $this;
	}

	public function get_order_create_date()
	{
		return $this->order_create_date;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
		return $this;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_bill_country_id($value)
	{
		$this->bill_country_id = $value;
		return $this;
	}

	public function get_bill_country_id()
	{
		return $this->bill_country_id;
	}

	public function set_delivery_country_id($value)
	{
		$this->delivery_country_id = $value;
		return $this;
	}

	public function get_delivery_country_id()
	{
		return $this->delivery_country_id;
	}

	public function set_country_by_ip($value)
	{
		$this->country_by_ip = $value;
		return $this;
	}

	public function get_country_by_ip()
	{
		return $this->country_by_ip;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
		return $this;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
		return $this;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_card_type($value)
	{
		$this->card_type = $value;
		return $this;
	}

	public function get_card_type()
	{
		return $this->card_type;
	}

	public function set_fail_reason($value)
	{
		$this->fail_reason = $value;
		return $this;
	}

	public function get_fail_reason()
	{
		preg_match('/(.* mismatch)|failed_reason:(.*)/', $this->fail_reason, $matches);
		return $matches[1].$matches[2];
	}
}

/* End of file so_w_payment_dto.php */
/* Location: ./app/libraries/dto/so_w_payment_dto.php */