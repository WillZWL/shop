<?php

class FlexRefundVo extends \BaseVo
{
    private $so_no;
    private $flex_batch_id;
    private $gateway_id;
    private $internal_txn_id = '';
    private $txn_id;
    private $txn_time;
    private $currency_id;
    private $amount;
    private $status;

    protected $primary_key = ['so_no', 'internal_txn_id', 'txn_time', 'status'];
    protected $increment_field = '';

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setFlexBatchId($flex_batch_id)
    {
        if ($flex_batch_id !== null) {
            $this->flex_batch_id = $flex_batch_id;
        }
    }

    public function getFlexBatchId()
    {
        return $this->flex_batch_id;
    }

    public function setGatewayId($gateway_id)
    {
        if ($gateway_id !== null) {
            $this->gateway_id = $gateway_id;
        }
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setInternalTxnId($internal_txn_id)
    {
        if ($internal_txn_id !== null) {
            $this->internal_txn_id = $internal_txn_id;
        }
    }

    public function getInternalTxnId()
    {
        return $this->internal_txn_id;
    }

    public function setTxnId($txn_id)
    {
        if ($txn_id !== null) {
            $this->txn_id = $txn_id;
        }
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnTime($txn_time)
    {
        if ($txn_time !== null) {
            $this->txn_time = $txn_time;
        }
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setAmount($amount)
    {
        if ($amount !== null) {
            $this->amount = $amount;
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
