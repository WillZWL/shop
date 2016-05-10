<?php
class FreightCatChargeVo extends \BaseVo
{
    private $id;
    private $fcat_id;
    private $origin_country;
    private $dest_country;
    private $currency_id = 'HKD';
    private $amount;


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

    public function setFcatId($fcat_id)
    {
        if ($fcat_id !== null) {
            $this->fcat_id = $fcat_id;
        }
    }

    public function getFcatId()
    {
        return $this->fcat_id;
    }

    public function setOriginCountry($origin_country)
    {
        if ($origin_country !== null) {
            $this->origin_country = $origin_country;
        }
    }

    public function getOriginCountry()
    {
        return $this->origin_country;
    }

    public function setDestCountry($dest_country)
    {
        if ($dest_country !== null) {
            $this->dest_country = $dest_country;
        }
    }

    public function getDestCountry()
    {
        return $this->dest_country;
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

}
