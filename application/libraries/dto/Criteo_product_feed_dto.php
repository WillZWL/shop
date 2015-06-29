<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Criteo_product_feed_dto extends Base_dto
{
    // mandatory field
    private $sku;
    private $prod_name;
    private $prod_url;

    // recommended field
    private $image_url_small;
    private $cat_name;
    private $short_desc;
    private $availability;
    private $price;

    // additional field
    private $image_url_large;
    private $rrp;
    private $discount;
    private $sub_cat_name;

    // auxillary field
    private $image;

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

    public function get_image_url_small()
    {
        return $this->image_url_small;
    }

    public function set_image_url_small($value)
    {
        $this->image_url_small = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_short_desc()
    {
        return $this->short_desc;
    }

    public function set_short_desc($value)
    {
        $this->short_desc = $value;
    }


    public function get_availability()
    {
        return $this->availability;
    }

    public function set_availability($value)
    {
        $this->availability = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_image_url_large()
    {
        return $this->image_url_large;
    }

    public function set_image_url_large($value)
    {
        $this->image_url_large = $value;
    }

    public function get_rrp()
    {
        return $this->rrp;
    }

    public function set_rrp($value)
    {
        $this->rrp = $value;
    }

    public function get_discount()
    {
        return $this->discount;
    }

    public function set_discount($value)
    {
        $this->discount = $value;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
    }

}

/* End of file criteo_product_feed_dto.php */
/* Location: ./system/application/libraries/dto/criteo_product_feed_dto.php */