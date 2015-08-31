<?php
class WeightCatChargeVo extends \BaseVo
{
    private $wcat_id;
    private $delivery_type;
    private $dest_country;
    private $currency_id;
    private $amount;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '127.0.0.1';
    private $modify_by;

    private $primary_key = ['wcat_id', 'delivery_type', 'dest_country'];

    public function setWcatId($wcat_id)
    {
        $this->wcat_id = $wcat_id;
    }

    public function getWcatId()
    {
        return $this->wcat_id;
    }

    public function setDeliveryType($delivery_type)
    {
        $this->delivery_type = $delivery_type;
    }

    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    public function setDestCountry($dest_country)
    {
        $this->dest_country = $dest_country;
    }

    public function getDestCountry()
    {
        return $this->dest_country;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
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
}
