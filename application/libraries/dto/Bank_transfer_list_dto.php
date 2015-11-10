<?php
include_once 'Base_dto.php';

class Bank_transfer_list_dto extends Base_dto
{

    //class variable
    private $so_no;
    private $id;
    private $platform_order_id;
    private $platform_id;
    private $payment_gateway_id;
    private $txn_id;
    private $client_id;
    private $biz_type;
    private $amount;
    private $currency_id;
    private $order_create_date;
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
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $del_tel_1;
    private $del_tel_2;
    private $del_tel_3;
    private $status;
    private $hold_status;
    private $refund_status;
    private $reason;
    private $sbt_status;
    private $net_diff_status;
    private $ext_ref_no;
    private $received_amt_localcurr;
    private $bank_account_id;
    private $bank_account_no;
    private $received_date_localtime;
    private $bank_charge;
    private $notes;
    private $so_create_on;
    private $so_create_at;
    private $so_create_by;
    private $so_modify_on;
    private $so_modify_at;
    private $so_modify_by;
    private $sbt_create_on;
    private $sbt_create_at;
    private $sbt_create_by;
    private $sbt_modify_on;
    private $sbt_modify_at;
    private $sbt_modify_by;
    private $items;
    private $forename;
    private $surname;
    private $del_name;
    private $email;
    private $password;
    private $delivery_type_id;

    //instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_sbt_status()
    {
        return $this->sbt_status;
    }

    public function set_sbt_status($value)
    {
        $this->sbt_status = $value;
    }

    public function get_net_diff_status()
    {
        return $this->net_diff_status;
    }

    public function set_net_diff_status($value)
    {
        $this->net_diff_status = $value;
    }

    public function get_pending_action()
    {
        return $this->pending_action;
    }

    public function set_pending_action($value)
    {
        $this->pending_action = $value;
    }

    public function get_risk_ref1()
    {
        return $this->risk_ref1;
    }

    public function set_risk_ref1($value)
    {
        $this->risk_ref1 = $value;
    }

    public function get_risk_ref2()
    {
        return $this->risk_ref2;
    }

    public function set_risk_ref2($value)
    {
        $this->risk_ref2 = $value;
    }

    public function get_risk_ref3()
    {
        return $this->risk_ref3;
    }

    public function set_risk_ref3($value)
    {
        $this->risk_ref3 = $value;
    }

    public function get_risk_ref4()
    {
        return $this->risk_ref4;
    }

    public function set_risk_ref4($value)
    {
        $this->risk_ref4 = $value;
    }

    public function get_risk_ref_desc()
    {
        return $this->risk_ref_desc;
    }

    public function set_risk_ref_desc($value)
    {
        $this->risk_ref_desc = $value;
    }

    public function get_platform_order_id()
    {
        return $this->platform_order_id;
    }

