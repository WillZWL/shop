<?php
class riaControlDto
{
    protected $so_no;
    protected $fri_txn_time = '';
    protected $dispatch_date = '';
    protected $fre_txn_time = '';
    protected $gateway_id;
    protected $fri_txn_id;
    protected $fre_txn_id;
    protected $fri_amount;
    protected $so_amount;
    protected $fre_amount;
    protected $currency_id;
    protected $so_status;
    protected $fri_status;
    protected $fre_status;
    protected $ria_control;

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
    }

    public function getFriTxnTime()
    {
        return $this->fri_txn_time;
    }

    public function setFriTxnTime($value)
    {
        $this->fri_txn_time = $value;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setDispatchDate($value)
    {
        $this->dispatch_date = $value;
    }

    public function getFreTxnTime()
    {
        return $this->fre_txn_time;
    }

    public function setFreTxnTime($value)
    {
        $this->fre_txn_time = $value;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setGatewayId($value)
    {
        $this->gateway_id = $value;
    }

    public function getFriTxnId()
    {
        return $this->fri_txn_id;
    }

    public function setFriTxnId($value)
    {
        $this->fri_txn_id = $value;
    }

    public function getFreTxnId()
    {
        return $this->fre_txn_id;
    }

    public function setFreTxnId($value)
    {
        $this->fre_txn_id = $value;
    }

    public function getFriAmount()
    {
        return $this->fri_amount;
    }

    public function setFriAmount($value)
    {
        $this->fri_amount = $value;
    }

    public function getSoAmount()
    {
        return $this->so_amount;
    }

    public function setSoAmount($value)
    {
        $this->so_amount = $value;
    }

    public function getFreAmount()
    {
        return $this->fre_amount;
    }

    public function setFreAmount($value)
    {
        $this->fre_amount = $value;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getSoStatus()
    {
        return $this->so_status;
    }

    public function setSoStatus($value)
    {
        $this->so_status = $value;
    }

    public function getFriStatus()
    {
        return $this->fri_status;
    }

    public function setFriStatus($value)
    {
        $this->fri_status = $value;
    }

    public function getFreStatus()
    {
        return $this->fre_status;
    }

    public function setFreStatus($value)
    {
        $this->fre_status = $value;
    }

    public function getRiaControl()
    {
        return $this->ria_control;
    }

    public function setRiaControl($value)
    {
        $this->ria_control = $value;
    }
}
