<?php
class InterfaceFlexRollingReserveVo extends \BaseVo
{
    private $trans_id;
    private $so_no;
    private $flex_batch_id;
    private $gateway_id;
    private $internal_txn_id;
    private $txn_id;
    private $txn_time;
    private $currency_id;
    private $amount;
    private $status;
    private $batch_status = '';
    private $failed_reason;

    protected $primary_key = ['trans_id'];
    protected $increment_field = 'trans_id';

    public function setTransId($trans_id)
    {
        if ($trans_id) {
            $this->trans_id = $trans_id;
        }
    }

    public function getTransId()
    {
        return $this->trans_id;
    }

    public function setSoNo($so_no)
    {
        if ($so_no) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setFlexBatchId($flex_batch_id)
    {
        if ($flex_batch_id) {
            $this->flex_batch_id = $flex_batch_id;
        }
    }

    public function getFlexBatchId()
    {
        return $this->flex_batch_id;
    }

    public function setGatewayId($gateway_id)
    {
        if ($gateway_id) {
            $this->gateway_id = $gateway_id;
        }
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setInternalTxnId($internal_txn_id)
    {
        if ($internal_txn_id) {
            $this->internal_txn_id = $internal_txn_id;
        }
    }

    public function getInternalTxnId()
    {
        return $this->internal_txn_id;
    }

    public function setTxnId($txn_id)
    {
        if ($txn_id) {
            $this->txn_id = $txn_id;
        }
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnTime($txn_time)
    {
        if ($txn_time) {
            $this->txn_time = $txn_time;
        }
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setAmount($amount)
    {
        if ($amount) {
            $this->amount = $amount;
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setStatus($status)
    {
        if ($status) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setBatchStatus($batch_status)
    {
        if ($batch_status) {
            $this->batch_status = $batch_status;
        }
    }

    public function getBatchStatus()
    {
        return $this->batch_status;
    }

    public function setFailedReason($failed_reason)
    {
        if ($failed_reason) {
            $this->failed_reason = $failed_reason;
        }
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }



}
