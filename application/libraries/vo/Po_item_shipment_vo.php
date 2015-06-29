<?php
include_once 'Base_vo.php';

class Po_item_shipment_vo extends Base_vo
{

    //class variable
    private $sid;
    private $po_number;
    private $line_number;
    private $invm_trans_id;
    private $qty;
    private $to_location;
    private $received_qty = '0';
    private $reason_code;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $create_on = '0000-00-00 00:00:00';

    //primary key
    private $primary_key = array("sid", "po_number", "line_number");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_sid()
    {
        return $this->sid;
    }

    public function set_sid($value)
    {
        $this->sid = $value;
        return $this;
    }

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

    public function get_invm_trans_id()
    {
        return $this->invm_trans_id;
    }

    public function set_invm_trans_id($value)
    {
        $this->invm_trans_id = $value;
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

    public function get_to_location()
    {
        return $this->to_location;
    }

    public function set_to_location($value)
    {
        $this->to_location = $value;
        return $this;
    }

    public function get_received_qty()
    {
        return $this->received_qty;
    }

    public function set_received_qty($value)
    {
        $this->received_qty = $value;
        return $this;
    }

    public function get_reason_code()
    {
        return $this->reason_code;
    }

    public function set_reason_code($value)
    {
        $this->reason_code = $value;
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

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
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