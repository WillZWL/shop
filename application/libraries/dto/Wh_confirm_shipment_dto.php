<?php

include_once 'Base_dto.php';

Class Wh_confirm_shipment_dto extends Base_dto
{

    private $shipment_id;
    private $tracking_no;
    private $courier;
    private $log_sku;
    private $sku;
    private $trans_id;
    private $prod_name;
    private $shipped_qty;
    private $ordered_qty;
    private $received_qty;
    private $supplier_id;
    private $supplier_name;
    private $detail;
    private $reason;
    private $remarks;
    private $delivery_mode;

    public function get_shipment_id()
    {
        return $this->shipment_id;
    }

    public function set_shipment_id($value)
    {
        $this->shipment_id = $value;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
    }

    public function get_courier()
    {
        return $this->courier;
    }

    public function set_courier($value)
    {
        $this->courier = $value;
    }

    public function get_log_sku()
    {
        return $this->log_sku;
    }

    public function set_log_sku($value)
    {
        $this->log_sku = $value;
    }

    public function get_trans_id()
    {
        return $this->trans_id;
    }

    public function set_trans_id($value)
    {
        $this->trans_id = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_shipped_qty()
    {
        return $this->shipped_qty;
    }

    public function set_shipped_qty($value)
    {
        $this->shipped_qty = $value;
    }

    public function get_ordered_qty()
    {
        return $this->ordered_qty;
    }

    public function set_ordered_qty($value)
    {
        $this->ordered_qty = $value;
    }

    public function get_received_qty()
    {
        return $this->received_qty;
    }

    public function set_received_qty($value)
    {
        $this->received_qty = $value;
    }

    public function get_supplier_id()
    {
        return $this->supplier_id;
    }

    public function set_supplier_id($value)
    {
        $this->supplier_id = $value;
    }

    public function get_supplier_name()
    {
        return $this->supplier_name;
    }

    public function set_supplier_name($value)
    {
        $this->supplier_name = $value;
    }

    public function get_detail()
    {
        return $this->detail;
    }

    public function set_detail($value)
    {
        $this->detail = $value;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }

    public function get_remarks()
    {
        return $this->remarks;
    }

    public function set_remarks($value)
    {
        $this->remarks = $value;
    }

    public function get_delivery_mode()
    {
        return $this->delivery_mode;
    }

    public function set_delivery_mode($value)
    {
        $this->delivery_mode = $value;
    }

}

?>