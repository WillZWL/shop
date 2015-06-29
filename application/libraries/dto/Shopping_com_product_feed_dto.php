<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Shopping_com_product_feed_dto extends Base_dto
{

    private $sku;
    private $name;
    private $product_url;
    private $image_url;
    private $price;
    private $cat_name;
    private $stock_status;
    private $shipping_rate;
    private $mpn;
    private $ean;
    private $prod_condition;
    private $brand_name;
    private $detail_desc;
    private $stock_desc;
    private $merc_type;
    private $is_bundle;

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

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
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

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }
    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_stock_status()
    {
        return $this->stock_status;
    }

    public function set_stock_status($value)
    {
        $this->stock_status = $value;
    }

    public function get_shipping_rate()
    {
        return $this->shipping_rate;
    }

    public function set_shipping_rate($value)
    {
        $this->shipping_rate = $value;
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

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_stock_desc()
    {
        return $this->stock_desc;
    }

    public function set_stock_desc($value)
    {
        $this->stock_desc = $value;
    }

    public function get_merc_type()
    {
        return $this->merc_type;
    }

    public function set_merc_type($value)
    {
        $this->merc_type = $value;
    }

    public function get_is_bundle()
    {
        return $this->is_bundle;
    }

    public function set_is_bundle($value)
    {
        $this->is_bundle = $value;
    }

}

/* End of file shopping_com_product_feed_dto.php */
/* Location: ./system/application/libraries/dto/shopping_com_product_feed_dto.php */