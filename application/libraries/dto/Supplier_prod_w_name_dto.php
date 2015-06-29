<?php
include_once 'Base_dto.php';

class Supplier_prod_w_name_dto extends Base_dto
{

    //class variable
    private $supplier_id;
    private $prod_sku;
    private $currency_id;
    private $cost;
    private $moq;
    private $order_default;
    private $region_default;
    private $supplier_status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $supplier_name;
    private $origin_country;
    private $region_name;
    private $creditor;
    private $sourcing_reg;
    private $total_cost;

    function __construct()
    {
        parent::__construct();
    }

    //instance method
    public function get_supplier_id()
    {
        return $this->supplier_id;
    }

    public function set_supplier_id($value)
    {
        $this->supplier_id = $value;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_cost()
    {
        return $this->cost;
    }

    public function set_cost($value)
    {
        $this->cost = $value;
    }

    public function get_moq()
    {
        return $this->moq;
    }

    public function set_moq($value)
    {
        $this->moq = $value;
    }

    public function get_order_default()
    {
        return $this->order_default;
    }

    public function set_order_default($value)
    {
        $this->order_default = $value;
    }

    public function get_region_default()
    {
        return $this->region_default;
    }

    public function set_region_default($value)
    {
        $this->region_default = $value;
    }

    public function get_supplier_status()
    {
        return $this->supplier_status;
    }

    public function set_supplier_status($value)
    {
        $this->supplier_status = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
    }

    public function get_supplier_name()
    {
        return $this->supplier_name;
    }

    public function set_supplier_name($value)
    {
        $this->supplier_name = $value;
    }

    public function get_origin_country()
    {
        return $this->origin_country;
    }

    public function set_origin_country($value)
    {
        $this->origin_country = $value;
    }

    public function get_region_name()
    {
        return $this->region_name;
    }

    public function set_region_name($value)
    {
        $this->region_name = $value;
    }

    public function get_creditor()
    {
        return $this->creditor;
    }

    public function set_creditor($value)
    {
        $this->creditor = $value;
    }

    public function get_sourcing_reg()
    {
        return $this->sourcing_reg;
    }

    public function set_sourcing_reg($value)
    {
        $this->sourcing_reg = $value;
    }

    public function get_total_cost()
    {
        return $this->total_cost;
    }

    public function set_total_cost($value)
    {
        $this->total_cost = $value;
    }

}

