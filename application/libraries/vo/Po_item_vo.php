<?php
include_once 'Base_vo.php';

class Po_item_vo extends Base_vo
{

    //class variable
    private $po_number = '';
    private $line_number = '0';
    private $sku;
    private $order_qty = '0';
    private $shipped_qty = '0';
    private $unit_price = '0.00';
    private $status = 'A';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("po_number", "line_number");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_po_number()
    {
        return $this->po_number;
    }

    public function set_po_number($value)
    {
        $this->po_number = $value;
        return $this;
    }

    public function get_line_number()
    {
        return $this->line_number;
    }

    public function set_line_number($value)
    {
        $this->line_number = $value;
        return $this;
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

    public function get_order_qty()
    {
        return $this->order_qty;
    }

    public function set_order_qty($value)
    {
        $this->order_qty = $value;
        return $this;
    }

    public function get_shipped_qty()
    {
        return $this->shipped_qty;
    }

    public function set_shipped_qty($value)
    {
        $this->shipped_qty = $value;
        return $this;
    }

    public function get_unit_price()
    {
        return $this->unit_price;
    }

    public function set_unit_price($value)
    {
        $this->unit_price = $value;
        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
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