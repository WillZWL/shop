<?php
class InterfaceSkuInfoVo extends \BaseVo
{
    private $id;
    private $batch_id;
    private $cps_batch_id;
    private $prod_sku = '';
    private $master_sku = '';
    private $mastersku_cached;
    private $pricehkd = '0.00';
    private $price = '0.00';
    private $currency_id = '';
    private $region = '';
    private $location = '';
    private $moq = '0';
    private $lead_days = '0';
    private $lang_restricted = '';
    private $comments;
    private $surplus_qty = '0';
    private $supply_status = '6';
    private $status = 'N';
    private $failed_reason;


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

    public function setCpsBatchId($cps_batch_id)
    {
        if ($cps_batch_id !== null) {
            $this->cps_batch_id = $cps_batch_id;
        }
    }

    public function getCpsBatchId()
    {
        return $this->cps_batch_id;
    }

    public function setProdSku($prod_sku)
    {
        if ($prod_sku !== null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setMasterSku($master_sku)
    {
        if ($master_sku !== null) {
            $this->master_sku = $master_sku;
        }
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterskuCached($mastersku_cached)
    {
        if ($mastersku_cached !== null) {
            $this->mastersku_cached = $mastersku_cached;
        }
    }

    public function getMasterskuCached()
    {
        return $this->mastersku_cached;
    }

    public function setPricehkd($pricehkd)
    {
        if ($pricehkd !== null) {
            $this->pricehkd = $pricehkd;
        }
    }

    public function getPricehkd()
    {
        return $this->pricehkd;
    }

    public function setPrice($price)
    {
        if ($price !== null) {
            $this->price = $price;
        }
    }

    public function getPrice()
    {
        return $this->price;
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

    public function setRegion($region)
    {
        if ($region !== null) {
            $this->region = $region;
        }
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function setLocation($location)
    {
        if ($location !== null) {
            $this->location = $location;
        }
    }

    public function getLocation()
    {
        return $this->location;
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

    public function setLeadDays($lead_days)
    {
        if ($lead_days !== null) {
            $this->lead_days = $lead_days;
        }
    }

    public function getLeadDays()
    {
        return $this->lead_days;
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

    public function setComments($comments)
    {
        if ($comments !== null) {
            $this->comments = $comments;
        }
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setSurplusQty($surplus_qty)
    {
        if ($surplus_qty !== null) {
            $this->surplus_qty = $surplus_qty;
        }
    }

    public function getSurplusQty()
    {
        return $this->surplus_qty;
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

    public function setFailedReason($failed_reason)
    {
        if ($failed_reason !== null) {
            $this->failed_reason = $failed_reason;
        }
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }

}
