<?php
class CompetitorMappingDto
{
    private $competitor_id;
    private $competitor_name;
    private $country_id;
    private $comp_status;
    private $cmap_status;
    private $match;
    private $ext_sku;
    private $last_price;
    private $now_price;
    private $product_url;
    private $note__1;
    private $note__2;
    private $comp_stock_status;
    private $comp_ship_charge;
    private $reprice_min_margin;
    private $reprice_value;
    private $sourcefile_timestamp;
    private $cmap_create_on;
    private $cmap_create_at;
    private $cmap_create_by;
    private $cmap_modify_on;
    private $cmap_modify_at;
    private $cmap_modify_by;
    private $platform_selling_price;
    private $sku;

    public function setCompetitorId($competitor_id)
    {
        $this->competitor_id = $competitor_id;
    }

    public function getCompetitorId()
    {
        return $this->competitor_id;
    }

    public function setCompetitorName($competitor_name)
    {
        $this->competitor_name = $competitor_name;
    }

    public function getCompetitorName()
    {
        return $this->competitor_name;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCompStatus($comp_status)
    {
        $this->comp_status = $comp_status;
    }

    public function getCompStatus()
    {
        return $this->comp_status;
    }

    public function setCmapStatus($cmap_status)
    {
        $this->cmap_status = $cmap_status;
    }

    public function getCmapStatus()
    {
        return $this->cmap_status;
    }

    public function setMatch($match)
    {
        $this->match = $match;
    }

    public function getMatch()
    {
        return $this->match;
    }

    public function setExtSku($ext_sku)
    {
        $this->ext_sku = $ext_sku;
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setLastPrice($last_price)
    {
        $this->last_price = $last_price;
    }

    public function getLastPrice()
    {
        return $this->last_price;
    }

    public function setNowPrice($now_price)
    {
        $this->now_price = $now_price;
    }

    public function getNowPrice()
    {
        return $this->now_price;
    }

    public function setProductUrl($product_url)
    {
        $this->product_url = $product_url;
    }

    public function getProductUrl()
    {
        return $this->product_url;
    }

    public function setNote1($note__1)
    {
        $this->note__1 = $note__1;
    }

    public function getNote1()
    {
        return $this->note__1;
    }

    public function setNote2($note__2)
    {
        $this->note__2 = $note__2;
    }

    public function getNote2()
    {
        return $this->note__2;
    }

    public function setCompStockStatus($comp_stock_status)
    {
        $this->comp_stock_status = $comp_stock_status;
    }

    public function getCompStockStatus()
    {
        return $this->comp_stock_status;
    }

    public function setCompShipCharge($comp_ship_charge)
    {
        $this->comp_ship_charge = $comp_ship_charge;
    }

    public function getCompShipCharge()
    {
        return $this->comp_ship_charge;
    }

    public function setRepriceMinMargin($reprice_min_margin)
    {
        $this->reprice_min_margin = $reprice_min_margin;
    }

    public function getRepriceMinMargin()
    {
        return $this->reprice_min_margin;
    }

    public function setRepriceValue($reprice_value)
    {
        $this->reprice_value = $reprice_value;
    }

    public function getRepriceValue()
    {
        return $this->reprice_value;
    }

    public function setSourcefileTimestamp($sourcefile_timestamp)
    {
        $this->sourcefile_timestamp = $sourcefile_timestamp;
    }

    public function getSourcefileTimestamp()
    {
        return $this->sourcefile_timestamp;
    }

    public function setCmapCreateOn($cmap_create_on)
    {
        $this->cmap_create_on = $cmap_create_on;
    }

    public function getCmapCreateOn()
    {
        return $this->cmap_create_on;
    }

    public function setCmapCreateAt($cmap_create_at)
    {
        $this->cmap_create_at = $cmap_create_at;
    }

    public function getCmapCreateAt()
    {
        return $this->cmap_create_at;
    }

    public function setCmapCreateBy($cmap_create_by)
    {
        $this->cmap_create_by = $cmap_create_by;
    }

    public function getCmapCreateBy()
    {
        return $this->cmap_create_by;
    }

    public function setCmapModifyOn($cmap_modify_on)
    {
        $this->cmap_modify_on = $cmap_modify_on;
    }

    public function getCmapModifyOn()
    {
        return $this->cmap_modify_on;
    }

    public function setCmapModifyAt($cmap_modify_at)
    {
        $this->cmap_modify_at = $cmap_modify_at;
    }

    public function getCmapModifyAt()
    {
        return $this->cmap_modify_at;
    }

    public function setCmapModifyBy($cmap_modify_by)
    {
        $this->cmap_modify_by = $cmap_modify_by;
    }

    public function getCmapModifyBy()
    {
        return $this->cmap_modify_by;
    }

    public function setPlatformSellingPrice($platform_selling_price)
    {
        $this->platform_selling_price = $platform_selling_price;
    }

    public function getPlatformSellingPrice()
    {
        return $this->platform_selling_price;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

}
