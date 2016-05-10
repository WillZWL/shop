<?php
class DeliverySurchargeVo extends \BaseVo
{
    private $id;
    private $country_id;
    private $code_type;
    private $code;
    private $currency_id;
    private $surcharge;
    private $status;

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

    public function setCodeType($code_type)
    {
        if ($code_type !== null) {
            $this->code_type = $code_type;
        }
    }

    public function getCodeType()
    {
        return $this->code_type;
    }

    public function setCode($code)
    {
        if ($code !== null) {
            $this->code = $code;
        }
    }

    public function getCode()
    {
        return $this->code;
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

    public function setSurcharge($surcharge)
    {
        if ($surcharge !== null) {
            $this->surcharge = $surcharge;
        }
    }

    public function getSurcharge()
    {
        return $this->surcharge;
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
