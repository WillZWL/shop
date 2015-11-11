<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Omg_product_feed_dto extends Base_dto
{
    private $sku;
    private $ext_sku;
    private $detail_desc;
    private $prod_name;
    private $prod_url;
    private $image_url;
    private $price;
    private $category;
    private $ean;
    private $mpn;
    private $brand_name;
    private $availability;
    private $delivery_cost;
    private $delivery_time;
    private $warranty;
    private $condition;
    private $active;
    private $currency;
    private $can_aggregate;
    private $free_shipping;
    private $stock_status;
    private $discount;
    private $empty_field = '';

    // The below variables needed for rrp calculation (price_service->calc_website_product_rrp())
    private $fixed_rrp;
    private $rrp_factor;
    private $rrp;


    public function __construct()
    {
        parent::__construct();
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_prod_url()
    {
        return $this->prod_url;
    }

    public function set_prod_url($value)
    {
        $this->prod_url = $value;
    }

    public function get_image_url()
    {
        return $this->image_url;
    }

    public function set_image_url($value)
    {
        $this->image_url = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_category()
    {
        return $this->category;
    }

    public function set_category($value)
    {
        $this->category = $value;
    }

    public function get_ean()
    {
        return $this->ean;
    }

    public function set_ean($value)
    {
        $this->ean = $value;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_availability()
    {
        return $this->availability;
    }

    public function set_availability($value)
    {
        $this->availability = $value;
    }

    public function get_delivery_cost()
    {
        return $this->delivery_cost;
    }

    public function set_delivery_cost($value)
    {
        $this->delivery_cost = $value;
    }

    public function get_delivery_time()
    {
        return $this->delivery_time;
    }

    public function set_delivery_time($value)
    {
        $this->delivery_time = $value;
    }

    public function get_warranty()
    {
        return $this->warranty;
    }

    public function set_warranty($value)
    {
        $this->warranty = $value;
    }

    public function get_condition()
    {
        return $this->condition;
    }

    public function set_condition($value)
    {
        $this->condition = $value;
    }

    public function get_active()
    {
        return $this->active;
    }

    public function set_active($value)
    {
        $this->active = $value;
    }

    public function get_currency()
    {
        return $this->currency;
    }

    public function set_currency($value)
    {
        $this->currency = $value;
    }

    public function get_can_aggregate()
    {
        return $this->can_aggregate;
    }

    public function set_can_aggregate($value)
    {
        $this->can_aggregate = $value;
    }

    public function get_free_shipping()
    {
        return $this->free_shipping;
    }

    public function set_free_shipping($value)
    {
        $this->free_shipping = $value;
    }

    public function get_stock_status()
    {
        return $this->stock_status;
    }

    public function set_stock_status($value)
    {
        $this->stock_status = $value;
    }

    public function get_discount()
    {
        return $this->discount;
    }

    public function set_discount($value)
    {
        $this->discount = $value;
    }

    public function get_empty_field()
    {
        return $this->empty_field;
    }

    public function set_empty_field($value)
    {
        $this->empty_field = $value;
    }


    // The below variables are needed for rrp calculation (price_service->calc_website_product_rrp())

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

    public function get_rrp()
    {
        return $this->rrp;
    }

    public function set_rrp($value)
    {
        $this->rrp = $value;
    }

}
