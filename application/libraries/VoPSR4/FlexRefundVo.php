<?php

class FlexRefundVo extends \BaseVo
{
    //class variable
    private $id;
    private $so_no;
    private $flex_batch_id;
    private $gateway_id;
    private $internal_txn_id;
    private $txn_id;
    private $txn_time;
    private $currency_id;
    private $amount;
    private $status;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("so_no", "status");

    //auo increment
    private $increment_field = "id";

    //instance method
    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

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

    public function getInternalTxnId()
    {
        return $this->internal_txn_id;
    }

    public function setInternalTxnId($value)
    {
        $this->internal_txn_id = $value;
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

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}