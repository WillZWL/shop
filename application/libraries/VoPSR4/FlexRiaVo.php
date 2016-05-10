<?php
class FlexRiaVo extends \BaseVo
{
    private $so_no;
    private $flex_batch_id;
    private $gateway_id;
    private $txn_id;
    private $txn_time;
    private $currency_id;
    private $amount;
    private $status;

    protected $primary_key = array("so_no");
    protected $increment_field = "";

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function getFlexBatchId()
    {
        return $this->flex_batch_id;
    }

    public function setFlexBatchId($value)
    {
        $this->flex_batch_id = $value;
        return $this;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setGatewayId($value)
    {
        $this->gateway_id = $value;
        return $this;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnId($value)
    {
        $this->txn_id = $value;
        return $this;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setTxnTime($value)
    {
        $this->txn_time = $value;
        return $this;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
        return $this;
    }




}

?>