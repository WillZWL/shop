<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class My_shopping_com_au_product_feed_dto extends Base_dto
{

    private $sku;
    private $prod_name;
    private $detail_desc;
    private $cat_name;
    private $price;
    private $product_url;
    private $image_url;
    private $brand_name;
    private $shipping;
    private $instock;
    private $mpn;
    private $platform_id;

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

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
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

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_shipping()
    {
        return $this->shipping;
    }

    public function set_shipping($value)
    {
        $this->shipping = $value;
    }

    public function get_instock()
    {
        return $this->instock;
    }

    public function set_instock($value)
    {
        $this->instock = $value;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }
}


