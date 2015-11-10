<?php
class PmgwReportTrademeDto
{
    private $date;
    private $description;
    private $money_in;
    private $money_out;
    private $balance;
    private $amount;
    private $commission;
    private $auction;
    private $purchase;
    private $txn_id;
    private $internal_txn_id;
    private $ref_txn_id;
    private $so_no;
    private $currency_id = "NZD";

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setMoneyIn($money_in)
    {
        $this->money_in = $money_in;
    }

    public function getMoneyIn()
    {
        return $this->money_in;
    }

    public function setMoneyOut($money_out)
    {
        $this->money_out = $money_out;
    }

    public function getMoneyOut()
    {
        return $this->money_out;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
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

    public function setAuction($auction)
    {
        $this->auction = $auction;
    }

    public function getAuction()
    {
        return $this->auction;
    }

    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
    }

    public function getPurchase()
    {
        return $this->purchase;
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

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

}
