<?php
include_once 'Base_dto.php';

class Credit_check_list_dto extends Base_dto
{

    //class variable
    private $sohr_reason;
    private $so_no;
    private $sor_obj;
    private $id;
    private $platform_order_id;
    private $platform_id;
    private $payment_gateway_id;
    private $txn_id;
    private $client_id;
    private $biz_type;
    private $amount;
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
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $del_tel_1;
    private $del_tel_2;
    private $del_tel_3;
    private $status;
    private $refund_status;
    private $hold_status;
    private $expect_delivery_date;
    private $t3m_is_sent;
    private $t3m_in_file;
    private $t3m_result;
    private $fd_stauts;
    private $payment_status;
    private $pending_action;
    private $risk_ref1;
    private $risk_ref2;
    private $risk_ref3;
    private $risk_ref4;
    private $risk_ref_desc;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $items;
    private $forename;
    private $surname;
    private $password;
    private $pw_count;
    private $reason;
    private $hold_date;
    private $delivery_type_id;

    //instance method
    public function get_sohr_reason()
    {
        return $this->sohr_reason;
    }

    public function set_sohr_reason($value)
    {
        $this->sohr_reason = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_sor_obj()
    {
        return $this->sor_obj;
    }

    public function set_sor_obj($value)
    {
        $this->sor_obj = $value;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_payment_status()
    {
        return $this->payment_status;
    }

    public function set_payment_status($value)
    {
        $this->payment_status = $value;
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

    public function get_expect_delivery_date()
    {
        return $this->expect_delivery_date;
    }

    public function set_expect_delivery_date($value)
    {
        $this->expect_delivery_date = $value;
    }

    public function get_t3m_is_sent()
    {
        return $this->t3m_is_sent;
    }

    public function set_t3m_is_sent($value)
    {
        $this->t3m_is_sent = $value;
    }

    public function get_t3m_in_file()
    {
        return $this->t3m_in_file;
    }

    public function set_t3m_in_file($value)
    {
        $this->t3m_in_file = $value;
    }

    public function get_t3m_result()
    {
        return $this->t3m_result;
    }

    public function set_t3m_result($value)
    {
        $this->t3m_result = $value;
    }

    public function get_fd_status()
    {
        return $this->fd_status;
    }

    public function set_fd_status($value)
    {
        $this->fd_status = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
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

    public function get_pw_count()
    {
        return $this->pw_count;
    }

    public function set_pw_count($value)
    {
        $this->pw_count = $value;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }

    public function get_hold_date()
    {
        return $this->hold_date;
    }

    public function set_hold_date($value)
    {
        $this->hold_date = $value;
    }

    public function get_delivery_type_id()
    {
        return $this->delivery_type_id;
    }

    public function set_delivery_type_id($value)
    {
        $this->delivery_type_id = $value;
    }

    public function get_card_type()
    {
        return $this->card_type;
    }

    public function set_card_type($value)
    {
        $this->card_type = $value;
    }
}


