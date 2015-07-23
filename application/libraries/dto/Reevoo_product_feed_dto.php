<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Reevoo_product_feed_dto extends Base_dto
{

    private $brand_name;
    private $mpn;
    private $sku;
    private $prod_name;
    private $image;
    private $cat_name;
    private $sub_cat_name;
    private $ean;
    private $product_category;
    private $model;
    private $image_url;

    public function __construct()
    {
        parent::__construct();
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

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
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

    public function get_ean()
    {
        return $this->ean;
    }

    public function set_ean($value)
    {
        $this->ean = $value;
    }

    public function get_product_category()
    {
        return $this->product_category;
    }

    public function set_product_category($value)
    {
        $this->product_category = $value;
    }

    public function get_model()
    {
        return $this->model;
    }

    public function set_model($value)
    {
        $this->model = $value;
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


