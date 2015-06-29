<?php
include_once 'Base_vo.php';

class Supplier_prod_vo extends Base_vo
{

    //class variable
    private $supplier_id;
    private $prod_sku;
    private $currency_id;
    private $cost;
    private $moq;
    private $order_default = '1';
    private $region_default;
    private $supplier_status = 'A';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("supplier_id", "prod_sku", "moq");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_supplier_id()
    {
        return $this->supplier_id;
    }

    public function set_supplier_id($value)
    {
        $this->supplier_id = $value;
        return $this;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
        return $this;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function get_cost()
    {
        return $this->cost;
    }

    public function set_cost($value)
    {
        $this->cost = $value;
        return $this;
    }

    public function get_moq()
    {
        return $this->moq;
    }

    public function set_moq($value)
    {
        $this->moq = $value;
        return $this;
    }

    public function get_order_default()
    {
        return $this->order_default;
    }

    public function set_order_default($value)
    {
        $this->order_default = $value;
        return $this;
    }

    public function get_region_default()
    {
        return $this->region_default;
    }

    public function set_region_default($value)
    {
        $this->region_default = $value;
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