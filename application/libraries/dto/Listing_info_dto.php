<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Listing_info_dto extends Base_dto
{

	private $platform_id;
	private $sku;
	private $prod_name;
	private $short_desc;
	private $image_ext;
	private $currency_id;
	private $rrp_price;
	private $price;
	private $qty;
	private $status;
	private $youtube_id_1;
	private $youtube_id_2;
	private $youtube_caption_1;
	private $youtube_caption_2;
	private $fixed_rrp;
	private $rrp_factor;
	private $warranty_in_month;
	private $delivery_scenarioid;

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

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}

	public function get_short_desc()
	{
		return $this->short_desc;
	}

	public function set_short_desc($value)
	{
		$this->short_desc = $value;
	}

	public function get_image_ext()
	{
		return $this->image_ext;
	}

	public function set_image_ext($value)
	{
		$this->image_ext = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_rrp_price()
	{
		return $this->rrp_price;
	}

	public function set_rrp_price($value)
	{
		$this->rrp_price = $value;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function set_price($value)
	{
		$this->price = $value;
	}

	public function get_qty()
	{
		return $this->qty;
	}

	public function set_qty($value)
	{
		$this->qty = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_youtube_id_1()
	{
		return $this->youtube_id_1;
	}

	public function set_youtube_id_1($value)
	{
		return $this->youtube_id_1 = $value;
	}

	public function get_youtube_caption_1()
	{
		return $this->youtube_caption_1;
	}

	public function set_youtube_caption_1($value)
	{
		return $this->youtube_caption_1 = $value;
	}

	public function get_youtube_id_2()
	{
		return $this->youtube_id_2;
	}

	public function set_youtube_id_2($value)
	{
		return $this->youtube_id_2 = $value;
	}

	public function get_youtube_caption_2()
	{
		return $this->youtube_caption_2;
	}

	public function set_youtube_caption_2($value)
	{
		return $this->youtube_caption_2 = $value;
	}

	public function get_fixed_rrp()
	{
		return $this->fixed_rrp;
	}

	public function set_fixed_rrp($value)
	{
		return $this->fixed_rrp = $value;
	}

	public function get_rrp_factor()
	{
		return $this->rrp_factor;
	}

	public function set_rrp_factor($value)
	{
		return $this->rrp_factor = $value;
	}

	public function get_warranty_in_month()
	{
		return $this->warranty_in_month;
	}

	public function set_warranty_in_month($value)
	{
		return $this->warranty_in_month = $value;
	}

	public function get_delivery_scenarioid()
	{
		return $this->delivery_scenarioid;
	}

	public function set_delivery_scenarioid($value)
	{
		return $this->delivery_scenarioid = $value;
	}
}

/* End of file listing_info_dto.php */
/* Location: ./system/application/libraries/dto/listing_info_dto.php */