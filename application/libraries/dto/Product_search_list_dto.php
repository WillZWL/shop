<?php
include_once 'Base_dto.php';

class Product_search_list_dto extends Base_dto
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
    private $clearance;
    private $quantity;
    private $display_quantity;
    private $website_quantity;
    private $ex_demo;
    private $rrp;
    private $image;
    private $flash;
    private $youtube_id;
    private $ean;
    private $mpn;
    private $upc;
    private $discount;
    private $proc_status;
    private $website_status;
    private $sourcing_status;
    private $status;
    private $with_bundle;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $prod_name;
    private $cat_name;
    private $brand_name;
    private $short_desc;
    private $detail_desc;
    private $price;
    private $sign;
    private $sign_pos;
    private $dec_place;
    private $dec_point;
    private $thousands_sep;
    private $sold_amount;
    private $price_range;
    private $num;


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

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
        return $this;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
        return $this;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
        return $this;
    }

    public function get_short_desc()
    {
        return $this->short_desc;
    }

    public function set_short_desc($value)
    {
        $this->short_desc = $value;
        return $this;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
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

    public function get_sign()
    {
        return $this->sign;
    }

    public function set_sign($value)
    {
        $this->sign = $value;
        return $this;
    }

    public function get_sign_pos()
    {
        return $this->sign_pos;
    }

    public function set_sign_pos($value)
    {
        $this->sign_pos = $value;
        return $this;
    }

    public function get_dec_place()
    {
        return $this->dec_place;
    }

    public function set_dec_place($value)
    {
        $this->dec_place = $value;
        return $this;
    }

    public function get_dec_point()
    {
        return $this->dec_point;
    }

    public function set_dec_point($value)
    {
        $this->dec_point = $value;
        return $this;
    }

    public function get_thousands_sep()
    {
        return $this->thousands_sep;
    }

    public function set_thousands_sep($value)
    {
        $this->thousands_sep = $value;
        return $this;
    }

    public function get_sold_amount()
    {
        return $this->sold_amount;
    }

    public function set_sold_amount($value)
    {
        $this->sold_amount = $value;
        return $this;
    }

    public function get_num()
    {
        return $this->num;
    }

    public function set_num($value)
    {
        $this->num = $value;
        return $this;
    }

    public function get_price_range()
    {
        return $this->price_range;
    }

    public function set_price_range($value)
    {
        $this->price_range = $value;
        return $this;
    }

    public function get_with_bundle()
    {
        return $this->with_bundle;
    }

    public function set_with_bundle($value)
    {
        $this->with_bundle = $value;
        return $this;
    }
}


