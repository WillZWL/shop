<?php
class PmgwReportPaypalNzDto
{
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

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCommission($commission)
    {
        $this->commission = $commission;
    }

    public function getCommission()
    {
        return $this->commission;
    }

    public function setNet($net)
    {
        $this->net = $net;
    }

    public function getNet()
    {
        return $this->net;
    }

    public function setFromEmailAddress($from_email_address)
    {
        $this->from_email_address = $from_email_address;
    }

    public function getFromEmailAddress()
    {
        return $this->from_email_address;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
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

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

}
