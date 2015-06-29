<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pmgw_report_worldpay_dto extends Base_dto
{
    //class variable
    private $full_so_no;
    private $txn_time;
    private $payment_card;
    private $status;
    private $currency_id;
    private $amount;
    private $commission;
    private $ext_ref;

    private $txn_id = "";
    private $interal_txn_id = "";
    private $ref_txn_id = "";
    private $so_no;
    private $date;


    public function get_full_so_no()
    {
        return $this->full_so_no;
    }

    public function set_full_so_no($value)
    {
        $this->full_so_no = $value;
    }

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
    }

    public function get_payment_card()
    {
        return $this->payment_card;
    }

    public function set_payment_card($value)
    {
        $this->payment_card = $value;
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

    public function get_ext_ref()
    {
        return $this->ext_ref;
    }

    public function set_ext_ref($value)
    {
        $this->ext_ref = $value;
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

    public function get_date()
    {
        return $this->date;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

}
/* End of file pmgw_report_worldpay_dto.php */
/* Location: ./system/application/libraries/dto/pmgw_report_worldpay_dto.php */

