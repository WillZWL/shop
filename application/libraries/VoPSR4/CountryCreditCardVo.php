<?php

class CountryCreditCardVo extends \BaseVo
{
    private $country_id;
    private $card_code;
    private $priority = '9';
    private $forcing_with_condition;
    private $status = '1';

    protected $primary_key = ['country_id', 'card_code'];
    protected $increment_field = '';

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCardCode($card_code)
    {
        if ($card_code !== null) {
            $this->card_code = $card_code;
        }
    }

    public function getCardCode()
    {
        return $this->card_code;
    }

    public function setPriority($priority)
    {
        if ($priority !== null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setForcingWithCondition($forcing_with_condition)
    {
        if ($forcing_with_condition !== null) {
            $this->forcing_with_condition = $forcing_with_condition;
        }
    }

    public function getForcingWithCondition()
    {
        return $this->forcing_with_condition;
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
