<?php

class CalculateProfitDto
{
    protected $sku;
    protected $qty;
    protected $unitPrice;
    protected $totalAmount;
//return value
    protected $cost;
    protected $rawProfit;
    protected $rawMargin;
    protected $profit;
    protected $margin;
    
    public function __construct($sku = null, $qty = null, $unitPrice = null, $totalAmount = null) {
        if ($sku)
            $this->setSku($sku);
        if ($qty)
            $this->setQty($qty);
        if ($unitPrice)
            $this->setUnitPrice($unitPrice);
        if ($totalAmount)
            $this->setTotalAmount($totalAmount);
    }

    public function getSku() {
        return $this->sku;
    }

    public function setSku($sku) {
        $this->sku = $sku;
    }

    public function getQty() {
        return $this->qty;
    }

    public function setQty($qty) {
        $this->qty = $qty;
    }

    public function getUnitPrice() {
        return $this->unitPrice;
    }

    public function setUnitPrice($unitPrice) {
        $this->unitPrice = $unitPrice;
    }
    
    public function getTotalAmount() {
        return $this->totalAmount;
    }

    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }

    public function getCost() {
        return $this->cost;
    }

    public function setCost($cost) {
        $this->cost = $cost;
    }

    public function getRawProfit() {
        return $this->rawProfit;
    }

    public function setRawProfit($rawProfit) {
        $this->rawProfit = $rawProfit;
    }

    public function getRawMargin() {
        return $this->rawMargin;
    }

    public function setRawMargin($rawMargin) {
        $this->rawMargin = $rawMargin;
    }

    public function getProfit() {
        return $this->profit;
    }

    public function setProfit($profit) {
        $this->profit = $profit;
    }

    public function getMargin() {
        return $this->margin;
    }

    public function setMargin($margin) {
        $this->margin = $margin;
    }
}