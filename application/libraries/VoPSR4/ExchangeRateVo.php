<?php

class ExchangeRateVo extends \BaseVo
{
    private $id;
    private $from_currency_id;
    private $to_currency_id;
    private $rate = '1.000000';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
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

}
