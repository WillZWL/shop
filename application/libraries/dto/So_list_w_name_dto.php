<?php
include_once 'Base_dto.php';

class So_list_w_name_dto extends Base_dto
{
    //class variable
    private $so_no;
    private $sh_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id;
    private $client_id;
    private $biz_type;
    private $amount;
    private $delivery_charge;
    private $delivery_type_id;
    private $weight;
    private $currency_id;
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
    private $status = '1';
    private $refund_status = '0';
    private $hold_status = '0';
    private $promotion_code;
    private $expect_delivery_date;
    private $order_create_date;
    private $dispatch_date;
    private $git;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $multiple;
    private $items;
    private $o_items;
    private $client_name;
    private $t3m_result;
    private $email;
    private $payment_gateway_id;
    private $warehouse_id;
    private $tracking_no;
    private $note;
    private $order_reason;
    private $offline_fee;
    private $delivery_type;
    private $courier_id;
    private $website_status;
    private $sku;
    private $product_name;
    private $qty;
    private $inventory;
    private $outstanding_qty;
    private $order_total_sku;
    private $reason;
    private $require_payment;
    private $master_sku;
    private $rate;
    private $amount_usd;
    private $so_item_amount;
    private $cat_name;
    private $sub_cat_name;
    private $rec_courier;
    private $packing_date;
    private $split_so_group;

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

    public function get_sh_no()
    {
        return $this->sh_no;
    }

    public function set_sh_no($value)
    {
        $this->sh_no = $value;
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

    public function get_git()
    {
        return $this->git;
    }

    public function set_git($value)
    {
        $this->git = $value;
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

    public function get_expect_delivery_date()
    {
        return $this->expect_delivery_date;
    }

    public function set_expect_delivery_date($value)
    {
        $this->expect_delivery_date = $value;
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

    public function get_multiple()
    {
        return $this->multiple;
    }

    public function set_multiple($value)
    {
        $this->multiple = $value;
    }

    public function get_items()
    {
        return $this->items;
    }

    public function set_items($value)
    {
        $this->items = $value;
    }

    public function get_o_items()
    {
        return $this->o_items;
    }

    public function set_o_items($value)
    {
        $this->o_items = $value;
    }

    public function get_client_name()
    {
        return $this->client_name;
    }

    public function set_client_name($value)
    {
        $this->client_name = $value;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($value)
    {
        $this->email = $value;
    }

    public function get_t3m_result()
    {
        return $this->t3m_result;
    }

    public function set_t3m_result($value)
    {
        $this->t3m_result = $value;
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function get_warehouse_id()
    {
        return $this->warehouse_id;
    }

    public function set_warehouse_id($value)
    {
        $this->warehouse_id = $value;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
    }

    public function get_note()
    {
        return $this->note;
    }

    public function set_note($value)
    {
        $this->note = $value;
    }

    public function get_order_reason()
    {
        return $this->order_reason;
    }

    public function set_order_reason($value)
    {
        $this->order_reason = $value;
    }

    public function get_offline_fee()
    {
        return $this->offline_fee;
    }

    public function set_offline_fee($value)
    {
        $this->offline_fee = $value;
    }

    public function get_delivery_type()
    {
        return $this->delivery_type;
    }

    public function set_delivery_type($value)
    {
        $this->delivery_type = $value;
    }

    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
    }

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_product_name()
    {
        return $this->product_name;
    }

    public function set_product_name($value)
    {
        $this->product_name = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_inventory()
    {
        return $this->inventory;
    }

    public function set_inventory($value)
    {
        $this->inventory = $value;
    }

    public function get_outstanding_qty()
    {
        return $this->outstanding_qty;
    }

    public function set_outstanding_qty($value)
    {
        $this->outstanding_qty = $value;
    }

    public function get_order_total_sku()
    {
        return $this->order_total_sku;
    }

    public function set_order_total_sku($value)
    {
        $this->order_total_sku = $value;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }

    public function get_require_payment()
    {
        return $this->require_payment;
    }

    public function set_require_payment($value)
    {
        $this->require_payment = $value;
    }

    public function get_master_sku()
    {
        return $this->master_sku;
    }

    public function set_master_sku($value)
    {
        $this->master_sku = $value;
    }

    public function get_rate()
    {
        return $this->rate;
    }

    public function set_rate($value)
    {
        $this->rate = $value;
    }

    public function get_amount_usd()
    {
        return $this->amount_usd;
    }

    public function set_amount_usd($value)
    {
        $this->amount_usd = $value;
    }

    public function get_so_item_amount()
    {
        return $this->so_item_amount;
    }

    public function set_so_item_amount($value)
    {
        $this->so_item_amount = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
    }

    public function get_rec_courier()
    {
        return $this->rec_courier;
    }

    public function set_rec_courier($value)
    {
        $this->rec_courier = $value;
    }

    public function get_packing_date()
    {
        return $this->packing_date;
    }

    public function set_packing_date($value)
    {
        $this->packing_date = $value;
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
