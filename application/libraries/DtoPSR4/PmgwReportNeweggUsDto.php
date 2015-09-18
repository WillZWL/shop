<?php
class PmgwReportNeweggUsDto
{
    private $so_no;
    private $invoice_no;
    private $transaction_type;
    private $txn_id;
    private $ref_txn_id;
    private $date;
    private $status;
    private $amount;
    private $currency_id = "USD";
    private $item_sku;
    private $commission;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setInvoiceNo($invoice_no)
    {
        $this->invoice_no = $invoice_no;
    }

    public function getInvoiceNo()
    {
        return $this->invoice_no;
    }

    public function setTransactionType($transaction_type)
    {
        $this->transaction_type = $transaction_type;
    }

    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setRefTxnId($ref_txn_id)
    {
        $this->ref_txn_id = $ref_txn_id;
    }

    public function getRefTxnId()
    {
        return $this->ref_txn_id;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

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

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setCommission($commission)
    {
        $this->commission = $commission;
    }

    public function getCommission()
    {
        return $this->commission;
    }

}
