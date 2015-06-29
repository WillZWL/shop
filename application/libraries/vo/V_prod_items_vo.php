<?php
include_once 'Base_vo.php';

class V_prod_items_vo extends Base_vo
{

    //class variable
    private $prod_sku;
    private $discount;
    private $item_sku;
    private $component_order;
    private $image;

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

    public function get_discount()
    {
        return $this->discount;
    }

    public function set_discount($value)
    {
        $this->discount = $value;
        return $this;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
        return $this;
    }

    public function get_component_order()
    {
        return $this->component_order;
    }

    public function set_component_order($value)
    {
        $this->component_order = $value;
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