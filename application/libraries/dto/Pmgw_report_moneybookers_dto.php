<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pmgw_report_moneybookers_dto extends Base_dto
{
    //class variable
    private $txn_id;
    private $txn_time;
    private $date;
    private $type;
    private $transaction_detail;
    private $amount_debit;
    private $amount_credit;
    private $amount;
    private $commission;
    private $status;
    private $balance;
    private $reference;
    private $order_amount_ref;
    private $currency_id;
    private $so_no;
    private $original_order_txn_id;
// ref_txn_id is the alias of original_order_txn_id
    private $ref_txn_id;
    private $payment_type;
    private $internal_txn_id;

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

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
    }

    public function get_date()
    {
        return $this->date;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($value)
    {
        $this->type = $value;
    }

    public function get_transaction_detail()
    {
        return $this->transaction_detail;
    }

    public function set_transaction_detail($value)
    {
        $this->transaction_detail = $value;
    }

    public function get_amount_debit()
    {
        return $this->amount_debit;
    }

    public function set_amount_debit($value)
    {
        $this->amount_debit = $value;
    }

    public function get_amount_credit()
    {
        return $this->amount_credit;
    }

    public function set_amount_credit($value)
    {
        $this->amount_credit = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_commission()
    {
        return $this->commission;
    }

    public function set_commission($value)
    {
        $this->commission = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_balance()
    {
        return $this->balance;
    }

    public function set_balance($value)
    {
        $this->balance = $value;
    }

    public function get_reference()
    {
        return $this->reference;
    }

    public function set_reference($value)
    {
        $this->reference = $value;
    }

    public function get_order_amount_ref()
    {
        return $this->order_amount_ref;
    }

    public function set_order_amount_ref($value)
    {
        $this->order_amount_ref = $value;
    }

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

    public function get_original_order_txn_id()
    {
        return $this->original_order_txn_id;
    }

    public function get_ref_txn_id()
    {
        return $this->ref_txn_id;
    }

    public function set_ref_txn_id($value)
    {
        $this->ref_txn_id = $value;
    }

    public function set_original_order_txn_id($value)
    {
        $this->original_order_txn_id = $value;
    }

    public function set_payment_type($value)
    {
        $this->payment_type = $value;
    }

    public function get_payment_type()
    {
        return $this->payment_type;
    }
}
/* End of file pmgw_report_moneybookers_dto.php */
/* Location: ./system/application/libraries/dto/pmgw_report_moneybookers_dto.php */

