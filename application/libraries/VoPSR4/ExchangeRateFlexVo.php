<?php

class ExchangeRateFlexVo extends \BaseVo
{
    private $from_currency_id;
    private $to_currency_id;
    private $rate = '1.0000';
    private $approvial_status = '1';

    protected $primary_key = ['from_currency_id', 'to_currency_id'];
    protected $increment_field = '';

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

    public function setApprovialStatus($approvial_status)
    {
        if ($approvial_status !== null) {
            $this->approvial_status = $approvial_status;
        }
    }

    public function getApprovialStatus()
    {
        return $this->approvial_status;
    }

}
