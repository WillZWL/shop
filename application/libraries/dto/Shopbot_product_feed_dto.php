<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Shopbot_product_feed_dto extends Base_dto
{

    private $mpn;
    private $sku;
    private $brand_name;
    private $cat_name;
    private $prod_name;
    private $detail_desc;
    private $product_url;
    private $price;
    private $rrp;
    private $fixed_rrp;
    private $rrp_factor;
    private $availability;
    private $image_url;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_product_url()
    {
        return $this->product_url;
    }

    public function set_product_url($value)
    {
        $this->product_url = $value;
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

    public function get_availability()
    {
        return $this->availability;
    }

    public function set_availability($value)
    {
        $this->availability = $value;
    }

    public function get_image_url()
    {
        return $this->image_url;
    }

    public function set_image_url($value)
    {
        $this->image_url = $value;
    }

}

/* End of file shopbot_product_feed_dto.php */
/* Location: ./system/application/libraries/dto/shopbot_product_feed_dto.php */