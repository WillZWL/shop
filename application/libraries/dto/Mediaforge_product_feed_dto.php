<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Mediaforge_product_feed_dto extends Base_dto
{
    private $prod_id;
    private $prod_name;
    private $sku;
    private $cat_name;
    private $sec_cat_name;
    private $product_url;
    private $image_url;
    private $buy_url;
    private $short_desc;
    private $detail_desc;
    private $discount;
    private $disc_type;
    private $sale_price;
    private $price;
    private $begin_date;
    private $end_date;
    private $brand_name;
    private $shipping_fee;
    private $delete_flag;
    private $keyword;
    private $all_flag;
    private $mpn;
    private $manufacturer;
    private $shipping_info;
    private $stock_status;
    private $upc;
    private $class_id;
    private $prod_link_flag;
    private $storefront_flag;
    private $merc_flag;
    private $currency;
    private $m1;
    private $margin;

    private $empty_field = '';


    public function __construct()
    {
        parent::__construct();
    }

    public function get_prod_id()
    {
        return $this->prod_id;
    }

    public function set_prod_id($value)
    {
        $this->prod_id = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_sec_cat_name()
    {
        return $this->sec_cat_name;
    }

    public function set_sec_cat_name($value)
    {
        $this->sec_cat_name = $value;
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

    public function get_buy_url()
    {
        return $this->buy_url;
    }

    public function set_buy_url($value)
    {
        $this->buy_url = $value;
    }

    public function get_short_desc()
    {
        return $this->short_desc;
    }

    public function set_short_desc($value)
    {
        $this->short_desc = $value;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_discount()
    {
        return $this->discount;
    }

    public function set_discount($value)
    {
        $this->discount = $value;
    }

    public function get_disc_type()
    {
        return $this->disc_type;
    }

    public function set_disc_type($value)
    {
        $this->disc_type = $value;
    }

    public function get_sale_price()
    {
        return $this->sale_price;
    }

    public function set_sale_price($value)
    {
        $this->sale_price = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_begin_date()
    {
        return $this->begin_date;
    }

    public function set_begin_date($value)
    {
        $this->begin_date = $value;
    }

    public function get_end_date()
    {
        return $this->end_date;
    }

    public function set_end_date($value)
    {
        $this->end_date = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_shipping_fee()
    {
        return $this->shipping_fee;
    }

    public function set_shipping_fee($value)
    {
        $this->shipping_fee = $value;
    }

    public function get_delete_flag()
    {
        return $this->delete_flag;
    }

    public function set_delete_flag($value)
    {
        $this->delete_flag = $value;
    }

    public function get_keyword()
    {
        return $this->keyword;
    }

    public function set_keyword($value)
    {
        $this->keyword = $value;
    }

    public function get_all_flag()
    {
        return $this->all_flag;
    }

    public function set_all_flag($value)
    {
        $this->all_flag = $value;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

    public function get_manufacturer()
    {
        return $this->manufacturer;
    }

    public function set_manufacturer($value)
    {
        $this->manufacturer = $value;
    }

    public function get_shipping_info()
    {
        return $this->shipping_info;
    }

    public function set_shipping_info($value)
    {
        $this->shipping_info = $value;
    }

    public function get_stock_status()
    {
        return $this->stock_status;
    }

    public function set_stock_status($value)
    {
        $this->stock_status = $value;
    }

    public function get_upc()
    {
        return $this->upc;
    }

    public function set_upc($value)
    {
        $this->upc = $value;
    }

    public function get_class_id()
    {
        return $this->class_id;
    }

    public function set_class_id($value)
    {
        $this->class_id = $value;
    }

    public function get_prod_link_flag()
    {
        return $this->prod_link_flag;
    }

    public function set_prod_link_flag($value)
    {
        $this->prod_link_flag = $value;
    }

    public function get_storefront_flag()
    {
        return $this->storefront_flag;
    }

    public function set_storefront_flag($value)
    {
        $this->storefront_flag = $value;
    }

    public function get_merc_flag()
    {
        return $this->merc_flag;
    }

    public function set_merc_flag($value)
    {
        $this->merc_flag = $value;
    }

    public function get_currency()
    {
        return $this->currency;
    }

    public function set_currency($value)
    {
        $this->currency = $value;
    }

    public function get_m1()
    {
        return $this->m1;
    }

    public function set_m1($value)
    {
        $this->m1 = $value;
    }

    public function get_margin()
    {
        return $this->margin;
    }

    public function set_margin($value)
    {
        $this->margin = $value;
    }

    public function get_empty_field()
    {
        return $this->empty_field;
    }

    public function set_empty_field($value)
    {
        $this->empty_field = $value;
    }



}
