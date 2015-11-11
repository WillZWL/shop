<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Product_category_dto extends Base_dto
{
    private $ext_sku;
    private $name;
    private $brand_name;
    private $cat_name;
    private $sub_cat_name;
    private $sub_sub_cat_name;

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
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

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
    }

    public function get_sub_sub_cat_name()
    {
        return $this->sub_sub_cat_name;
    }

    public function set_sub_sub_cat_name($value)
    {
        $this->sub_sub_cat_name = $value;
    }
}

?>