<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'shipment_info_to_courier_dto.php';

class Dispatch_list_dto extends Shipment_info_to_courier_dto
{
    protected $warehouse_id;
    protected $bin;
    protected $empty;
    protected $no_info;

    public function __construct()
    {
        parent::Shipment_info_to_courier_dto();
    }

    public function set_warehouse_id($value)
    {
        $this->warehouse_id = $value;
    }

    public function get_warehouse_id()
    {
        return $this->warehouse_id;
    }

    public function set_bin($value)
    {
        $this->bin = $value;
    }

    public function get_bin()
    {
        return $this->bin;
    }

    public function set_empty($value)
    {
        $this->empty = "";
    }

    public function get_empty()
    {
        return "";
    }

    public function set_no_info($value)
    {
        $this->no_info = $value;
    }

    public function get_no_info()
    {
        return "no info";
    }

    public function get_order_create_date()
    {
        if (!empty($this->order_create_date) && (!is_null($this->order_create_date)))
        {
            $result = explode(" ", $this->order_create_date);
            return $result[0];
        }
        return "";
    }
}
