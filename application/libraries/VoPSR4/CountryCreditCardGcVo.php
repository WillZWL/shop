<?php

class CountryCreditCardGcVo extends \BaseVo
{
    private $country_id;
    private $card_code;
    private $priority = '9';
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
