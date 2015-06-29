<?php
include_once 'Base_vo.php';

class Temp_barcode_mapping_vo extends Base_vo
{

    //class variable
    private $se_sku;
    private $master_sku;
    private $ean;
    private $ean_us;
    private $mpn;
    private $upc;

    //primary key
    private $primary_key = array("se_sku", "master_sku");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_se_sku()
    {
        return $this->se_sku;
    }

    public function set_se_sku($value)
    {
        $this->se_sku = $value;
        return $this;
    }

    public function get_master_sku()
    {
        return $this->master_sku;
    }

    public function set_master_sku($value)
    {
        $this->master_sku = $value;
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

    public function get_ean_us()
    {
        return $this->ean_us;
    }

    public function set_ean_us($value)
    {
        $this->ean_us = $value;
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