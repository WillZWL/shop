<?php
class ProductHistorySyncVo extends \BaseVo
{
    private $id;
    private $batch_id = '0';
    private $sku;
    private $quantity = '0';
    private $lang_restricted = '0';
    private $currency_id;
    private $cost = '0.00';
    private $lead_day = '0';
    private $moq = '0';
    private $supply_status = 'A';
    private $website_status = 'I';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

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

    public function setBatchId($batch_id)
    {
        if ($batch_id !== null) {
            $this->batch_id = $batch_id;
        }
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setQuantity($quantity)
    {
        if ($quantity !== null) {
            $this->quantity = $quantity;
        }
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setLangRestricted($lang_restricted)
    {
        if ($lang_restricted !== null) {
            $this->lang_restricted = $lang_restricted;
        }
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
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

    public function setCost($cost)
    {
        if ($cost !== null) {
            $this->cost = $cost;
        }
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setLeadDay($lead_day)
    {
        if ($lead_day !== null) {
            $this->lead_day = $lead_day;
        }
    }

    public function getLeadDay()
    {
        return $this->lead_day;
    }

    public function setMoq($moq)
    {
        if ($moq !== null) {
            $this->moq = $moq;
        }
    }

    public function getMoq()
    {
        return $this->moq;
    }

    public function setSupplyStatus($supply_status)
    {
        if ($supply_status !== null) {
            $this->supply_status = $supply_status;
        }
    }

    public function getSupplyStatus()
    {
        return $this->supply_status;
    }

    public function setWebsiteStatus($website_status)
    {
        if ($website_status !== null) {
            $this->website_status = $website_status;
        }
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
            $this->modify_by = $modify_by;
        }
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
