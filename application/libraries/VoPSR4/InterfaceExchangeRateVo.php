<?php
class InterfaceExchangeRateVo extends \BaseVo
{
    private $trans_id;
    private $batch_id;
    private $from_currency_id;
    private $to_currency_id;
    private $rate = '1.000000';
    private $batch_status;
    private $failed_reason;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at;
    private $modify_by;

    private $primary_key = ['trans_id'];
    private $increment_field = 'trans_id';

    public function setTransId($trans_id)
    {
        $this->trans_id = $trans_id;
    }

    public function getTransId()
    {
        return $this->trans_id;
    }

    public function setBatchId($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setFromCurrencyId($from_currency_id)
    {
        $this->from_currency_id = $from_currency_id;
    }

    public function getFromCurrencyId()
    {
        return $this->from_currency_id;
    }

    public function setToCurrencyId($to_currency_id)
    {
        $this->to_currency_id = $to_currency_id;
    }

    public function getToCurrencyId()
    {
        return $this->to_currency_id;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setBatchStatus($batch_status)
    {
        $this->batch_status = $batch_status;
    }

    public function getBatchStatus()
    {
        return $this->batch_status;
    }

    public function setFailedReason($failed_reason)
    {
        $this->failed_reason = $failed_reason;
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
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
