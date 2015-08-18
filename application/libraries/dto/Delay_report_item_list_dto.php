<?php
include_once "Base_dto.php";

class Delay_report_item_list_dto extends Base_dto
{
    private $platform_id;
    private $order_no;
    private $bill_country_id;
    private $order_date;
    private $sku;
    private $hold_status;
    private $mult;
    private $packed_date;
    private $dispatched_date;
    private $courier_id;
    private $tracking_no;
    private $fulfillment_centre;
    private $cs_comment;
    private $fulfillment_day;
    private $unfulfilled_day;
    private $refund_type;
    private $refund_status;
    private $refund_qty;
    private $refund_amount;

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_order_no()
    {
        return $this->order_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_bill_country_id()
    {
        return $this->bill_country_id;
    }

    public function set_bill_country_id($value)
    {
        $this->bill_country_id = $value;
    }

    public function get_order_date()
    {
        return $this->order_date;
    }

    public function set_order_date($value)
    {
        $this->order_date = $value;
    }

    public function get_product_name()
    {
        return $this->product_name;
    }

    public function set_product_name($value)
    {
        $this->product_name = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_hold_status()
    {
        return $this->hold_status;
    }

    public function set_hold_status($value)
    {
        $this->hold_status = $value;
    }

    public function get_mult()
    {
        return $this->mult;
    }

    public function set_mult($value)
    {
        $this->mult = $value;
    }

    public function get_packed_date()
    {
        return $this->packed_date;
    }

    public function set_packed_date($value)
    {
        $this->packed_date = $value;
    }

    public function get_dispatched_date()
    {
        return $this->dispatched_date;
    }

    public function set_dispatched_date($value)
    {
        $this->dispatched_date = $value;
    }

    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
    }

    public function get_fulfillment_centre()
    {
        return $this->fulfillment_centre;
    }

    public function set_fulfillment_centre($value)
    {
        $this->fulfillment_centre = $value;
    }

    public function get_cs_comment()
    {
        return $this->cs_comment;
    }

    public function set_cs_comment($value)
    {
        $this->cs_comment = $value;
    }

    public function get_fulfillment_day()
    {
        return $this->fulfillment_day;
    }

    public function set_fulfillment_day($value)
    {
        $this->fulfillment_day = $value;
    }

    public function get_refund_type()
    {
        return $this->refund_type;
    }

    public function set_refund_type($value)
    {
        $this->refund_type = $value;
    }

    public function get_refund_status()
    {
        return $this->refund_status;
    }

    public function set_refund_status($value)
    {
        $this->refund_status = $value;
    }

    public function get_refund_qty()
    {
        return $this->refund_qty;
    }

    public function set_refund_qty($value)
    {
        $this->refund_qty = $value;
    }


    public function get_refund_amount()
    {
        return $this->refund_amount;
    }

    public function set_refund_amount($value)
    {
        $this->refund_amount = $value;
    }
}