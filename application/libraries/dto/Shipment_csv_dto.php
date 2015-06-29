<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Shipment_csv_dto extends Base_dto
{
    //instance variable
    private $shipment_id;
    private $sku;
    private $prod_name;
    private $qty;
    private $courier;
    private $tracking_no;

    public function set_shipment_id($value)
    {
        $this->shipment_id = $value;
        return $this;
    }

    public function get_shipment_id()
    {
        return $this->shipment_id;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
        return $this;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
        return $this;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
        return $this;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_courier($value)
    {
        $this->courier = $value;
        return $this;
    }

    public function get_courier()
    {
        return $this->courier;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
        return $this;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }
}

/* End of file shipment_csv_dto.php */
/* Location: ./system/application/libraries/dto/shipment_csv_dto.php */