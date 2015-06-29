<?php
include_once 'Base_dto.php';

Class Chargeback_orders_dto extends Base_dto
{
    // mainly copied from So_screening_dto
    private $so_no;
    private $hold_reason;
    private $hold_date_time;
    private $hold_date;
    private $hold_time;
    private $hold_staff;
/*
    private $release_date_time;
    private $release_staff;
*/
    private $order_create_date_time;
    private $order_create_date;
    private $order_create_time;
    private $payment_transaction_id;
    private $payment_gateway_id;
    private $order_value;
    private $item_quantity;
    private $order_quantity;
    private $category_name;
    private $currency;
    private $product_name;
    private $item_value;
    private $paid;
    private $mb_status;
    private $payment_status;
    private $client_forename;
    private $client_surname;
    private $client_id;
    private $email;
    private $bill_name;
    private $bill_forename;
    private $bill_surname;
    private $bill_company;
    private $bill_address;
    private $bill_address1;
    private $bill_address2;
    private $bill_address3;
    private $bill_city;
    private $bill_state;
    private $bill_postcode;
    private $bill_country_id;
    private $delivery_name;
    private $delivery_forename;
    private $delivery_surname;
    private $delivery_company;
    private $delivery_address;
    private $delivery_address1;
    private $delivery_address2;
    private $delivery_address3;
    private $delivery_city;
    private $delivery_state;
    private $delivery_postcode;
    private $delivery_country_id;
    private $password;
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $mobile;
    private $order_type;
    private $ship_service_level;
    private $delivery_mode;
    private $delivery_cost;
    private $promotion_code;
    private $payment_type;
    private $card_type;
    private $pay_to_account;
    private $risk_var1;
    private $risk_var2;
    private $risk_var3;
    private $risk_var4;
    private $risk_var5;
    private $risk_var6;
    private $risk_var7;
    private $risk_var8;
    private $risk_var9;
    private $risk_var10;

    private $card_bin;
    private $risk_ref1;
    private $risk_ref2;
    private $risk_ref3;
    private $risk_ref4;
/*
    private $verification_level;
    private $fraud_result;
    private $avs_result;
    private $protection_eligibility;
    private $protection_eligibilityType;
    private $address_status;
    private $payer_status;
*/
    private $ip_address;
    private $order_status;
    private $dispatch_date;
    private $refund_status;
    private $refund_date;
    private $refund_reason;
    private $empty_field;

    private $chargeback_create_date;
    private $chargeback_reason;
    private $chargeback_remark;
    private $chargeback_status;

    public function get_so_no()
    {

        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_hold_reason()
    {
        return $this->hold_reason;
    }
    public function set_hold_reason($input)
    {
        $this->hold_reason = $input;
    }
    public function get_hold_date_time()
    {
        return $this->hold_date_time;
    }
    public function set_hold_date_time($input)
    {
        $this->hold_date_time = $input;
        if ($input != "")
        {
            $date = explode(" ", $input);
            $this->hold_date = $date[0];
            $this->hold_time = $date[1];
        }
    }
    public function get_hold_date()
    {
        return $this->hold_date;
    }
    public function get_hold_time()
    {
        return $this->hold_time;
    }
    public function get_hold_staff()
    {
        return $this->hold_staff;
    }
    public function set_hold_staff($input)
    {
        $this->hold_staff = $input;
    }
    public function get_order_create_date_time()
    {
        return $this->order_create_date_time;
    }
    public function set_order_create_date_time($input)
    {
        $this->order_create_date_time = $input;
        if ($input != "")
        {
            $date = explode(" ", $input);
            $this->order_create_date = $date[0];
            $this->order_create_time = $date[1];
        }
    }
    public function get_order_create_date()
    {
        return $this->order_create_date;
    }
    public function get_order_create_time()
    {
        return $this->order_create_time;
    }

    public function get_payment_transaction_id()
    {
        return $this->payment_transaction_id;
    }
    public function set_payment_transaction_id($input)
    {
        $this->payment_transaction_id = $input;
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }
    public function set_payment_gateway_id($input)
    {
        $this->payment_gateway_id = $input;
    }
    public function get_order_value()
    {
        return $this->order_value;
    }
    public function set_order_value($input)
    {
        $this->order_value = $input;
    }
    public function get_item_quantity()
    {
        return $this->item_quantity;
    }
    public function set_item_quantity($input)
    {
        $this->item_quantity = $input;
    }
    public function get_order_quantity()
    {
        return $this->order_quantity;
    }
    public function set_order_quantity($input)
    {
        $this->order_quantity = $input;
    }
    public function get_category_name()
    {
        return $this->category_name;
    }
    public function set_category_name($input)
    {
        $this->category_name = $input;
    }
    public function get_currency()
    {
        return $this->currency;
    }
    public function set_currency($input)
    {
        $this->currency = $input;
    }
    public function get_product_name()
    {
        return $this->product_name;
    }
    public function set_product_name($input)
    {
        $this->product_name = $input;
    }
    public function get_item_value()
    {
        return $this->item_value;
    }
    public function set_item_value($input)
    {
        $this->item_value = $input;
    }

    public function get_payment_status()
    {
        return $this->payment_status;
    }
    public function set_payment_status($input)
    {
        $this->payment_status = $input;
        if ($this->payment_gateway_id == 'moneybookers')
            $this->mb_status = $this->payment_status;
        else
            $this->mb_status = "";
    }
    public function get_mb_status()
    {
        return $this->mb_status;
    }
    public function get_client_forename()
    {
        return $this->client_forename;
    }
    public function set_client_forename($input)
    {
        $this->client_forename = $input;
    }
    public function get_client_surname()
    {
        return $this->client_surname;
    }
    public function set_client_surname($input)
    {
        $this->client_surname = $input;
    }
    public function get_client_id()
    {
        return $this->client_id;
    }
    public function set_client_id($input)
    {
        $this->client_id = $input;
    }

    public function get_email()
    {
        return $this->email;
    }
    public function set_email($input)
    {
        $this->email = $input;
    }

    public function get_bill_address()
    {
        return $this->bill_address;
    }

    public function set_bill_address($input)
    {
        $this->bill_address = $input;

        $address = explode("|", $input);
        $this->bill_address1 = $address[0];
        if (sizeof($address) > 1)
            $this->bill_address2 = $address[1];
        if (sizeof($address) > 2)
            $this->bill_address3 = $address[2];
    }

    public function get_bill_address1()
    {
        return $this->bill_address1;
    }

    public function get_bill_address2()
    {
        return $this->bill_address2;
    }

    public function get_bill_address3()
    {
        return $this->bill_address3;
    }

    public function get_delivery_company()
    {
        return $this->delivery_company;
    }
    public function set_delivery_company($input)
    {
        $this->delivery_company = $input;
    }
    public function get_delivery_address()
    {
        return $this->delivery_address;
    }

    public function set_delivery_address($input)
    {
        $this->delivery_address = $input;

        $address = explode("|", $input);
        $this->delivery_address1 = $address[0];
        if (sizeof($address) > 1)
            $this->delivery_address2 = $address[1];
        if (sizeof($address) > 2)
            $this->delivery_address3 = $address[2];
    }

    public function get_delivery_address1()
    {
        return $this->delivery_address1;
    }

    public function get_delivery_address2()
    {
        return $this->delivery_address2;
    }

    public function get_delivery_address3()
    {
        return $this->delivery_address3;
    }

    public function get_bill_name()
    {
        return $this->bill_name;
    }
    public function set_bill_name($input)
    {
        $this->bill_name = $input;
/*
        $name = explode(" ", $input);
        $this->bill_forename = $name[0];
        if (sizeof($name) > 0)
            $this->bill_surname = $name[1];
*/
    }

    public function get_bill_forename()
    {
        $name = explode(" ", $this->bill_name);
        if (sizeof($name) > 0)
            return $name[0];
        else
            return "";
//      return $this->bill_forename;
    }

    public function get_bill_surname()
    {
        $name_length = strlen($this->get_bill_forename());
        return substr($this->bill_name, $name_length, (strlen($this->bill_name) - $name_length));
//      return $this->bill_surname;
    }
    public function get_bill_company()
    {
        return $this->bill_company;
    }
    public function set_bill_company($input)
    {
        $this->bill_company = $input;
    }
    public function get_bill_city()
    {
        return $this->bill_city;
    }
    public function set_bill_city($input)
    {
        $this->bill_city = $input;
    }

    public function get_bill_state()
    {
        return $this->bill_state;
    }
    public function set_bill_state($input)
    {
        $this->bill_state = $input;
    }

    public function get_bill_postcode()
    {
        return $this->bill_postcode;
    }
    public function set_bill_postcode($input)
    {
        $this->bill_postcode = $input;
    }

    public function get_bill_country_id()
    {
        return $this->bill_country_id;
    }
    public function set_bill_country_id($input)
    {
        $this->bill_country_id = $input;
    }

    public function get_paid()
    {
        if (($this->order_status == 5)
            || ($this->order_status == 2)
            || ($this->order_status == 3)
            || ($this->order_status == 6))
            return "1";
        else
            return 0;
    }
    public function get_delivery_name()
    {
        return $this->delivery_name;
    }
    public function set_delivery_name($input)
    {
        $this->delivery_name = $input;
        $name = explode(" ", $this->delivery_name);
        $this->delivery_forename = $name[0];
        if (sizeof($name) > 0)
            $this->delivery_surname = $name[1];
    }

    public function get_delivery_forename()
    {
        return $this->delivery_forename;
    }
    public function get_delivery_surname()
    {
        return $this->delivery_surname;
    }
    public function get_delivery_city()
    {
        return $this->delivery_city;
    }
    public function set_delivery_city($input)
    {
        $this->delivery_city = $input;
    }

    public function get_delivery_state()
    {
        return $this->delivery_state;
    }
    public function set_delivery_state($input)
    {
        $this->delivery_state = $input;
    }

    public function get_delivery_postcode()
    {
        return $this->delivery_postcode;
    }
    public function set_delivery_postcode($input)
    {
        $this->delivery_postcode = $input;
    }

    public function get_delivery_country_id()
    {
        return $this->delivery_country_id;
    }
    public function set_delivery_country_id($input)
    {
        $this->delivery_country_id = $input;
    }
    public function get_password()
    {
        return $this->password;
    }
    public function set_password($input)
    {
        $this->password = $input;
    }
    public function get_tel()
    {
        return $this->tel_1 .  " " . $this->tel_2 . " " . $this->tel_3;
    }
    public function get_tel_1()
    {
        return $this->tel_1;
    }
    public function set_tel_1($input)
    {
        $this->tel_1 = $input;
    }

    public function get_tel_2()
    {
        return $this->tel_2;
    }
    public function set_tel_2($input)
    {
        $this->tel_2 = $input;
    }

    public function get_tel_3()
    {
        return $this->tel_3;
    }
    public function set_tel_3($input)
    {
        $this->tel_3 = $input;
    }

    public function get_mobile()
    {
        return $this->mobile;
    }
    public function set_mobile($input)
    {
        $this->mobile = $input;
    }
    public function get_ship_service_level()
    {
        return $this->ship_service_level;
    }
    public function set_ship_service_level($input)
    {
        $this->ship_service_level = $input;
    }
    public function get_order_type()
    {
        return $this->order_type;
    }
    public function set_order_type($input)
    {
        $this->order_type = $input;
    }
    public function get_delivery_mode()
    {
        return $this->delivery_mode;
    }
    public function set_delivery_mode($input)
    {
        $this->delivery_mode = $input;
    }
    public function get_delivery_cost()
    {
        return $this->delivery_cost;
    }
    public function set_delivery_cost($input)
    {
        $this->delivery_cost = $input;
    }
    public function get_promotion_code()
    {
        return $this->promotion_code;
    }
    public function set_promotion_code($input)
    {
        $this->promotion_code = $input;
    }
    public function get_payment_type()
    {
        return $this->payment_type;
    }
    public function set_payment_type($input)
    {
        $this->payment_type = $input;
    }
    public function get_card_type()
    {
        return $this->card_type;
    }
    public function set_card_type($input)
    {
        $this->card_type = $input;
    }
    public function get_pay_to_account()
    {
        return $this->pay_to_account;
    }
    public function set_pay_to_account($input)
    {
        $this->pay_to_account = $input;
    }
    public function get_risk_var1()
    {
        return $this->risk_var1;
    }
    public function set_risk_var1($input)
    {
        $this->risk_var1 = $input;
    }
    public function get_risk_var2()
    {
        return $this->risk_var2;
    }
    public function set_risk_var2($input)
    {
        $this->risk_var2 = $input;
    }
    public function get_risk_var3()
    {
        return $this->risk_var3;
    }
    public function set_risk_var3($input)
    {
        $this->risk_var3 = $input;
    }
    public function get_risk_var4()
    {
        return $this->risk_var4;
    }
    public function set_risk_var4($input)
    {
        $this->risk_var4 = $input;
    }
    public function get_risk_var5()
    {
        return $this->risk_var5;
    }
    public function set_risk_var5($input)
    {
        $this->risk_var5 = $input;
    }
    public function get_risk_var6()
    {
        return $this->risk_var6;
    }
    public function set_risk_var6($input)
    {
        $this->risk_var6 = $input;
    }
    public function get_risk_var7()
    {
        return $this->risk_var7;
    }
    public function set_risk_var7($input)
    {
        $this->risk_var7 = $input;
    }
    public function get_risk_var8()
    {
        return $this->risk_var8;
    }
    public function set_risk_var8($input)
    {
        $this->risk_var8 = $input;
    }
    public function get_risk_var9()
    {
        return $this->risk_var9;
    }
    public function set_risk_var9($input)
    {
        $this->risk_var9 = $input;
    }
    public function get_risk_var10()
    {
        return $this->risk_var10;
    }
    public function set_risk_var10($input)
    {
        $this->risk_var10 = $input;
    }
    public function get_card_bin()
    {
        return $this->card_bin;
    }
    public function set_card_bin($input)
    {
        $this->card_bin = $input;
    }

    public function get_risk_ref1()
    {
        return $this->risk_ref1;
    }
    public function set_risk_ref1($input)
    {
        $this->risk_ref1 = $input;
    }

    public function get_risk_ref2()
    {
        return $this->risk_ref2;
    }
    public function set_risk_ref2($input)
    {
        $this->risk_ref2 = $input;
    }

    public function get_risk_ref3()
    {
        return $this->risk_ref3;
    }
    public function set_risk_ref3($input)
    {
        $this->risk_ref3 = $input;
    }

    public function get_risk_ref4()
    {
        return $this->risk_ref4;
    }
    public function set_risk_ref4($input)
    {
        $this->risk_ref4 = $input;
    }

    public function get_ip_address()
    {
        return $this->ip_address;
    }
    public function set_ip_address($input)
    {
        $this->ip_address = $input;
    }
    public function get_order_status()
    {
        return $this->order_status;
    }
    public function set_order_status($input)
    {
        $this->order_status = $input;
    }
    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }
    public function set_dispatch_date($input)
    {
        $this->dispatch_date = $input;
    }
    public function get_refund_status()
    {
        return $this->refund_status;
    }
    public function set_refund_status($input)
    {
        $this->refund_status = $input;
    }
    public function get_refund_date()
    {
        return $this->refund_date;
    }
    public function set_refund_date($input)
    {
        $this->refund_date = $input;
    }
    public function get_refund_reason()
    {
        return $this->refund_reason;
    }
    public function set_refund_reason($input)
    {
        $this->refund_reason = $input;
    }
    public function get_empty_field()
    {
        $this->empty_field = "";
        return $this->empty_field;
    }
    public function get_verification_level()
    {
        if ($this->payment_gateway_id == 'moneybookers')
            return $this->risk_ref1;
        else
            return "";
    }
    public function get_fraud_result()
    {
        if ($this->payment_gateway_id != 'paypal')
            return $this->risk_ref2;
        else
            return "";
    }
    public function get_avs_result()
    {
        if ($this->payment_gateway_id != 'paypal')
            return $this->risk_ref1;
        else
            return "";
    }
    public function get_protection_eligibility()
    {
        if ($this->payment_gateway_id == 'paypal')
            return $this->risk_ref1;
        else
            return "";
    }
    public function get_protection_eligibilityType()
    {
        if ($this->payment_gateway_id == 'paypal')
            return $this->risk_ref2;
        else
            return "";
    }
    public function get_address_status()
    {
        return $this->risk_ref3;
    }
    public function get_payer_status()
    {
        return $this->risk_ref4;
    }

    public function get_chargeback_create_date()
    {
        return $this->chargeback_create_date;
    }
    public function set_chargeback_create_date($value)
    {
        $this->chargeback_create_date = $value;
        return $this;
    }

    public function get_chargeback_reason()
    {
        return $this->chargeback_reason;
    }

    public function set_chargeback_reason($value)
    {
        $this->chargeback_reason = $value;
        return $this;
    }

    public function get_chargeback_remark()
    {
        return $this->chargeback_remark;
    }

    public function set_chargeback_remark($value)
    {
        $this->chargeback_remark = $value;
        return $this;
    }

    public function get_chargeback_status()
    {
        return $this->chargeback_status;
    }

    public function set_chargeback_status($value)
    {
        $this->chargeback_status = $value;
        return $this;
    }


}
