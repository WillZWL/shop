<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

/**
*
*/
class Pmgw_report_qoo10_dto extends Base_dto
{
    private $so_no;
    private $type;
    private $txn_id;
    private $date;
    private $amount;
    private $currency_id;
    private $commission;

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_type($value)
    {
        $this->type = $value;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_date($value)
    {
        $this->date = $value;
    }

    public function get_date()
    {
        return $this->date;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_commission($value)
    {
        $this->commission = $value;
    }

    public function get_commission()
    {
        return $this->commission;
    }

}