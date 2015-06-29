<?php
include_once 'Base_vo.php';

class Wms_inventory_vo extends Base_vo
{

    //class variable
    private $warehouse_id;
    private $prod_sku;
    private $inventory;
    private $git;
    private $modify_on;

    //primary key
    private $primary_key = array("warehouse_id", "prod_sku");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_warehouse_id()
    {
        return $this->warehouse_id;
    }

    public function set_warehouse_id($value)
    {
        $this->warehouse_id = $value;
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

    public function get_inventory()
    {
        return $this->inventory;
    }

    public function set_inventory($value)
    {
        $this->inventory = $value;
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

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
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