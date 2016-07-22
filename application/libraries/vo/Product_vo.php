<?php
include_once 'Base_vo.php';

class Product_vo extends Base_vo
{
    //class variable
    private $sku;
    private $prod_grp_cd;
    private $colour_id;
    private $version_id;
    private $name;
    private $freight_cat_id;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $brand_id;
    private $clearance = '0';
    private $quantity = '0';
    private $display_quantity = '0';
    private $website_quantity = '0';
    private $ex_demo = '0';
    private $china_oem = '0';
    private $rrp = '0.00';
    private $image;
    private $flash;
    private $youtube_id;
    private $ean;
    private $mpn;
    private $upc;
    private $discount;
    private $proc_status = '0';
    private $website_status = 'I';
    private $sourcing_status = 'A';
    private $warranty_in_month;
    private $expected_delivery_date;
    private $cat_upselling;
    private $shipment_restricted_type;
    private $lang_restricted;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;
    private $surplus_quantity = 0;
    private $slow_move_7_days;
    private $accelerator_salesrpt_bd;
    private $accelerator;
    //primary key
    private $primary_key = array("sku");

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

    public function get_prod_grp_cd()
    {
        return $this->prod_grp_cd;
    }

    public function set_prod_grp_cd($value)
    {
        $this->prod_grp_cd = $value;
        return $this;
    }

    public function get_colour_id()
    {
        return $this->colour_id;
    }

    public function set_colour_id($value)
    {
        $this->colour_id = $value;
        return $this;
    }

    public function get_version_id()
    {
        return $this->version_id;
    }

    public function set_version_id($value)
    {
        $this->version_id = $value;
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

    public function get_freight_cat_id()
    {
        return $this->freight_cat_id;
    }

    public function set_freight_cat_id($value)
    {
        $this->freight_cat_id = $value;
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

    public function get_clearance()
    {
        return $this->clearance;
    }

    public function set_clearance($value)
    {
        $this->clearance = $value;
        return $this;
    }

    public function get_surplus_quantity()
    {
        return $this->surplus_quantity;
    }

    public function set_surplus_quantity($value)
    {
        $this->surplus_quantity = $value;
        return $this;
    }

    public function get_slow_move_7_days()
    {
        return $this->slow_move_7_days;
    }

    public function set_slow_move_7_days($value)
    {
        $this->slow_move_7_days = $value;
        return $this;
    }

    public function get_quantity()
    {
        return $this->quantity;
    }

    public function set_quantity($value)
    {
        $this->quantity = $value;
        return $this;
    }

    public function get_display_quantity()
    {
        return $this->display_quantity;
    }

    public function set_display_quantity($value)
    {
        $this->display_quantity = $value;
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

    public function get_ex_demo()
    {
        return $this->ex_demo;
    }

    public function set_ex_demo($value)
    {
        $this->ex_demo = $value;
        return $this;
    }

    public function get_china_oem()
    {
        return $this->china_oem;
    }

    public function set_china_oem($value)
    {
        $this->china_oem = $value;
        return $this;
    }

    public function get_rrp()
    {
        return $this->rrp;
    }

    public function set_rrp($value)
    {
        $this->rrp = $value;
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

    public function get_flash()
    {
        return $this->flash;
    }

    public function set_flash($value)
    {
        $this->flash = $value;
        return $this;
    }

    public function get_youtube_id()
    {
        return $this->youtube_id;
    }

    public function set_youtube_id($value)
    {
        $this->youtube_id = $value;
        return $this;
    }

    public function get_ean()
    {
        return $this->ean;
    }

    public function set_ean($value)
    {
        $this->ean = $value;
        return $this;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
        return $this;
    }

    public function get_upc()
    {
        return $this->upc;
    }

    public function set_upc($value)
    {
        $this->upc = $value;
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

    public function get_proc_status()
    {
        return $this->proc_status;
    }

    public function set_proc_status($value)
    {
        $this->proc_status = $value;
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

    public function get_sourcing_status()
    {
        return $this->sourcing_status;
    }

    public function set_sourcing_status($value)
    {
        $this->sourcing_status = $value;
        return $this;
    }

    public function get_warranty_in_month()
    {
        return $this->warranty_in_month;
    }

    public function set_warranty_in_month($value)
    {
        $this->warranty_in_month = $value;
        return $this;
    }

    public function get_expected_delivery_date()
    {
        return $this->expected_delivery_date;
    }

    public function set_expected_delivery_date($value)
    {
        $this->expected_delivery_date = $value;
        return $this;
    }

    public function get_cat_upselling()
    {
        return $this->cat_upselling;
    }

    public function set_cat_upselling($value)
    {
        $this->cat_upselling = $value;
        return $this;
    }

    public function get_lang_restricted()
    {
        return $this->lang_restricted;
    }

    public function set_lang_restricted($value)
    {
        $this->lang_restricted = $value;
        return $this;
    }

    public function get_shipment_restricted_type()
    {
        return $this->shipment_restricted_type;
    }

    public function set_shipment_restricted_type($value)
    {
        $this->shipment_restricted_type = $value;
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

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function get_accelerator_salesrpt_bd()
    {
        return $this->accelerator_salesrpt_bd;
    }

    public function set_accelerator_salesrpt_bd($value)
    {
        $this->accelerator_salesrpt_bd = $value;
        return $this;
    }

    public function get_accelerator()
    {
        return $this->accelerator;
    }

    public function set_accelerator($value)
    {
        $this->accelerator = $value;
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
