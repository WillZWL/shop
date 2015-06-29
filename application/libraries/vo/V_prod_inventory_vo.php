<?php
include_once 'Base_vo.php';

class V_prod_inventory_vo extends Base_vo
{

    //class variable
    private $prod_sku;
    private $inventory;

    //primary key
    private $primary_key = array();

    //auo increment
    private $increment_field = "";

    //instance method
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