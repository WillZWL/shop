<?php

include_once "Base_dto.php";

class Website_prod_info_dto extends Base_dto
{

    private $sku;
    private $name;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $brand_name;
    private $colour_id;
    private $website_status;
    private $website_quantity;
    private $quantity;
    private $thumbnail;
    private $price;
    private $fixed_rrp;
    private $rrp_factor;
    private $currency;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_currency()
    {
        return $this->currency;
    }

    public function set_currency($value)
    {
        $this->currency = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_fixed_rrp()
    {
        return $this->fixed_rrp;
    }

    public function set_fixed_rrp($value)
    {
        $this->price = $fixed_rrp;
    }

    public function get_rrp_factor()
    {
        return $this->rrp_factor;
    }

    public function set_rrp_factor($value)
    {
        $this->price = $rrp_factor;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
    }

    public function get_quantity()
    {
        return $this->quantity;
    }

    public function set_quantity($value)
    {
        $this->quantity = $value;
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

    public function get_colour_id()
    {
        return $this->colour_id;
    }

    public function set_colour_id($value)
    {
        $this->colour_id = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_sub_sub_cat_id()
    {
        return $this->sub_sub_cat_id;
    }

    public function set_sub_sub_cat_id($value)
    {
        $this->sub_sub_cat_id = $value;
    }

    public function get_sub_cat_id()
    {
        return $this->sub_cat_id;
    }

    public function set_sub_cat_id($value)
    {
        $this->sub_cat_id = $value;
    }

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_thumbnail()
    {
        return $this->thumbnail;
    }

    public function set_thumbnail($value)
    {
        $this->thumbnail = $value;
    }
}

?>