<?php
include_once 'Base_dto.php';

class Order_history_dto extends Base_dto
{
    //class variable
    private $currency_id;
    private $so_no;
    private $client_id;
    private $order_create_date;
    private $dispatch_date;
    private $delivery_name;
    private $sku;
    private $prod_name;
    private $amount;
    private $status;
    private $refund_status;
    private $hold_status;
    private $net_diff_status;
    private $payment_gateway_id;
    private $bill_country_id;
    private $delivery_country_id;
    private $join_split_so_no;
    private $split_so_group;

    public function __construct()
    {
        parent::__construct();
    }

    //instance method
    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_client_id($value)
    {
        $this->client_id = $value;
    }

    public function get_order_create_date()
    {
        return $this->order_create_date;
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
    }

    public function get_delivery_name()
    {
        return $this->delivery_name;
    }

    public function set_delivery_name($value)
    {
        $this->delivery_name = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_refund_status()
    {
        return $this->refund_status;
    }

    public function set_refund_status($value)
    {
        $this->refund_status = $value;
    }

    public function get_hold_status()
    {
        return $this->hold_status;
    }

    public function set_hold_status($value)
    {
        $this->hold_status = $value;
    }

    public function get_net_diff_status()
    {
        return $this->net_diff_status;
    }

    public function set_net_diff_status($value)
    {
        $this->net_diff_status = $value;
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function get_bill_country_id()
    {
        return $this->bill_country_id;
    }

    public function set_bill_country_id($value)
    {
        $this->bill_country_id = $value;
    }

    public function get_delivery_country_id()
    {
        return $this->delivery_country_id;
    }

    public function set_delivery_country_id($value)
    {
        $this->delivery_country_id = $value;
    }

    public function get_join_split_so_no()
    {
        return $this->join_split_so_no;
    }

    public function set_join_split_so_no($value)
    {
        $this->join_split_so_no = $value;
    }

    public function get_split_so_group()
    {
        return $this->split_so_group;
    }

    public function set_split_so_group($value)
    {
        $this->split_so_group = $value;
    }

}

?>