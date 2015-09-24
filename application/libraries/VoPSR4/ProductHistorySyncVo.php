<?php

class ProductHistorySyncVo extends \BaseVo
{

    //class variable
    private $id;
    private $batch_id;
    private $sku;
    private $quantity;
    private $lang_restricted;
    private $currency_id;
    private $cost;
    private $lead_day;
    private $moq;
    private $supply_status = 'A';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "id";

    //instance method
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setBatchId($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setLangRestricted($lang_restricted)
    {
        $this->lang_restricted = $lang_restricted;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getLeadDay()
    {
        return $this->lead_day;
    }

    public function setLeadDay($lead_day)
    {
        $this->lead_day = $lead_day;
    }

    public function getMoq()
    {
        return $this->moq;
    }

    public function setMoq($moq)
    {
        $this->moq = $moq;
    }

    public function getSupplyStatus()
    {
        return $this->supply_status;
    }

    public function setSupplyStatus($supply_status)
    {
        $this->supply_status = $supply_status;
    }

     public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
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