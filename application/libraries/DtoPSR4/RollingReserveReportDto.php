<?php
class RollingReserveReportDto
{
    private $so_no;
    private $batch_id;
    private $gateway_id;
    private $txn_id;
    private $txn_date;
    private $currency_id;
    private $order_amount;
    private $amount;
    private $percentage;
    private $status;
    private $hold_time;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setBatchId($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setGatewayId($gateway_id)
    {
        $this->gateway_id = $gateway_id;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnDate($txn_date)
    {
        $this->txn_date = $txn_date;
    }

    public function getTxnDate()
    {
        return $this->txn_date;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setOrderAmount($order_amount)
    {
        $this->order_amount = $order_amount;
    }

    public function getOrderAmount()
    {
        return $this->order_amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setHoldTime($hold_time)
    {
        $this->hold_time = $hold_time;
    }

    public function getHoldTime()
    {
        return $this->hold_time;
    }

}
