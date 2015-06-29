<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pmgw_report_trademe_dto extends Base_dto
{
    //there are defined base on the report
    private $date;
    private $description;
    private $money_in;
    private $money_out;
    private $balance;
    private $auction;
    private $purchase;

    //must have function, since they are use as common method to assign value to the master table
    private $amount;
    private $commission;
    private $txn_id = "";
    private $interal_txn_id = "";
    private $ref_txn_id = "";
    private $so_no;
    private $currency_id = "NZD";

    public function get_date()
    {
        return $this->date;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($value)
    {
        $this->description = $value;
    }

    public function get_money_in()
    {
        return $this->money_in;
    }

    public function set_money_in($value)
    {
        $this->money_in = $value;
    }

    public function get_money_out()
    {
        return $this->money_out;
    }

    public function set_money_out($value)
    {
        $this->money_out = $value;
    }

    public function get_balance()
    {
        return $this->balance;
    }

    public function set_balance($value)
    {
        $this->balance = $value;
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

    public function get_auction()
    {
        return $this->auction;
    }

    public function set_auction($value)
    {
        $this->auction = $value;
    }

    public function get_purchase()
    {
        return $this->purchase;
    }

    public function set_purchase($value)
    {
        $this->purchase = $value;
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

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }
}
/* End of file pmgw_report_trademe_dto.php */
/* Location: ./system/application/libraries/dto/pmgw_report_trademe_dto.php */

