<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dto.php';

class Prod_supplier_info_dto extends Base_dto
{
    private $sku;
    private $name;
    private $surplus_quantity;
    private $slow_move_7_days;
    private $sourcing_status;
    private $platform_id;
    private $price;
    private $supplier_id;
    private $supplier_status;
    private $origin_country;
    private $supplier_name;
    private $git;

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

    public function get_sourcing_status()
    {
        return $this->sourcing_status;
    }

    public function set_sourcing_status($value)
    {
        $this->sourcing_status = $value;
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

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
        return $this;
    }

    public function get_supplier_id()
    {
        return $this->supplier_id;
    }

    public function set_supplier_id($value)
    {
        $this->supplier_id = $value;
        return $this;
    }

    public function get_supplier_status()
    {
        return $this->supplier_status;
    }

    public function set_supplier_status($value)
    {
        $this->supplier_status = $value;
        return $this;
    }

    public function get_origin_country()
    {
        return $this->origin_country;
    }

    public function set_origin_country($value)
    {
        $this->origin_country = $value;
        return $this;
    }

    public function get_supplier_name()
    {
        return $this->supplier_name;
    }

    public function set_supplier_name($value)
    {
        $this->supplier_name = $value;
        return $this;
    }

    public function get_git()
    {
        return $this->git;
    }

    public function set_git($value)
    {
        $this->git = $value;
        return $this;
    }
}


