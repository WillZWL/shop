<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Googlebase_product_feed_dto extends Base_dto
{
	private $platform_id;
	private $sku;
	private $prod_grp_cd;
	private $version_id;
	private $colour_id;
	private $colour_name;
	private $platform_country_id;
	private $language_id;
	private $prod_name;
	private $cat_id;
	private $cat_name;
	private $sub_cat_id;
	private $sub_cat_name;
	private $brand_id;
	private $brand_name;
	private $mpn;
	private $upc;
	private $ean;
	private $short_desc;
	private $detail_desc;
	private $contents;
	private $prod_weight;
	private $image;
	private $platform_currency_id;
	private $price;
	private $default_platform_converted_price;
	private $quantity;
	private $display_quantity;
	private $website_quantity;
	private $website_status;
	private $availability;
	private $prod_status;
	private $listing_status;
	private $ex_demo;
	private $google_ref_id;
	private $product_type;
	private $google_product_category;
	private $product_url;
	private $image_url;
	private $condition;
	private $price_w_curr;
	private $item_group_id;
	private $shipping;

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

	public function set_prod_grp_cd($value)
	{
		$this->prod_grp_cd = $value;
	}

	public function get_prod_grp_cd()
	{
		return $this->prod_grp_cd;
	}

	public function get_version_id()
	{
		return $this->version_id;
	}

	public function set_version_id($value)
	{
		$this->version_id = $value;
	}

	public function get_colour_id()
	{
		return $this->colour_id;
	}

	public function set_colour_id($value)
	{
		$this->colour_id = $value;
	}

	public function get_colour_name()
	{
		return $this->colour_name;
	}

	public function set_colour_name($value)
	{
		$this->colour_name = $value;
	}

	public function get_platform_country_id()
	{
		return $this->platform_country_id;
	}

	public function set_platform_country_id($value)
	{
		$this->platform_country_id = $value;
	}

	public function get_language_id()
	{
		return $this->language_id;
	}

	public function set_language_id($value)
	{
		$this->language_id = $value;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
	}

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_cat_name($value)
	{
		$this->cat_name = $value;
	}

	public function get_sub_cat_id()
	{
		return $this->sub_cat_id;
	}

	public function set_sub_cat_id($value)
	{
		$this->sub_cat_id = $value;
	}

	public function get_sub_cat_name()
	{
		return $this->sub_cat_name;
	}

	public function set_sub_cat_name($value)
	{
		$this->sub_cat_name = $value;
	}

	public function get_brand_id()
	{
		return $this->brand_id;
	}

	public function set_brand_id($value)
	{
		$this->brand_id = $value;
	}

	public function get_brand_name()
	{
		return $this->brand_name;
	}

	public function set_brand_name($value)
	{
		$this->brand_name = $value;
	}

	public function get_mpn()
	{
		return $this->mpn;
	}

	public function set_mpn($value)
	{
		$this->mpn = $value;
	}

	public function get_upc()
	{
		return $this->upc;
	}

	public function set_upc($value)
	{
		$this->upc = $value;
	}

	public function get_ean()
	{
		return $this->ean;
	}

	public function set_ean($value)
	{
		$this->ean = $value;
	}

	public function get_short_desc()
	{
		return $this->short_desc;
	}

	public function set_short_desc($value)
	{
		$this->short_desc = $value;
	}

	public function get_detail_desc()
	{
		return $this->detail_desc;
	}

	public function set_detail_desc($value)
	{
		$this->detail_desc = $value;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_contents($value)
	{
		$this->contents = $value;
	}

	public function get_prod_weight()
	{
		return $this->prod_weight;
	}

	public function set_prod_weight($value)
	{
		$this->prod_weight = $value;
	}

	public function get_image()
	{
		return $this->image;
	}

	public function set_image($value)
	{
		$this->image = $value;
	}

	public function get_platform_currency_id()
	{
		return $this->platform_currency_id;
	}

	public function set_platform_currency_id($value)
	{
		$this->platform_currency_id = $value;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function set_price($value)
	{
		$this->price = $value;
	}

	public function get_default_platform_converted_price()
	{
		return $this->default_platform_converted_price;
	}

	public function set_default_platform_converted_price($value)
	{
		$this->default_platform_converted_price = $value;
	}

	public function get_quantity()
	{
		return $this->quantity;
	}

	public function set_quantity($value)
	{
		$this->quantity = $value;
	}

	public function get_display_quantity()
	{
		return $this->display_quantity;
	}

	public function set_display_quantity($value)
	{
		$this->display_quantity = $value;
	}

	public function get_website_quantity()
	{
		return $this->website_quantity;
	}

	public function set_website_quantity($value)
	{
		$this->website_quantity = $value;
	}

	public function get_website_status()
	{
		return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
	}

	public function get_availability()
	{
		return $this->availability;
	}

	public function set_availability($value)
	{
		$this->availability = $value;
	}

	public function get_prod_status()
	{
		return $this->prod_status;
	}

	public function set_prod_status($value)
	{
		$this->prod_status = $value;
	}

	public function get_listing_status()
	{
		return $this->listing_status;
	}

	public function set_listing_status($value)
	{
		$this->listing_status = $value;
	}

	public function get_ex_demo()
	{
		return $this->ex_demo;
	}

	public function set_ex_demo($value)
	{
		$this->ex_demo = $value;
	}

	public function get_google_ref_id()
	{
		return $this->google_ref_id;
	}

	public function set_google_ref_id($value)
	{
		$this->google_ref_id = $value;
	}

	public function get_product_type()
	{
		return $this->product_type;
	}

	public function set_product_type($value)
	{
		$this->product_type = $value;
	}

	public function get_google_product_category()
	{
		return $this->google_product_category;
	}

	public function set_google_product_category($value)
	{
		$this->google_product_category = $value;
	}

	public function get_product_url()
	{
		return $this->product_url;
	}

	public function set_product_url($value)
	{
		$this->product_url = $value;
	}

	public function get_image_url()
	{
		return $this->image_url;
	}

	public function set_image_url($value)
	{
		$this->image_url = $value;
	}

	public function get_condition()
	{
		return $this->condition;
	}

	public function set_condition($value)
	{
		$this->condition = $value;
	}

	public function get_price_w_curr()
	{
		return $this->price_w_curr;
	}

	public function set_price_w_curr($value)
	{
		$this->price_w_curr = $value;
	}

	public function get_sale_price()
	{
		return $this->sale_price;
	}

	public function set_sale_price($value)
	{
		$this->sale_price = $value;
	}

	public function get_item_group_id()
	{
		return $this->item_group_id;
	}

	public function set_item_group_id($value)
	{
		$this->item_group_id = $value;
	}

	public function get_shipping()
	{
		return $this->shipping;
	}

	public function set_shipping($value)
	{
		$this->shipping = $value;
	}
}

/* End of file googlebase_product_feed_dto.php */
/* Location: ./system/application/libraries/dto/googlebase_product_feed_dto.php */