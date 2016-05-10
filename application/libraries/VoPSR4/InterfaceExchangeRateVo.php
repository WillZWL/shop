<?php
class InterfaceExchangeRateVo extends \BaseVo
{
    private $trans_id;
    private $batch_id;
    private $from_currency_id;
    private $to_currency_id;
    private $rate = '1.000000';
    private $batch_status = '';
    private $failed_reason;

    protected $primary_key = ['trans_id'];
    protected $increment_field = 'trans_id';

    public function setTransId($trans_id)
    {
        if ($trans_id !== null) {
            $this->trans_id = $trans_id;
        }
    }

    public function getTransId()
    {
        return $this->trans_id;
    }

    public function setBatchId($batch_id)
    {
        if ($batch_id !== null) {
            $this->batch_id = $batch_id;
        }
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setFromCurrencyId($from_currency_id)
    {
        if ($from_currency_id !== null) {
            $this->from_currency_id = $from_currency_id;
        }
    }

    public function getFromCurrencyId()
    {
        return $this->from_currency_id;
    }

    public function setToCurrencyId($to_currency_id)
    {
        if ($to_currency_id !== null) {
            $this->to_currency_id = $to_currency_id;
        }
    }

    public function getToCurrencyId()
    {
        return $this->to_currency_id;
    }

    public function setRate($rate)
    {
        if ($rate !== null) {
            $this->rate = $rate;
        }
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setBatchStatus($batch_status)
    {
        if ($batch_status !== null) {
            $this->batch_status = $batch_status;
        }
    }

    public function getBatchStatus()
    {
        return $this->batch_status;
    }

    public function setFailedReason($failed_reason)
    {
        if ($failed_reason !== null) {
            $this->failed_reason = $failed_reason;
        }
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }

}
