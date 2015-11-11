<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Product_list_w_name_dto extends Base_dto
{

    //class variable
    private $sku;
    private $name;
    private $image_file;
    private $price;
    private $rrp;
    private $ext;
    private $website_status;
    private $colour;
    private $category;
    private $sub_cat;
    private $sub_sub_cat;
    private $brand;
    private $proc_status;
    private $status;
    private $website_quantity;
    private $quantity;
    private $master_sku;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $warranty_in_month;

    //instance method
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
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_image_file()
    {
        return $this->image_file;
    }

    public function set_image_file($value)
    {
        $this->image_file = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_ext()
    {
        return $this->ext;
    }

    public function set_ext($value)
    {
        $this->ext = $value;
    }

    public function get_rrp()
    {
        return $this->rrp;
    }

    public function set_rrp($value)
    {
        $this->rrp = $value;
    }

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status = $value;
    }

    public function get_colour()
    {
        return $this->colour;
    }

    public function set_colour($value)
    {
        $this->colour = $value;
    }

    public function get_category()
    {
        return $this->category;
    }

    public function set_category($value)
    {
        $this->category = $value;
    }

    public function get_sub_cat()
    {
        return $this->sub_cat;
    }

    public function set_sub_cat($value)
    {
        $this->sub_cat = $value;
    }

    public function get_sub_sub_cat()
    {
        return $this->sub_sub_cat;
    }

    public function set_sub_sub_cat($value)
    {
        $this->sub_sub_cat = $value;
    }

    public function get_brand()
    {
        return $this->brand;
    }

    public function set_brand($value)
    {
        $this->brand = $value;
    }

    public function get_proc_status()
    {
        return $this->proc_status;
    }

    public function set_proc_status($value)
    {
        $this->proc_status = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_website_quantity()
    {
        return $this->website_quantity;
    }

    public function set_website_quantity($value)
    {
        $this->website_quantity = $value;
    }

    public function get_quantity()
    {
        return $this->quantity;
    }

    public function set_quantity($value)
    {
        $this->quantity = $value;
    }

    public function get_master_sku()
    {
        return $this->master_sku;
    }

    public function set_master_sku($value)
    {
        $this->master_sku = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
    }

    public function get_warranty_in_month()
    {
        return $this->warranty_in_month;
    }

    public function set_warranty_in_month($value)
    {
        $this->warranty_in_month = $value;
    }
}


