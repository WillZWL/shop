<?php
class AmazonProdFeedDto
{
    private $sku;
    private $price;
    private $platform_code;
    private $name;
    private $prod_name;
    private $brand_name;
    private $contents;
    private $mpn;
    private $weight;
    private $moq;
    private $keywords;
    private $quantity;
    private $latency;
    private $oos_latency;
    private $latency_in_stock;
    private $latency_out_of_stock;
    private $clearance;
    private $auto_price;
    private $shiptype;
    private $inv_qty;
    private $condition;
    private $condition_note;
    private $ext_qty;
    private $listing_status;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPlatformCode($platform_code)
    {
        $this->platform_code = $platform_code;
    }

    public function getPlatformCode()
    {
        return $this->platform_code;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setMoq($moq)
    {
        $this->moq = $moq;
    }

    public function getMoq()
    {
        return $this->moq;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setLatency($latency)
    {
        $this->latency = $latency;
    }

    public function getLatency()
    {
        return $this->latency;
    }

    public function setOosLatency($oos_latency)
    {
        $this->oos_latency = $oos_latency;
    }

    public function getOosLatency()
    {
        return $this->oos_latency;
    }

    public function setLatencyInStock($latency_in_stock)
    {
        $this->latency_in_stock = $latency_in_stock;
    }

    public function getLatencyInStock()
    {
        return $this->latency_in_stock;
    }

    public function setLatencyOutOfStock($latency_out_of_stock)
    {
        $this->latency_out_of_stock = $latency_out_of_stock;
    }

    public function getLatencyOutOfStock()
    {
        return $this->latency_out_of_stock;
    }

    public function setClearance($clearance)
    {
        $this->clearance = $clearance;
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setAutoPrice($auto_price)
    {
        $this->auto_price = $auto_price;
    }

    public function getAutoPrice()
    {
        return $this->auto_price;
    }

    public function setShiptype($shiptype)
    {
        $this->shiptype = $shiptype;
    }

    public function getShiptype()
    {
        return $this->shiptype;
    }

    public function setInvQty($inv_qty)
    {
        $this->inv_qty = $inv_qty;
    }

    public function getInvQty()
    {
        return $this->inv_qty;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function setConditionNote($condition_note)
    {
        $this->condition_note = $condition_note;
    }

    public function getConditionNote()
    {
        return $this->condition_note;
    }

    public function setExtQty($ext_qty)
    {
        $this->ext_qty = $ext_qty;
    }

    public function getExtQty()
    {
        return $this->ext_qty;
    }

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

}
