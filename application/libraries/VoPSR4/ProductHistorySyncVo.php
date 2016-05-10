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

}
