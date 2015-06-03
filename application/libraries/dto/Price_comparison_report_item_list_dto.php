<?php
include_once "Base_dto.php";

class price_comparison_report_item_list_dto extends Base_dto
{
	private $sku;
	private $name;
	private $country;
	private $website_platform;
	private $website_price;
	private $skype_platform;
	private $skype_price;

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_name()
	{
		$f_name = str_replace(',',' ',$this->name);
		return $f_name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_country()
	{
		return $this->country;
	}

	public function set_country($value)
	{
		$this->country = $value;
	}

	public function get_website_platform()
	{
		return $this->website_platform;
	}

	public function set_website_platform($value)
	{
		$this->website_platform = $value;
	}

	public function get_website_price()
	{
		return $this->website_price;
	}

	public function set_website_price($value)
	{
		$this->website_price = $value;
	}

	public function get_skype_platform()
	{
		return $this->skype_platform;
	}

	public function set_skype_platform($value)
	{
		$this->skype_platform = $value;
	}

	public function get_skype_price()
	{
		return $this->skype_price;
	}

	public function set_skype_price($value)
	{
		$this->skype_price = $value;
	}
}