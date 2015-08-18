<?php
include_once 'Base_dto.php';

class Quick_search_result_dto extends Base_dto
{

    private $so_no;
    private $platform_order_id;
    private $txn_id;
    private $name;
    private $platform_id;
    private $client_id;
    private $status;
    private $hold_status;
    private $refund_status;
    private $cost;
    private $amount;
    private $tracking_no;
    private $order_create_date;
    private $delivery_charge;
    private $delivery_name;
    private $expect_delivery_date;
    private $dispatch_date;
    private $currency_id;
    private $title;
    private $password;
    private $payment_gateway_id;
    private $payment_gateway_name;
    private $delivery_mode;
    private $forename;
    private $surname;
    private $tel;
    private $items;
    private $warehouse;
    private $packed_on;
    private $shipped_on;
    private $email;
    private $biz_type;
    private $bt_total_received;
    private $bt_total_bank_charge;
    private $ext_client_id;
    private $expect_ship_days;
    private $expect_del_days;
    private $fulfilled;
    private $cs_customer_query;
    private $split_so_group;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_cost()
    {
        return $this->cost;
    }

    public function set_cost($value)
    {
        $this->cost = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_biz_type()
    {
        return $this->biz_type;
    }

    public function set_biz_type($value)
    {
        $this->biz_type = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_platform_currency_id($value)
    {
        $this->platform_currency_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_platform_order_id()
    {
        return $this->platform_order_id;
    }

    public function set_platform_order_id($value)
    {
        $this->platform_order_id = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_client_id($value)
    {
        $this->client_id = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_hold_status()
    {
        return $this->hold_status;
    }

    public function set_hold_status($value)
    {
        $this->hold_status = $value;
    }

    public function get_refund_status()
    {
        return $this->refund_status;
    }

    public function set_refund_status($value)
    {
        $this->refund_status = $value;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
    }

    public function get_expect_delivery_date()
    {
        return $this->expect_delivery_date;
    }

    public function set_expect_delivery_date($value)
    {
        $this->expect_delivery_date = $value;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function set_password($value)
    {
        $this->password = $value;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function set_title($value)
    {
        $this->title = $value;
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function get_payment_gateway_name()
    {
        return $this->payment_gateway_name;
    }

    public function set_payment_gateway_name($value)
    {
        $this->payment_gateway_name = $value;
    }

    public function get_forename()
    {
        return $this->forename;
    }

    public function set_forename($value)
    {
        $this->forename = $value;
    }

    public function get_surname()
    {
        return $this->surname;
    }

    public function set_surname($value)
    {
        $this->surname = $value;
    }

    public function get_items()
    {
        return $this->items;
    }

    public function set_items($value)
    {
        $this->items = $value;
    }

    public function get_tel()
    {
        return $this->tel;
    }

    public function set_tel($value)
    {
        $this->tel = $value;
    }

    public function get_delivery_charge()
    {
        return $this->delivery_charge;
    }

    public function set_delivery_charge($value)
    {
        $this->delivery_charge = $value;
    }

    public function get_delivery_name()
    {
        return $this->delivery_name;
    }

    public function set_delivery_name($value)
    {
        $this->delivery_name = $value;
    }

    public function get_warehouse()
    {
        return $this->warehouse;
    }

    public function set_warehouse($value)
    {
        $this->warehouse = $value;
    }

    public function get_packed_on()
    {
        return $this->packed_on;
    }

    public function set_packed_on($value)
    {
        $this->packed_on = $value;
    }

    public function get_delivery_mode()
    {
        return $this->delivery_mode;
    }

    public function set_delivery_mode($value)
    {
        $this->delivery_mode = $value;
    }

    public function get_shipped_on()
    {
        return $this->shipped_on;
    }

    public function set_shipped_on($value)
    {
        $this->shipped_on = $value;
    }

    public function get_order_create_date()
    {
        return $this->order_create_date;
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($value)
    {
        $this->email = $value;
    }

    public function get_bt_total_received()
    {
        return $this->bt_total_received;
    }

    public function set_bt_total_received($value)
    {
        $this->bt_total_received = $value;
    }

    public function get_bt_total_bank_charge()
    {
        return $this->bt_total_bank_charge;
    }

    public function set_bt_total_bank_charge($value)
    {
        $this->bt_total_bank_charge = $value;
    }

    public function get_ext_client_id()
    {
        return $this->ext_client_id;
    }

    public function set_ext_client_id($value)
    {
        $this->ext_client_id = $value;
    }

    public function get_expect_ship_days()
    {
        return $this->expect_ship_days;
    }

    public function set_expect_ship_days($value)
    {
        $this->expect_ship_days = $value;
    }

    public function get_expect_del_days()
    {
        return $this->expect_del_days;
    }

    public function set_expect_del_days($value)
    {
        $this->expect_del_days = $value;
    }

    public function get_fulfilled()
    {
        return $this->fulfilled;
    }

    public function set_fulfilled($value)
    {
        $this->fulfilled = $value;
    }

    public function get_cs_customer_query()
    {
        return $this->cs_customer_query;
    }

    public function set_cs_customer_query($value)
    {
        $this->cs_customer_query = $value;
        return $this;
    }

    public function get_split_so_group()
    {
        return $this->split_so_group;
    }

    public function set_split_so_group($value)
    {
        $this->split_so_group = $value;
        return $this;
    }

}


?>