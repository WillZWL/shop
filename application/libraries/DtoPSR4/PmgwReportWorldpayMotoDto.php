<?php
class PmgwReportWorldpayMotoDto
{
    private $full_so_no;
    private $txn_time;
    private $payment_card;
    private $status;
    private $currency_id;
    private $amount;
    private $commission;
    private $ext_ref;
    private $txn_id;
    private $internal_txn_id;
    private $ref_txn_id;
    private $so_no;
    private $date;

    public function setFullSoNo($full_so_no)
    {
        $this->full_so_no = $full_so_no;
    }

    public function getFullSoNo()
    {
        return $this->full_so_no;
    }

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setPaymentCard($payment_card)
    {
        $this->payment_card = $payment_card;
    }

    public function getPaymentCard()
    {
        return $this->payment_card;
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

    public function setExtRef($ext_ref)
    {
        $this->ext_ref = $ext_ref;
    }

    public function getExtRef()
    {
        return $this->ext_ref;
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

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

}
