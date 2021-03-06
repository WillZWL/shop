<?php

include_once "Base_dto.php";

class Amazon_prod_feed_dto extends Base_dto
{
    //private variables
    private $sku;
    private $price;
    private $platform_code;
    private $name;
    private $prod_name;
    private $brand_name;
    private $contents;
    private $mpn;
    private $weight;
    private $moq;
    private $keywords;
    private $quantity;
    private $latency;
    private $oos_latency;
    private $latency_in_stock;
    private $latency_out_of_stock;
    private $clearance;
    private $auto_price;
    private $shiptype;
    private $inv_qty;
    private $condition;
    private $condition_note;
    private $ext_qty;
    private $listing_status;

    //instant methods
    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_platform_code()
    {
        return $this->platform_code;
    }

    public function set_platform_code($value)
    {
        $this->platform_code = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_contents()
    {
        return $this->contents;
    }

    public function set_contents($value)
    {
        $this->contents = $value;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

    public function get_weight()
    {
        return $this->weight;
    }

    public function set_weight($value)
    {
        $this->weight = $value;
    }

    public function get_moq()
    {
        return $this->moq;
    }

    public function set_moq($value)
    {
        $this->moq = $value;
    }

    public function get_keywords()
    {
        return $this->keywords;
    }

    public function set_keywords($value)
    {
        $this->keywords = $value;
    }

    public function get_quantity()
    {
        return $this->quantity;
    }

    public function set_quantity($value)
    {
        $this->quantity = $value;
    }

    public function get_latency()
    {
        return $this->latency;
    }

    public function set_latency($value)
    {
        $this->latency = $value;
    }

    public function get_oos_latency()
    {
        return $this->oos_latency;
    }

    public function set_oos_latency($value)
    {
        $this->oos_latency = $value;
    }

    public function get_latency_in_stock()
    {
        return $this->latency_in_stock;
    }

    public function set_latency_in_stock($value)
    {
        $this->latency_in_stock = $value;
    }

    public function get_latency_out_of_stock()
    {
        return $this->latency_out_of_stock;
    }

    public function set_latency_out_of_stock($value)
    {
        $this->latency_out_of_stock = $value;
    }

    public function get_clearance()
    {
        return $this->clearance;
    }

    public function set_clearance($value)
    {
        $this->clearance = $value;
    }

    public function get_auto_price()
    {
        return $this->auto_price;
    }

    public function set_auto_price($value)
    {
        $this->auto_price = $value;
    }

    public function get_shiptype()
    {
        return $this->shiptype;
    }

    public function set_shiptype($value)
    {
        $this->shiptype = $value;
    }

    public function get_inv_qty()
    {
        return $this->inv_qty;
    }

    public function set_inv_qty($value)
    {
        $this->inv_qty = $value;
    }

    public function get_condition()
    {
        return $this->condition;
    }

    public function set_condition($value)
    {
        $this->condition = $value;
    }

    public function get_condition_note()
    {
        return $this->condition_note;
    }

    public function set_condition_note($value)
    {
        $this->condition_note = $value;
    }

    public function get_ext_qty()
    {
        return $this->ext_qty;
    }

    public function set_ext_qty($value)
    {
        $this->ext_qty = $value;
    }

    public function get_listing_status()
    {
        return $this->listing_status;
    }

    public function set_listing_status($value)
    {
        $this->listing_status = $value;
    }
}
