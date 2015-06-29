<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Sli_product_feed_dto extends Base_dto
{

    private $platform_id;
    private $country_id;
    private $sku;
    private $prod_name;
    private $short_desc;
    private $detail_desc;
    private $product_url;
    private $image_url;
    private $cat_name;
    private $sub_cat_name;
    private $brand_name;
    private $quantity;
    private $currency_id;
    private $price;
    private $rrp;
    private $website_status;
    private $mpn;
    private $ean;
    private $upc;
    private $inbundle;

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

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
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

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

    public function get_ean()
    {
        return $this->ean;
    }

    public function set_ean($value)
    {
        $this->ean = $value;
    }

    public function get_upc()
    {
        return $this->upc;
    }

    public function set_upc($value)
    {
        $this->upc = $value;
    }

    public function get_prod_condition()
    {
        return $this->prod_condition;
    }

    public function set_prod_condition($value)
    {
        $this->prod_condition = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
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

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status = $value;
    }

    public function get_inbundle()
    {
        return $this->inbundle;
    }

    public function set_inbundle($value)
    {
        $this->inbundle = $value;
    }

}

/* End of file sli_product_feed_dto.php */
/* Location: ./system/application/libraries/dto/sli_product_feed_dto.php */