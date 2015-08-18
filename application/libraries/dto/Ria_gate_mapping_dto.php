<?php

/**
 *
 */
class Ria_gate_mapping_dto extends Base_dto
{

    //class variable
    private $currency_id;
    private $amount;
    private $status;
    private $tran_type;
    private $report_pmgw;
    private $flex_batch_id;
    private $txn_time;
    private $txn_id;

    //instance method
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

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_tran_type()
    {
        return $this->tran_type;
    }

    public function set_tran_type($value)
    {
        $this->tran_type = $value;
    }

    public function get_report_pmgw()
    {
        return $this->report_pmgw;
    }

    public function set_report_pmgw($value)
    {
        $this->report_pmgw = $value;
    }

    public function get_flex_batch_id()
    {
        return $this->flex_batch_id;
    }

    public function set_flex_batch_id($value)
    {
        $this->flex_batch_id = $value;
    }

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }
}
