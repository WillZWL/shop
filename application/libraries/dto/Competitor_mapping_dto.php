<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Competitor_mapping_dto extends Base_dto
{
    private $competitor_id;
    private $competitor_name;
    private $country_id;
    private $comp_status;
    private $cmap_status;
    private $match;
    private $ext_sku;
    private $last_price;
    private $now_price;
    private $product_url;
    private $note_1;
    private $note_2;
    private $comp_stock_status;
    private $comp_ship_charge;
    private $reprice_min_margin;
    private $reprice_value;
    private $sourcefile_timestamp;
    private $cmap_create_on;
    private $cmap_create_at;
    private $cmap_create_by;
    private $cmap_modify_on;
    private $cmap_modify_at;
    private $cmap_modify_by;

    // extra variables needed
    private $platform_selling_price;
    private $sku;

    private $empty_field = '';


    public function __construct()
    {
        parent::__construct();
    }

    public function get_competitor_id()
    {
        return $this->competitor_id;
    }

    public function set_competitor_id($value)
    {
        $this->competitor_id = $value;
    }

    public function get_competitor_name()
    {
        return $this->competitor_name;
    }

    public function set_competitor_name($value)
    {
        $this->competitor_name = $value;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
    }

    public function get_comp_status()
    {
        return $this->comp_status;
    }

    public function set_comp_status($value)
    {
        $this->comp_status = $value;
    }

    public function get_cmap_status()
    {
        return $this->cmap_status;
    }

    public function set_cmap_status($value)
    {
        $this->cmap_status = $value;
    }

    public function get_match()
    {
        return $this->match;
    }

    public function set_match($value)
    {
        $this->match = $value;
    }

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
    }

    public function get_last_price()
    {
        return $this->last_price;
    }

    public function set_last_price($value)
    {
        $this->last_price = $value;
    }

    public function get_now_price()
    {
        return $this->now_price;
    }

    public function set_now_price($value)
    {
        $this->now_price = $value;
    }

    public function get_product_url()
    {
        return $this->product_url;
    }

    public function set_product_url($value)
    {
        $this->product_url = $value;
    }

    public function get_note_1()
    {
        return $this->note_1;
    }

    public function set_note_1($value)
    {
        $this->note_1 = $value;
    }

    public function get_note_2()
    {
        return $this->note_2;
    }

    public function set_note_2($value)
    {
        $this->note_2 = $value;
    }

    public function get_comp_stock_status()
    {
        return $this->comp_stock_status;
    }

    public function set_comp_stock_status($value)
    {
        $this->comp_stock_status = $value;
    }

    public function get_comp_ship_charge()
    {
        return $this->comp_ship_charge;
    }

    public function set_comp_ship_charge($value)
    {
        $this->comp_ship_charge = $value;
    }

    public function get_reprice_min_margin()
    {
        return $this->reprice_min_margin;
    }

    public function set_reprice_min_margin($value)
    {
        $this->reprice_min_margin = $value;
    }

    public function get_reprice_value()
    {
        return $this->reprice_value;
    }

    public function set_reprice_value($value)
    {
        $this->reprice_value = $value;
    }

    public function get_sourcefile_timestamp()
    {
        return $this->sourcefile_timestamp;
    }

    public function set_sourcefile_timestamp($value)
    {
        $this->sourcefile_timestamp = $value;
    }

    public function get_cmap_create_on()
    {
        return $this->cmap_create_on;
    }

    public function set_cmap_create_on($value)
    {
        $this->cmap_create_on = $value;
    }

    public function get_cmap_create_at()
    {
        return $this->cmap_create_at;
    }

    public function set_cmap_create_at($value)
    {
        $this->cmap_create_at = $value;
    }

    public function get_cmap_create_by()
    {
        return $this->cmap_create_by;
    }

    public function set_cmap_create_by($value)
    {
        $this->cmap_create_by = $value;
    }

    public function get_cmap_modify_on()
    {
        return $this->cmap_modify_on;
    }

    public function set_cmap_modify_on($value)
    {
        $this->cmap_modify_on = $value;
    }

    public function get_cmap_modify_at()
    {
        return $this->cmap_modify_at;
    }

    public function set_cmap_modify_at($value)
    {
        $this->cmap_modify_at = $value;
    }

    public function get_cmap_modify_by()
    {
        return $this->cmap_modify_by;
    }

    public function set_cmap_modify_by($value)
    {
        $this->cmap_modify_by = $value;
    }

    public function get_platform_selling_price()
    {
        return $this->platform_selling_price;
    }

    public function set_platform_selling_price($value)
    {
        $this->platform_selling_price = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

}
