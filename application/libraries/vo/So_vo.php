<?php
include_once 'Base_vo.php';

class So_vo extends Base_vo
{

    //class variable
    private $so_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id;
    private $client_id;
    private $biz_type;
    private $amount;
    private $cost;
    private $vat_percent;
    private $rate = '1.000000';
    private $ref_1 = '1.000000';
    private $delivery_charge;
    private $delivery_type_id;
    private $weight;
    private $currency_id;
    private $lang_id = 'en';
    private $bill_name;
    private $bill_company;
    private $bill_address;
    private $bill_postcode;
    private $bill_city;
    private $bill_state;
    private $bill_country_id;
    private $delivery_name;
    private $delivery_company;
    private $delivery_address;
    private $delivery_postcode;
    private $delivery_city;
    private $delivery_state;
    private $delivery_country_id;
    private $parent_so_no;
    private $status = '1';
    private $refund_status = '0';
    private $hold_status = '0';
    private $promotion_code;
    private $client_promotion_code;
    private $expect_delivery_date;
    private $expect_ship_days;
    private $expect_del_days;
    private $order_create_date;
    private $dispatch_date;
    private $finance_dispatch_date;
    private $fingerprintId;
    private $cc_reminder_type;
    private $cc_reminder_schedule_date;
    private $cs_customer_query;
    private $split_so_group;
    private $split_status = 0;
    private $split_create_on = '0000-00-00 00:00:00';
    private $split_create_by;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("so_no");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_platform_order_id()
    {
        return $this->platform_order_id;
    }

