<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pmgw_report_newegg_us_dto extends Base_dto
{
    //class variable
    private $so_no;
    private $invoice_no;
    private $transaction_type;
    private $txn_id = "";
    private $ref_txn_id;
    private $date;
    private $status;
    private $amount;
    private $currency_id = 'USD';
    private $item_sku;
    private $commission;

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_invoice_no()
    {
        return $this->invoice_no;
    }

    public function set_invoice_no($value)
    {
        $this->invoice_no = $value;
    }

    public function get_transaction_type()
    {
        return $this->transaction_type;
    }

    public function set_transaction_type($value)
    {
        $this->transaction_type = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_ref_txn_id()
    {
        return $this->ref_txn_id;
    }

    public function set_ref_txn_id($value)
    {
        $this->ref_txn_id = $value;
    }

    public function get_date()
    {
        return $this->date;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
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
        if ($value) {
            $this->currency_id = $value;
        }
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
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

