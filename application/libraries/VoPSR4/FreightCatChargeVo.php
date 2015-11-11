<?php
class FreightCatChargeVo extends \BaseVo
{
    private $id;
    private $fcat_id;
    private $origin_country;
    private $dest_country;
    private $currency_id = 'HKD';
    private $amount;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by;

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFcatId($fcat_id)
    {
        $this->fcat_id = $fcat_id;
    }

    public function getFcatId()
    {
        return $this->fcat_id;
    }

    public function setOriginCountry($origin_country)
    {
        $this->origin_country = $origin_country;
    }

    public function getOriginCountry()
    {
        return $this->origin_country;
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

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