    public function set_platform_order_id($value)
    {
        $this->platform_order_id = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
        return $this;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_client_id($value)
    {
        $this->client_id = $value;
        return $this;
    }

    public function get_biz_type()
    {
        return $this->biz_type;
    }

    public function set_biz_type($value)
    {
        $this->biz_type = $value;
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

    public function get_cost()
    {
        return $this->cost;
    }

    public function set_cost($value)
    {
        $this->cost = $value;
        return $this;
    }

    public function get_vat_percent()
    {
        return $this->vat_percent;
    }

    public function set_vat_percent($value)
    {
        $this->vat_percent = $value;
        return $this;
    }

    public function get_rate()
    {
        return $this->rate;
    }

    public function set_rate($value)
    {
        $this->rate = $value;
        return $this;
    }

    public function get_ref_1()
    {
        return $this->ref_1;
    }

    public function set_ref_1($value)
    {
        $this->ref_1 = $value;
        return $this;
    }

    public function get_delivery_charge()
    {
        return $this->delivery_charge;
    }

    public function set_delivery_charge($value)
    {
        $this->delivery_charge = $value;
        return $this;
    }

    public function get_delivery_type_id()
    {
        return $this->delivery_type_id;
    }

    public function set_delivery_type_id($value)
    {
        $this->delivery_type_id = $value;
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

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
        return $this;
    }

    public function get_bill_name()
    {
        return $this->bill_name;
    }

    public function set_bill_name($value)
    {
        $this->bill_name = $value;
        return $this;
    }

    public function get_bill_company()
    {
        return $this->bill_company;
    }

    public function set_bill_company($value)
    {
        $this->bill_company = $value;
        return $this;
    }

    public function get_bill_address()
    {
        return $this->bill_address;
    }

    public function set_bill_address($value)
    {
        $this->bill_address = $value;
        return $this;
    }

    public function get_bill_postcode()
    {
        return $this->bill_postcode;
    }

    public function set_bill_postcode($value)
    {
        $this->bill_postcode = $value;
        return $this;
    }

    public function get_bill_city()
    {
        return $this->bill_city;
    }

    public function set_bill_city($value)
    {
        $this->bill_city = $value;
        return $this;
    }

    public function get_bill_state()
    {
        return $this->bill_state;
    }

    public function set_bill_state($value)
    {
        $this->bill_state = $value;
        return $this;
    }

    public function get_bill_country_id()
    {
        return $this->bill_country_id;
    }

    public function set_bill_country_id($value)
    {
        $this->bill_country_id = $value;
        return $this;
    }

    public function get_delivery_name()
    {
        return $this->delivery_name;
    }

    public function set_delivery_name($value)
    {
        $this->delivery_name = $value;
        return $this;
    }

    public function get_delivery_company()
    {
        return $this->delivery_company;
    }

    public function set_delivery_company($value)
    {
        $this->delivery_company = $value;
        return $this;
    }

    public function get_delivery_address()
    {
        return $this->delivery_address;
    }

    public function set_delivery_address($value)
    {
        $this->delivery_address = $value;
        return $this;
    }

    public function get_delivery_postcode()
    {
        return $this->delivery_postcode;
    }

    public function set_delivery_postcode($value)
    {
        $this->delivery_postcode = $value;
        return $this;
    }

    public function get_delivery_city()
    {
        return $this->delivery_city;
    }

    public function set_delivery_city($value)
    {
        $this->delivery_city = $value;
        return $this;
    }

    public function get_delivery_state()
    {
        return $this->delivery_state;
    }

    public function set_delivery_state($value)
    {
        $this->delivery_state = $value;
        return $this;
    }

    public function get_delivery_country_id()
    {
        return $this->delivery_country_id;
    }

    public function set_delivery_country_id($value)
    {
        $this->delivery_country_id = $value;
        return $this;
    }

    public function get_parent_so_no()
    {
        return $this->parent_so_no;
    }

    public function set_parent_so_no($value)
    {
        $this->parent_so_no = $value;
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

    public function get_refund_status()
    {
        return $this->refund_status;
    }

    public function set_refund_status($value)
    {
        $this->refund_status = $value;
        return $this;
    }

    public function get_hold_status()
    {
        return $this->hold_status;
    }

    public function set_hold_status($value)
    {
        if ($this->hold_status == 10)
            return $this;
        $this->hold_status = $value;
        return $this;
    }

    public function get_promotion_code()
    {
        return $this->promotion_code;
    }

    public function set_promotion_code($value)
    {
        $this->promotion_code = $value;
        return $this;
    }

    public function get_client_promotion_code()
    {
        return $this->client_promotion_code;
    }

    public function set_client_promotion_code($value)
    {
        $this->client_promotion_code = $value;
        return $this;
    }

    public function get_expect_delivery_date()
    {
        return $this->expect_delivery_date;
    }

    public function set_expect_delivery_date($value)
    {
        $this->expect_delivery_date = $value;
        return $this;
    }

    public function get_expect_ship_days()
    {
        return $this->expect_ship_days;
    }

    public function set_expect_ship_days($value)
    {
        $this->expect_ship_days = $value;
        return $this;
    }

    public function get_expect_del_days()
    {
        return $this->expect_del_days;
    }

    public function set_expect_del_days($value)
    {
        $this->expect_del_days = $value;
        return $this;
    }

    public function get_order_create_date()
    {
        return $this->order_create_date;
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
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

    public function get_finance_dispatch_date()
    {
        return $this->finance_dispatch_date;
    }

    public function set_finance_dispatch_date($value)
    {
        $this->finance_dispatch_date = $value;
        return $this;
    }

    public function get_fingerprintId()
    {
        return $this->fingerprintId;
    }

    public function set_fingerprintId($value)
    {
        $this->fingerprintId = $value;
        return $this;
    }

    public function get_cc_reminder_type()
    {
        return $this->cc_reminder_type;
    }

    public function set_cc_reminder_type($value)
    {
        $this->cc_reminder_type = $value;
        return $this;
    }

    public function get_cc_reminder_schedule_date()
    {
        return $this->cc_reminder_schedule_date;
    }

    public function set_cc_reminder_schedule_date($value)
    {
        $this->cc_reminder_schedule_date = $value;
        return $this;
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

    public function get_split_status()
    {
        return $this->split_status;
    }

    public function set_split_status($value)
    {
        $this->split_status = $value;
        return $this;
    }

    public function get_split_create_on()
    {
        return $this->split_create_on;
    }

    public function set_split_create_on($value)
    {
        $this->split_create_on = $value;
        return $this;
    }

    public function get_split_create_by()
    {
        return $this->split_create_by;
    }

    public function set_split_create_by($value)
    {
        $this->split_create_by = $value;
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