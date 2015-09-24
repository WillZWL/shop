<?php

class InterfaceSkuInfoVo extends \BaseVo
{

    //class variable
    private $id;
    private $batch_id;
    private $cps_batch_id;
    private $prod_sku;
    private $master_sku;
    private $mastersku_cached;
    private $pricehkd;
    private $price;
    private $currency_id;
    private $region;
    private $location;
    private $moq;
    private $lead_days;
    private $lang_restricted;
    private $comments;
    private $surplus_qty;
    private $supply_status = '6';
    private $status = 'N';
    private $failed_reason;
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

    public function getCpsBatchId()
    {
        return $this->cps_batch_id;
    }

    public function setCpsBatchId($cps_batch_id)
    {
        $this->cps_batch_id = $cps_batch_id;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($master_sku)
    {
        $this->master_sku = $master_sku;
    }

    public function getMasterskuCached()
    {
        return $this->mastersku_cached;
    }

    public function setMasterskuCached($mastersku_cached)
    {
        $this->mastersku_cached = $mastersku_cached;
    }

    public function getPricehkd()
    {
        return $this->pricehkd;
    }

    public function setPricehkd($pricehkd)
    {
        $this->pricehkd = $pricehkd;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function setRegion($region)
    {
        $this->region = $region;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getMoq()
    {
        return $this->moq;
    }

    public function setMoq($moq)
    {
        $this->moq = $moq;
    }

    public function getLeadDays()
    {
        return $this->lead_days;
    }

    public function setLeadDays($lead_days)
    {
        $this->lead_days = $lead_days;
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setLangRestricted($lang_restricted)
    {
        $this->lang_restricted = $lang_restricted;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getSurplusQty()
    {
        return $this->surplus_qty;
    }

    public function setSurplusQty($surplus_qty)
    {
        $this->surplus_qty = $surplus_qty;
    }

    public function getSupplyStatus()
    {
        return $this->supply_status;
    }

    public function setSupplyStatus($supply_status)
    {
        $this->supply_status = $supply_status;
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }

    public function setFailedReason($value)
    {
        $this->failed_reason = $value;
    }

     public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCreateOn()
    {
        return $this->create_on;
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