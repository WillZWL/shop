<?php

include_once "Base_dto.php";

class Sli_prod_webfeed_dto extends Base_dto
{
    private $sku;
    private $priceGBP;
    private $RRPpriceGBP;
    private $priceEUR;
    private $RRPpriceEUR;
    private $website_quantity;
    private $website_status;
    private $bundle;

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

    public function get_priceGBP()
    {
            return $this->priceGBP;
    }

    public function set_priceGBP($value)
    {
            $this->priceGBP = $value;
    }

    public function get_RRPpriceGBP()
    {
            return $this->RRPpriceGBP;
    }

    public function set_RRPpriceGBP($value)
    {
            $this->RRPpriceGBP = $value;
    }

    public function get_priceEUR()
    {
            return $this->priceEUR;
    }

    public function set_priceEUR($value)
    {
            $this->priceEUR = $value;
    }

    public function get_RRPpriceEUR()
    {
            return $this->RRPpriceEUR;
    }

    public function set_RRPpriceEUR($value)
    {
            $this->RRPpriceEUR = $value;
    }

    public function get_website_quantity()
    {
            return $this->website_quantity;
    }

    public function set_website_quantity($value)
    {
            $this->website_quantity = $value;
    }

    public function get_website_status()
    {
            return $this->website_status;
    }

    public function set_website_status($value)
    {
            $this->website_status = $value;
    }

    public function get_bundle()
    {
        return $this->bundle;
    }

    public function set_bundle($value)
    {
        $this->bundle = $value;
    }
}

