<?php
class SoFeeInvoiceDto
{
    private $so_no;
    private $type;
    private $txn_time;
    private $currency;
    private $gateway_id;
    private $batch_id;
    private $qty;
    private $order_amount;
    private $fee;
    private $percentage;
    private $txn_ref;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setGatewayId($gateway_id)
    {
        $this->gateway_id = $gateway_id;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setBatchId($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setOrderAmount($order_amount)
    {
        $this->order_amount = $order_amount;
    }

    public function getOrderAmount()
    {
        return $this->order_amount;
    }

    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    public function getFee()
    {
        return $this->fee;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setTxnRef($txn_ref)
    {
        $this->txn_ref = $txn_ref;
    }

    public function getTxnRef()
    {
        return $this->txn_ref;
    }

}
