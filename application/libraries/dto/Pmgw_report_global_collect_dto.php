<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pmgw_report_global_collect_dto extends Base_dto
{
    //class variable
    //txn_id and ref_txn_id
    //data
    //amount
    //currency
    //trans_id

    private $merchant_id;
    private $contract_id;
    private $so_no;
    private $effort_id;
    private $type;
    private $txn_id;
    private $internal_txn_id;
    private $payment_reference;
    private $customer_id;
    private $status_id;
    private $status_description;
    private $payment_product_id;
    private $payment_product_description;
    private $currency_id;
    private $amount;
    private $request_currency_code;
    private $request_amount;
    private $paid_currency;
    private $paid_amount;
    private $received_date;
    private $txn_time;
    private $rejection_code;
    private $remarks;


    private $ref_txn_id;

    private $commission = 0.00;

    /*
    private $date;
    private $time;
    private $type;
    private $status;
    private $currency_id;
    private $amount;
    private $commission;
    private $net;
    private $from_email_address;
    private $txn_id;
    private $ref_txn_id;
    private $so_no;
*/
    public function get_merchant_id()
    {
        return $this->merchant_id;
    }

    public function set_merchant_id($value)
    {
        $this->merchant_id = $value;
    }

    public function get_contract_id()
    {
        return $this->contract_id;
    }

    public function set_contract_id($value)
    {
        $this->contract_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_effort_id()
    {
        return $this->effort_id;
    }

    public function set_effort_id($value)
    {
        $this->effort_id = $value;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($value)
    {
        $this->type = $value;
    }

    public function get_payment_reference()
    {
        return $this->payment_reference;
    }

    public function set_payment_reference($value)
    {
        $this->payment_reference = $value;
    }

    public function get_customer_id()
    {
        return $this->customer_id;
    }

    public function set_customer_id($value)
    {
        $this->customer_id = $value;
    }

    public function get_status_id()
    {
        return $this->status_id;
    }

    public function set_status_id($value)
    {
        $this->status_id = $value;
    }

    public function get_status_description()
    {
        return $this->status_description;
    }

    public function set_status_description($value)
    {
        $this->status_description = $value;
    }

    public function get_payment_product_id()
    {
        return $this->payment_product_id;
    }

    public function set_payment_product_id($value)
    {
        $this->payment_product_id = $value;
    }

    public function get_payment_product_description()
    {
        return $this->payment_product_description;
    }

    public function set_payment_product_description($value)
    {
        $this->payment_product_description = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_request_currency_code()
    {
        return $this->request_currency_code;
    }

    public function set_request_currency_code($value)
    {
        $this->request_currency_code = $value;
    }

    public function get_request_amount()
    {
        return $this->request_amount;
    }

    public function set_request_amount($value)
    {
        $this->request_amount = $value;
    }

    public function get_paid_currency()
    {
        return $this->paid_currency;
    }

    public function set_paid_currency($value)
    {
        $this->paid_currency = $value;
    }

    public function get_paid_amount()
    {
        return $this->paid_amount;
    }

    public function set_paid_amount($value)
    {
        $this->paid_amount = $value;
    }

    public function get_received_date()
    {
        return $this->received_date;
    }

    public function set_received_date($value)
    {
        $this->received_date = $value;
    }

    public function get_date()
    {
        return $this->date;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

    public function get_rejection_code()
    {
        return $this->rejection_code;
    }

    public function set_rejection_code($value)
    {
        $this->rejection_code = $value;
    }

    public function get_remarks()
    {
        return $this->remarks;
    }

    public function set_remarks($value)
    {
        $this->remarks = $value;
    }

    //-----------------------------
    //use merchant_reference as both txn_id and ref_txn_id
    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_internal_txn_id()
    {
        return $this->internal_txn_id;
    }

    public function set_internal_txn_id($value)
    {
        $this->internal_txn_id = $value;
    }

    public function get_ref_txn_id()
    {
        return $this->txn_id;
    }

    public function set_ref_txn_id($value)
    {
        $this->ref_txn_id = $value;
    }

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
    }


    public function get_commission()
    {
        return $this->commission;
    }

    public function set_commission($value)
    {
        $this->commission = $value;
    }
}