    public function set_platform_order_id($value)
    {
        $this->platform_order_id = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_client_id($value)
    {
        $this->client_id = $value;
    }

    public function get_biz_type()
    {
        return $this->biz_type;
    }

    public function set_biz_type($value)
    {
        $this->biz_type = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_order_create_date()
    {
        return $this->order_create_date;
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
    }

    public function get_bill_name()
    {
        return $this->bill_name;
    }

    public function set_bill_name($value)
    {
        $this->bill_name = $value;
    }

    public function get_bill_company()
    {
        return $this->bill_company;
    }

    public function set_bill_company($value)
    {
        $this->bill_company = $value;
    }

    public function get_bill_address()
    {
        return $this->bill_address;
    }

    public function set_bill_address($value)
    {
        $this->bill_address = $value;
    }

    public function get_bill_postcode()
    {
        return $this->bill_postcode;
    }

    public function set_bill_postcode($value)
    {
        $this->bill_postcode = $value;
    }

    public function get_bill_city()
    {
        return $this->bill_city;
    }

    public function set_bill_city($value)
    {
        $this->bill_city = $value;
    }

    public function get_bill_state()
    {
        return $this->bill_state;
    }

    public function set_bill_state($value)
    {
        $this->bill_state = $value;
    }

    public function get_bill_country_id()
    {
        return $this->bill_country_id;
    }

    public function set_bill_country_id($value)
    {
        $this->bill_country_id = $value;
    }

    public function get_delivery_name()
    {
        return $this->delivery_name;
    }

    public function set_delivery_name($value)
    {
        $this->delivery_name = $value;
    }

    public function get_delivery_company()
    {
        return $this->delivery_company;
    }

    public function set_delivery_company($value)
    {
        $this->delivery_company = $value;
    }

    public function get_delivery_address()
    {
        return $this->delivery_address;
    }

    public function set_delivery_address($value)
    {
        $this->delivery_address = $value;
    }

    public function get_delivery_postcode()
    {
        return $this->delivery_postcode;
    }

    public function set_delivery_postcode($value)
    {
        $this->delivery_postcode = $value;
    }

    public function get_delivery_city()
    {
        return $this->delivery_city;
    }

    public function set_delivery_city($value)
    {
        $this->delivery_city = $value;
    }

    public function get_delivery_state()
    {
        return $this->delivery_state;
    }

    public function set_delivery_state($value)
    {
        $this->delivery_state = $value;
    }

    public function get_delivery_country_id()
    {
        return $this->delivery_country_id;
    }

    public function set_delivery_country_id($value)
    {
        $this->delivery_country_id = $value;
    }

    public function get_tel_1()
    {
        return $this->tel_1;
    }

    public function set_tel_1($value)
    {
        $this->tel_1 = $value;
    }

    public function get_tel_2()
    {
        return $this->tel_2;
    }

    public function set_tel_2($value)
    {
        $this->tel_2 = $value;
    }

    public function get_tel_3()
    {
        return $this->tel_3;
    }

    public function set_tel_3($value)
    {
        $this->tel_3 = $value;
    }

    public function get_del_tel_1()
    {
        return $this->del_tel_1;
    }

    public function set_del_tel_1($value)
    {
        $this->del_tel_1 = $value;
    }

    public function get_del_tel_2()
    {
        return $this->del_tel_2;
    }

    public function set_del_tel_2($value)
    {
        $this->del_tel_2 = $value;
    }

    public function get_del_tel_3()
    {
        return $this->del_tel_3;
    }

    public function set_del_tel_3($value)
    {
        $this->del_tel_3 = $value;
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

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }

    public function get_ext_ref_no()
    {
        return $this->ext_ref_no;
    }

    public function set_ext_ref_no($value)
    {
        $this->ext_ref_no = $value;
    }

    public function get_received_amt_localcurr()
    {
        return $this->received_amt_localcurr;
    }

    public function set_received_amt_localcurr($value)
    {
        $this->received_amt_localcurr = $value;
    }

    public function get_bank_account_id()
    {
        return $this->bank_account_id;
    }

    public function set_bank_account_id($value)
    {
        $this->bank_account_id = $value;
    }

    public function get_bank_account_no()
    {
        return $this->bank_account_no;
    }

    public function set_bank_account_no($value)
    {
        $this->bank_account_no = $value;
    }

    public function get_received_date_localtime()
    {
        return $this->received_date_localtime;
    }

    public function set_received_date_localtime($value)
    {
        $this->received_date_localtime = $value;
    }

    public function get_bank_charge()
    {
        return $this->bank_charge;
    }

    public function set_bank_charge($value)
    {
        $this->bank_charge = $value;
    }

    public function get_notes()
    {
        return $this->notes;
    }

    public function set_notes()
    {
        $this->notes = $value;
        return $this;
    }

    public function get_so_create_on()
    {
        return $this->so_create_on;
    }

    public function set_so_create_on($value)
    {
        $this->so_create_on = $value;
    }

    public function get_so_create_at()
    {
        return $this->so_create_at;
    }

    public function set_so_create_at($value)
    {
        $this->so_create_at = $value;
    }

    public function get_so_create_by()
    {
        return $this->so_create_by;
    }

    public function set_so_create_by($value)
    {
        $this->so_create_by = $value;
    }

    public function get_so_modify_on()
    {
        return $this->so_modify_on;
    }

    public function set_so_modify_on($value)
    {
        $this->so_modify_on = $value;
    }

    public function get_so_modify_at()
    {
        return $this->so_modify_at;
    }

    public function set_so_modify_at($value)
    {
        $this->so_modify_at = $value;
    }

    public function get_so_modify_by()
    {
        return $this->so_modify_by;
    }

    public function set_so_modify_by($value)
    {
        $this->so_modify_by = $value;
    }

    public function get_sbt_create_on()
    {
        return $this->sbt_create_on;
    }

    public function set_sbt_create_on($value)
    {
        $this->sbt_create_on = $value;
    }

    public function get_sbt_create_at()
    {
        return $this->sbt_create_at;
    }

    public function set_sbt_create_at($value)
    {
        $this->sbt_create_at = $value;
    }

    public function get_sbt_create_by()
    {
        return $this->sbt_create_by;
    }

    public function set_sbt_create_by($value)
    {
        $this->sbt_create_by = $value;
    }

    public function get_sbt_modify_on()
    {
        return $this->sbt_modify_on;
    }

    public function set_sbt_modify_on($value)
    {
        $this->sbt_modify_on = $value;
    }

    public function get_sbt_modify_at()
    {
        return $this->sbt_modify_at;
    }

    public function set_sbt_modify_at($value)
    {
        $this->sbt_modify_at = $value;
    }

    public function get_sbt_modify_by()
    {
        return $this->sbt_modify_by;
    }

    public function set_sbt_modify_by($value)
    {
        $this->sbt_modify_by = $value;
    }


    public function get_items()
    {
        return $this->items;
    }

    public function set_items($value)
    {
        $this->items = $value;
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

    public function get_del_name()
    {
        return $this->del_name;
    }

    public function set_del_name($value)
    {
        $this->del_name = $value;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($value)
    {
        $this->email = $value;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function set_password($value)
    {
        $this->password = $value;
    }

    public function get_delivery_type_id()
    {
        return $this->delivery_type_id;
    }

    public function set_delivery_type_id($value)
    {
        $this->delivery_type_id = $value;
    }

}

