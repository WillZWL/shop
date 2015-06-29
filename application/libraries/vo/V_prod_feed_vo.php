<?php
include_once 'Base_vo.php';

class V_prod_feed_vo extends Base_vo
{

    //class variable
    private $platform_id;
    private $sku;
    private $prod_grp_cd;
    private $version_id;
    private $colour_id;
    private $colour_name;
    private $platform_country_id;
    private $prod_name;
    private $cat_id;
    private $cat_name;
    private $sub_cat_id;
    private $sub_cat_name;
    private $brand_id;
    private $brand_name;
    private $mpn;
    private $upc;
    private $ean;
    private $short_desc;
    private $detail_desc;
    private $contents;
    private $prod_weight;
    private $image;
    private $platform_currency_id;
    private $price;
    private $default_platform_converted_price;
    private $quantity = '0';
    private $display_quantity = '0';
    private $website_quantity = '0';
    private $website_status = 'I';
    private $prod_status = '1';
    private $listing_status = 'N';
    private $ex_demo = '0';

    //primary key
    private $primary_key = array();

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

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

    public function get_version_id()
    {
        return $this->version_id;
    }

    public function set_version_id($value)
    {
        $this->version_id = $value;
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

    public function get_colour_name()
    {
        return $this->colour_name;
    }

    public function set_colour_name($value)
    {
        $this->colour_name = $value;
        return $this;
    }

    public function get_platform_country_id()
    {
        return $this->platform_country_id;
    }

    public function set_platform_country_id($value)
    {
        $this->platform_country_id = $value;
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

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
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

    public function get_sub_cat_id()
    {
        return $this->sub_cat_id;
    }

    public function set_sub_cat_id($value)
    {
        $this->sub_cat_id = $value;
        return $this;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
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

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
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

    public function get_ean()
    {
        return $this->ean;
    }

    public function set_ean($value)
    {
        $this->ean = $value;
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

    public function get_contents()
    {
        return $this->contents;
    }

    public function set_contents($value)
    {
        $this->contents = $value;
        return $this;
    }

    public function get_prod_weight()
    {
        return $this->prod_weight;
    }

    public function set_prod_weight($value)
    {
        $this->prod_weight = $value;
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

    public function get_platform_currency_id()
    {
        return $this->platform_currency_id;
    }

    public function set_platform_currency_id($value)
    {
        $this->platform_currency_id = $value;
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

    public function get_default_platform_converted_price()
    {
        return $this->default_platform_converted_price;
    }

    public function set_default_platform_converted_price($value)
    {
        $this->default_platform_converted_price = $value;
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

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status = $value;
        return $this;
    }

    public function get_prod_status()
    {
        return $this->prod_status;
    }

    public function set_prod_status($value)
    {
        $this->prod_status = $value;
        return $this;
    }

    public function get_listing_status()
    {
        return $this->listing_status;
    }

    public function set_listing_status($value)
    {
        $this->listing_status = $value;
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