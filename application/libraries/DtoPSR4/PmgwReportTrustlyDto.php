<?php
class PmgwReportTrustlyDto
{
    private $status;
    private $amount;
    private $currency_id;
    private $txn_time;
    private $txn_id;
    private $so_no;
    private $transaction_type;
    private $userid_id;
    private $internal_txn_id;
    private $ref_txn_id;
    private $commission;
    private $date;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setTransactionType($transaction_type)
    {
        $this->transaction_type = $transaction_type;
    }

    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    public function setUseridId($userid_id)
    {
        $this->userid_id = $userid_id;
    }

    public function getUseridId()
    {
        return $this->userid_id;
    }

    public function setInternalTxnId($internal_txn_id)
    {
        $this->internal_txn_id = $internal_txn_id;
    }

    public function getInternalTxnId()
    {
        return $this->internal_txn_id;
    }

    public function setRefTxnId($ref_txn_id)
    {
        $this->ref_txn_id = $ref_txn_id;
    }

    public function getRefTxnId()
    {
        return $this->ref_txn_id;
    }

    public function setCommission($commission)
    {
        $this->commission = $commission;
    }

    public function getCommission()
    {
        return $this->commission;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

}
