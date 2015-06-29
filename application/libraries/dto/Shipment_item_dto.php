<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Shipment_item_dto extends Base_dto
{
    //instance variable
    private $sid;
    private $po_number;
    private $line_number;
    private $to_location;
    private $sku;
    private $qty;
    private $status;
    private $reason_code;
    private $create_on;
    private $create_by;

    //instance method
    public function get_sid()
    {
        return $this->sku;
    }

    public function set_sid($value)
    {
        $this->sku = $value;
    }

    public function get_po_number()
    {
        return $this->po_number;
    }

    public function set_po_number($value)
    {
        $this->po_number = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function get_to_location()
    {
        return $this->to_location;
    }

    public function set_to_location($value)
    {
        $this->to_location = $value;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_line_number()
    {
        return $this->name;
    }

    public function set_line_number($value)
    {
        $this->name = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_reason_code()
    {
        return $this->reason_code;
    }

    public function set_reason_code($value)
    {
        $this->reason_code = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }
}

?>