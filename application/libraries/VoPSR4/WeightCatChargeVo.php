<?php
class WeightCatChargeVo extends \BaseVo
{
    private $wcat_id;
    private $delivery_type;
    private $dest_country;
    private $currency_id;
    private $amount;

    protected $primary_key = ['wcat_id', 'delivery_type', 'dest_country'];
    protected $increment_field = '';

    public function setWcatId($wcat_id)
    {
        if ($wcat_id !== null) {
            $this->wcat_id = $wcat_id;
        }
    }

    public function getWcatId()
    {
        return $this->wcat_id;
    }

    public function setDeliveryType($delivery_type)
    {
        if ($delivery_type !== null) {
            $this->delivery_type = $delivery_type;
        }
    }

    public function getDeliveryType()
    {
        return $this->delivery_type;
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
