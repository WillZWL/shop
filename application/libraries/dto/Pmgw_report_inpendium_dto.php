<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pmgw_report_inpendium_dto extends Base_dto
{
    //class variable
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
    private $internal_txn_id;
    private $ref_txn_id;
    private $so_no;

    public function get_date()
    {
        return $this->date;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

    public function get_time()
    {
        return $this->time;
    }

    public function set_time($value)
    {
        $this->time = $value;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($value)
    {
        $this->type = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
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

    public function get_commission()
    {
        return $this->commission;
    }

    public function set_commission($value)
    {
        $this->commission = $value;
    }

    public function get_net()
    {
        return $this->net;
    }

    public function set_net($value)
    {
        $this->net = $value;
    }

    public function get_from_email_address()
    {
        return $this->from_email_address;
    }

    public function set_from_email_address($value)
    {
        $this->from_email_address = $value;
    }

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
        return $this->ref_txn_id;
    }

    public function set_ref_txn_id($value)
    {
        $this->ref_txn_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    private $payment_type;
    private $short_id;
    private $unique_id;
    private $request_timestamp;
    private $transaction_id;
    private $status_code;
    private $debit;
    private $credit;

    public function get_payment_type()
    {
        return $this->payment_type;
    }

    public function set_payment_type($value)
    {
        $this->payment_type = $value;
    }

    public function get_short_id()
    {
        return $this->short_id;
    }

    public function set_short_id($value)
    {
        $this->short_id = $value;
    }

    public function get_unique_id()
    {
        return $this->unique_id;
    }

    public function set_unique_id($value)
    {
        $this->unique_id = $value;
    }

    public function get_request_timestamp()
    {
        return $this->request_timestamp;
    }

    public function set_request_timestamp($value)
    {
        $this->request_timestamp = $value;
    }

    public function get_transaction_id()
    {
        return $this->transaction_id;
    }

    public function set_transaction_id($value)
    {
        $this->transaction_id = $value;
    }

    public function get_status_code()
    {
        return $this->status_code;
    }

    public function set_status_code($value)
    {
        $this->status_code = $value;
    }

    public function get_debit()
    {
        return $this->debit;
    }

    public function set_debit($value)
    {
        $this->debit = $value;
    }

    public function get_credit()
    {
        return $this->credit;
    }

    public function set_credit($value)
    {
        $this->credit = $value;
    }

}
/* End of file pmgw_report_paypal_nz_dto.php */
/* Location: ./system/application/libraries/dto/pmgw_report_paypal_nz_dto.php */

