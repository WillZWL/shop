<?php
include_once 'Base_vo.php';

class Interface_tracking_info_vo extends Base_vo
{

    //class variable
    private $trans_id;
    private $batch_id;
    private $sh_no;
    private $so_no;
    private $order_number;
    private $status;
    private $tracking_no;
    private $ship_method;
    private $courier_id;
    private $dispatch_date;
    private $weight;
    private $consignee;
    private $postcode;
    private $country;
    private $amount;
    private $currency;
    private $charge_out;
    private $qty;
    private $sku;
    private $qty_shipped;
    private $shipping_cost;
    private $batch_status = '';
    private $failed_reason;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("trans_id");

    //auo increment
    private $increment_field = "trans_id";

    //instance method
    public function get_trans_id()
    {
        return $this->trans_id;
    }

    public function set_trans_id($value)
    {
        $this->trans_id = $value;
        return $this;
    }

    public function get_batch_id()
    {
        return $this->batch_id;
    }

    public function set_batch_id($value)
    {
        $this->batch_id = $value;
        return $this;
    }

    public function get_sh_no()
    {
        return $this->sh_no;
    }

    public function set_sh_no($value)
    {
        $this->sh_no = $value;
        return $this;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_order_number()
    {
        return $this->order_number;
    }

    public function set_order_number($value)
    {
        $this->order_number = $value;
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

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
        return $this;
    }

    public function get_ship_method()
    {
        return $this->ship_method;
    }

    public function set_ship_method($value)
    {
        $this->ship_method = $value;
        return $this;
    }

    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
        return $this;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
        return $this;
    }

    public function get_weight()
    {
        return $this->weight;
    }

    public function set_weight($value)
    {
        $this->weight = $value;
        return $this;
    }

    public function get_consignee()
    {
        return $this->consignee;
    }

    public function set_consignee($value)
    {
        $this->consignee = $value;
        return $this;
    }

    public function get_postcode()
    {
        return $this->postcode;
    }

    public function set_postcode($value)
    {
        $this->postcode = $value;
        return $this;
    }

    public function get_country()
    {
        return $this->country;
    }

    public function set_country($value)
    {
        $this->country = $value;
        return $this;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function get_currency()
    {
        return $this->currency;
    }

    public function set_currency($value)
    {
        $this->currency = $value;
        return $this;
    }

    public function get_charge_out()
    {
        return $this->charge_out;
    }

    public function set_charge_out($value)
    {
        $this->charge_out = $value;
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

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
        return $this;
    }

    public function get_qty_shipped()
    {
        return $this->qty_shipped;
    }

    public function set_qty_shipped($value)
    {
        $this->qty_shipped = $value;
        return $this;
    }

    public function get_shipping_cost()
    {
        return $this->shipping_cost;
    }

    public function set_shipping_cost($value)
    {
        $this->shipping_cost = $value;
        return $this;
    }

    public function get_batch_status()
    {
        return $this->batch_status;
    }

    public function set_batch_status($value)
    {
        $this->batch_status = $value;
        return $this;
    }

    public function get_failed_reason()
    {
        return $this->failed_reason;
    }

    public function set_failed_reason($value)
    {
        $this->failed_reason = $value;
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