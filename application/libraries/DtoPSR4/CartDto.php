<?php
class CartDto
{
    protected $platformId;
    public $items;  //CartItemDto
    protected $vatPercent;
    protected $vat;
    protected $cost;
    protected $subtotal;
    protected $grandTotal;
    protected $paymentCharge;
    protected $adminFee;
    protected $deliveryCharge = 0;
    protected $deliveryCost;
    protected $totalNumberOfItems;

    public function getPlatformId() {
        return $this->platformId;
    }

    public function setPlatformId($platformId) {
        $this->platformId = $platformId;
    }

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }

    public function getVat() {
        return $this->vat;
    }

    public function setVat($vat) {
        $this->vat = $vat;
    }

    public function getVatPercent() {
        return $this->vatPercent;
    }

    public function setVatPercent($vatPercent) {
        $this->vatPercent = $vatPercent;
    }

    public function getCost() {
        return $this->cost;
    }

    public function setCost($cost) {
        $this->cost = $cost;
    }

    public function getSubtotal() {
        return $this->subtotal;
    }

    public function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
    }

    public function getGrandTotal() {
        return ($this->subtotal + $this->deliveryCharge);
    }

    public function setGrandTotal($grandTotal) {
        $this->grandTotal = $grandTotal;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    public function getPaymentCharge() {
        return $this->paymentCharge;
    }

    public function setDeclaredValue($paymentCharge) {
        $this->paymentCharge = $paymentCharge;
    }

    public function getAdminFee() {
        return $this->adminFee;
    }

    public function setAdminFee($adminFee) {
        $this->adminFee = $adminFee;
    }

    public function getDeliveryCharge() {
        return $this->deliveryCharge;
    }

    public function setDeliveryCharge($deliveryCharge) {
        $this->deliveryCharge = $deliveryCharge;
    }
    
    public function getDeliveryCost() {
        return $this->deliveryCost;
    }

    public function setDeliveryCost($deliveryCost) {
        $this->deliveryCost = $deliveryCost;
    }

    public function getTotalNumberOfItems() {
        return $this->totalNumberOfItems;
    }

    public function setTotalNumberOfItems($totalNumberOfItems) {
        $this->totalNumberOfItems = $totalNumberOfItems;
    }
}
