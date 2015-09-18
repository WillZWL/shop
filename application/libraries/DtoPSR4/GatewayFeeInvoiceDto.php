<?php
class GatewayFeeInvoiceDto
{
    private $type;
    private $txn_time;
    private $from_currency;
    private $from_amount;
    private $gateway_id;
    private $batch_id;
    private $to_currency;
    private $to_amount;
    private $difference;
    private $percentage;
    private $txn_ref;

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

    public function setFromCurrency($from_currency)
    {
        $this->from_currency = $from_currency;
    }

    public function getFromCurrency()
    {
        return $this->from_currency;
    }

    public function setFromAmount($from_amount)
    {
        $this->from_amount = $from_amount;
    }

    public function getFromAmount()
    {
        return $this->from_amount;
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

    public function setToCurrency($to_currency)
    {
        $this->to_currency = $to_currency;
    }

    public function getToCurrency()
    {
        return $this->to_currency;
    }

    public function setToAmount($to_amount)
    {
        $this->to_amount = $to_amount;
    }

    public function getToAmount()
    {
        return $this->to_amount;
    }

    public function setDifference($difference)
    {
        $this->difference = $difference;
    }

    public function getDifference()
    {
        return $this->difference;
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
