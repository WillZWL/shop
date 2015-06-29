<?php
include_once 'Base_vo.php';

class V_prod_w_total_price1_vo extends Base_vo
{

    //class variable
    private $sku;
    private $name;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $brand_id;
    private $website_quantity = '0';
    private $image;
    private $discount;
    private $website_status = 'I';
    private $status = '1';
    private $platform_id;
    private $price;

    //primary key
    private $primary_key = array();

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
        return $this;
    }

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
        return $this;
    }

    public function get_sub_cat_id()
    {
        return $this->sub_cat_id;
    }

    public function set_sub_cat_id($value)
    {
        $this->sub_cat_id = $value;
        return $this;
    }

    public function get_sub_sub_cat_id()
    {
        return $this->sub_sub_cat_id;
    }

    public function set_sub_sub_cat_id($value)
    {
        $this->sub_sub_cat_id = $value;
        return $this;
    }

    public function get_brand_id()
    {
        return $this->brand_id;
    }

    public function set_brand_id($value)
    {
        $this->brand_id = $value;
        return $this;
    }

    public function get_website_quantity()
    {
        return $this->website_quantity;
    }

    public function set_website_quantity($value)
    {
        $this->website_quantity = $value;
        return $this;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
        return $this;
    }

    public function get_discount()
    {
        return $this->discount;
    }

    public function set_discount($value)
    {
        $this->discount = $value;
        return $this;
    }

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status = $value;
        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

}
?>