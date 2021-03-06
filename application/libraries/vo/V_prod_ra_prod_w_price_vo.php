<?php
include_once 'Base_vo.php';

class V_prod_ra_prod_w_price_vo extends Base_vo
{

    //class variable
    private $sku;
    private $ra_sku;
    private $prod_grp_cd;
    private $prod_name;
    private $platform_id;
    private $shiptype = '0';
    private $quantity = '0';
    private $clearance = '0';
    private $website_quantity = '0';
    private $proc_status = '0';
    private $website_status = 'I';
    private $sourcing_status = 'A';
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $brand_id;
    private $image;
    private $freight_cat_id;
    private $ean;
    private $mpn;
    private $upc;
    private $prod_status = '1';
    private $display_quantity = '0';
    private $youtube_id;
    private $platform_currency_id;
    private $price;
    private $current_platform_price = '0.00';
    private $default_platform_converted_price;
    private $platform_code;
    private $listing_status = 'N';
    private $language_id;
    private $content_prod_name;
    private $sub_cat;

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

    public function get_ra_sku()
    {
        return $this->ra_sku;
    }

    public function set_ra_sku($value)
    {
        $this->ra_sku = $value;
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

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
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

    public function get_shiptype()
    {
        return $this->shiptype;
    }

    public function set_shiptype($value)
    {
        $this->shiptype = $value;
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

    public function get_clearance()
    {
        return $this->clearance;
    }

    public function set_clearance($value)
    {
        $this->clearance = $value;
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

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
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

    public function get_prod_status()
    {
        return $this->prod_status;
    }

    public function set_prod_status($value)
    {
        $this->prod_status = $value;
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

    public function get_youtube_id()
    {
        return $this->youtube_id;
    }

    public function set_youtube_id($value)
    {
        $this->youtube_id = $value;
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

    public function get_current_platform_price()
    {
        return $this->current_platform_price;
    }

    public function set_current_platform_price($value)
    {
        $this->current_platform_price = $value;
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

    public function get_platform_code()
    {
        return $this->platform_code;
    }

    public function set_platform_code($value)
    {
        $this->platform_code = $value;
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

    public function get_language_id()
    {
        return $this->language_id;
    }

    public function set_language_id($value)
    {
        $this->language_id = $value;
        return $this;
    }

    public function get_content_prod_name()
    {
        return $this->content_prod_name;
    }

    public function set_content_prod_name($value)
    {
        $this->content_prod_name = $value;
        return $this;
    }

    public function get_sub_cat()
    {
        return $this->sub_cat;
    }

    public function set_sub_cat($value)
    {
        $this->sub_cat = $value;
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