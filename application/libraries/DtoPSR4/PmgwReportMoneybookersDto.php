<?php
class PmgwReportMoneybookersDto
{
    private $txn_id;
    private $internal_txn_id;
    private $txn_time;
    private $date;
    private $type;
    private $transaction_detail;
    private $amount_debit;
    private $amount_credit;
    private $amount;
    private $commission;
    private $status;
    private $balance;
    private $reference;
    private $order_amount_ref;
    private $currency_id;
    private $so_no;
    private $original_order_txn_id;
    private $ref_txn_id;
    private $payment_type;

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

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTransactionDetail($transaction_detail)
    {
        $this->transaction_detail = $transaction_detail;
    }

    public function getTransactionDetail()
    {
        return $this->transaction_detail;
    }

    public function setAmountDebit($amount_debit)
    {
        $this->amount_debit = $amount_debit;
    }

    public function getAmountDebit()
    {
        return $this->amount_debit;
    }

    public function setAmountCredit($amount_credit)
    {
        $this->amount_credit = $amount_credit;
    }

    public function getAmountCredit()
    {
        return $this->amount_credit;
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

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setOrderAmountRef($order_amount_ref)
    {
        $this->order_amount_ref = $order_amount_ref;
    }

    public function getOrderAmountRef()
    {
        return $this->order_amount_ref;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setOriginalOrderTxnId($original_order_txn_id)
    {
        $this->original_order_txn_id = $original_order_txn_id;
    }

    public function getOriginalOrderTxnId()
    {
        return $this->original_order_txn_id;
    }

    public function setRefTxnId($ref_txn_id)
    {
        $this->ref_txn_id = $ref_txn_id;
    }

    public function getRefTxnId()
    {
        return $this->ref_txn_id;
    }

    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;
    }

    public function getPaymentType()
    {
        return $this->payment_type;
    }

}
