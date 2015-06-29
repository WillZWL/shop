<?php
include_once 'Base_vo.php';

class Refund_item_vo extends Base_vo
{

    //class variable
    private $refund_id;
    private $line_no;
    private $item_sku;
    private $qty;
    private $refund_amount = '0.00';
    private $status = 'CS';
    private $refund_type = 'C';
    private $item_status;
    private $stockback_date;
    private $stockback_warehouse;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("refund_id", "line_no");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_refund_id()
    {
        return $this->refund_id;
    }

    public function set_refund_id($value)
    {
        $this->refund_id = $value;
        return $this;
    }

    public function get_line_no()
    {
        return $this->line_no;
    }

    public function set_line_no($value)
    {
        $this->line_no = $value;
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

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
        return $this;
    }

    public function get_refund_amount()
    {
        return $this->refund_amount;
    }

    public function set_refund_amount($value)
    {
        $this->refund_amount = $value;
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

    public function get_refund_type()
    {
        return $this->refund_type;
    }

    public function set_refund_type($value)
    {
        $this->refund_type = $value;
        return $this;
    }

    public function get_item_status()
    {
        return $this->item_status;
    }

    public function set_item_status($value)
    {
        $this->item_status = $value;
        return $this;
    }

    public function get_stockback_date()
    {
        return $this->stockback_date;
    }

    public function set_stockback_date($value)
    {
        $this->stockback_date = $value;
        return $this;
    }

    public function get_stockback_warehouse()
    {
        return $this->stockback_warehouse;
    }

    public function set_stockback_warehouse($value)
    {
        $this->stockback_warehouse = $value;
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