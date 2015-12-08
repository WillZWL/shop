<?php
class InterfaceFlexRiaVo extends \BaseVo
{
    private $trans_id;
    private $so_no;
    private $flex_batch_id;
    private $gateway_id;
    private $txn_id;
    private $txn_time;
    private $currency_id;
    private $amount;
    private $status;
    private $batch_status = '';
    private $failed_reason;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['trans_id'];
    private $increment_field = 'trans_id';

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

    public function setCreateOn($create_on)
    {
        if ($create_on) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
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
