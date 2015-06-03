<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Sli_product_feed_price_info_dto extends Base_dto
{

	private $platform_id;
	private $country_id;
	private $sku;
	private $product_url;
	private $quantity;
	private $currency_id;
	private $price;
	private $rrp;
	private $fixed_rrp;
	private $rrp_factor;
	private $website_status;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

	public function get_country_id()
	{
		return $this->country_id;
	}

	public function set_country_id($value)
	{
		$this->country_id = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_product_url()
	{
		return $this->product_url;
	}

	public function set_product_url($value)
	{
		$this->product_url = $value;
	}

	public function get_quantity()
	{
		return $this->quantity;
	}

	public function set_quantity($value)
	{
		$this->quantity = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function set_price($value)
	{
		$this->price = $value;
	}

	public function get_rrp()
	{
		return $this->rrp;
	}

	public function set_rrp($value)
	{
		$this->rrp = $value;
	}

	public function get_fixed_rrp()
	{
		return $this->fixed_rrp;
	}

	public function set_fixed_rrp($value)
	{
		$this->fixed_rrp = $value;
	}

	public function get_rrp_factor()
	{
		return $this->rrp_factor;
	}

	public function set_rrp_factor($value)
	{
		$this->rrp_factor = $value;
	}

	public function get_website_status()
	{
		return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
	}
}

/* End of file sli_product_feed_price_info_dto.php */
/* Location: ./system/application/libraries/dto/sli_product_feed_price_info_dto.php */