<?php
class CartDto
{
    protected $platformId;
    protected $platformOrderId;
    public $items;  //CartItemDto
//   protected $bizType;
    protected $vatPercent;
    protected $vat;
    protected $platformCurrency;
    protected $platformCountryId;
    protected $cost;
    protected $subtotal;
    protected $grandTotal;
    protected $paymentCharge;
    protected $adminFee;
    protected $offlineFee;
    protected $totalWeight;
    protected $deliveryCharge = 0;
    protected $deliveryType = "STD";
    protected $deliveryCost;
    protected $totalNumberOfItems;
    protected $orderCreateDate;
//optional
    protected $totalProfit;
    protected $margin;

    public function getPlatformId() {
        return $this->platformId;
    }

    public function setPlatformId($platformId) {
        $this->platformId = $platformId;
    }

    public function getPlatformOrderId() {
        return $this->platformOrderId;
    }

    public function setPlatformOrderId($platformOrderId) {
        $this->platformOrderId = $platformOrderId;
    }

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }

/*
    public function getBizType() {
        return $this->bizType;
    }

    public function setBizType($bizType) {
        $this->bizType = $bizType;
    }
*/
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
        return ($this->subtotal + $this->deliveryCharge + $this->offlineFee);
    }

    public function setGrandTotal($grandTotal) {
        $this->grandTotal = $grandTotal;
    }

    public function getPlatformCurrency() {
        return $this->platformCurrency;
    }

    public function setPlatformCurrency($platformCurrency) {
        $this->platformCurrency = $platformCurrency;
    }

    public function getPlatformCountryId() {
        return $this->platformCountryId;
    }

    public function setPlatformCountryId($platformCountryId) {
        $this->platformCountryId = $platformCountryId;
    }

    public function getPaymentCharge() {
        return $this->paymentCharge;
    }

    public function setDeclaredValue($paymentCharge) {
        $this->paymentCharge = $paymentCharge;
    }

    public function getTotalWeight() {
        return $this->totalWeight;
    }

    public function setTotalWeight($totalWeight) {
        $this->totalWeight = $totalWeight;
    }

    public function getAdminFee() {
        return $this->adminFee;
    }

    public function setAdminFee($adminFee) {
        $this->adminFee = $adminFee;
    }

    public function getOfflineFee() {
        return $this->offlineFee;
    }

    public function setOfflineFee($offlineFee) {
        $this->offlineFee = $offlineFee;
    }

    public function getDeliveryCharge() {
        return $this->deliveryCharge;
    }

    public function setDeliveryCharge($deliveryCharge) {
        $this->deliveryCharge = $deliveryCharge;
    }

    public function getDeliveryType() {
        return $this->deliveryType;
    }

    public function setDeliveryType($deliveryType) {
        $this->deliveryType = $deliveryType;
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

    public function getOrderCreateDate() {
        return $this->orderCreateDate;
    }

    public function setOrderCreateDate($orderCreateDate) {
        $this->orderCreateDate = $orderCreateDate;
    }

    public function getTotalProfit() {
        return $this->totalProfit;
    }

    public function setTotalProfit($totalProfit) {
        $this->totalProfit = $totalProfit;
    }

    public function getMargin() {
        return $this->margin;
    }

    public function setMargin($margin) {
        $this->margin = $margin;
    }
}
