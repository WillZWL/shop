<?php
class WeightCatChargeWithWeightDto
{
    private $wcat_id;
    private $weight;
    private $delivery_type;
    private $dest_country;
    private $currency_id;
    private $amount;

    public function getWcatId()
    {
        return $this->wcat_id;
    }

    public function setWcatId($value)
    {
        $this->wcat_id = $value;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($value)
    {
        $this->weight = $value;
    }

    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    public function setDeliveryType($value)
    {
        $this->delivery_type = $value;
    }

    public function getDestCountry()
    {
        return $this->dest_country;
    }

    public function setDestCountry($value)
    {
        $this->dest_country = $value;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
    }
}
