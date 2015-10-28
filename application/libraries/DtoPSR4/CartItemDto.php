<?php

class CartItemDto
{
    private $sku;
    private $extItemCd;
    private $name;
    private $nameInLang;
    private $qty;
    private $platformCurrency;
    private $unitCost;
    private $price;
    private $discountTotal;
    private $promoDiscAmt;
    private $amount;  //subtotal
    private $imageUrl = null;
    private $image;
    private $supplierUnitCost;
    private $supplierCostCurrency;
    private $supplierUnitCostInHkd;
    private $vatTotal;
    private $vatPercent;
    private $unitWeight;
    private $listingStatus;
    private $websiteStatus;
    private $sourcingStatus;
    private $warrantyInMonth;
    private $decPlace;

    public function _construct()
    {
        if (is_null($this->imageUrl))
        {
            if ($this->image)
            {
//it will be load only 1 times, when database bind the result into this object
                $imageFile = get_image_file($this->image, "s", $this->sku);
                $this->setImageUrl($imageFile);
            }
        }
    }

    public function getSku() {
        return $this->sku;
    }

    public function setSku($sku) {
        $this->sku = $sku;
    }

    public function getExtItemCd() {
        return $this->extItemCd;
    }

    public function setExtItemCd($extItemCd) {
        $this->extItemCd = $extItemCd;
    }

    public function getPlatformCurrency() {
        return $this->platformCurrency;
    }

    public function setPlatformCurrency($platformCurrency) {
        $this->platformCurrency = $platformCurrency;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getNameInLang() {
        return $this->nameInLang;
    }

    public function setNameInLang($nameInLang) {
        $this->nameInLang = $nameInLang;
    }

    public function getSupplierUnitCost() {
        return $this->supplierUnitCost;
    }

    public function setSupplierUnitCost($supplierUnitCost) {
        $this->supplierUnitCost = $supplierUnitCost;
    }

    public function getSupplierCostCurrency() {
        return $this->supplierCostCurrency;
    }

    public function setSupplierCostCurrency($supplierCostCurrency) {
        $this->supplierCostCurrency = $supplierCostCurrency;
    }

    public function getSupplierUnitCostInHkd() {
        return $this->supplierUnitCostInHkd;
    }

    public function setSupplierUnitCostInHkd($supplierUnitCostInHkd) {
        $this->supplierUnitCostInHkd = $supplierUnitCostInHkd;
    }

    public function getVatTotal() {
        return $this->vatTotal;
    }

    public function setVatTotal($vatTotal) {
        $this->vatTotal = $vatTotal;
    }

    public function getVatPercent() {
        return $this->vatPercent;
    }

    public function setVatPercent($vatPercent) {
        $this->vatPercent = $vatPercent;
    }

    public function getUnitWeight() {
        return $this->unitWeight;
    }

    public function setUnitWeight($unitWeight) {
        $this->unitWeight = $unitWeight;
    }

    public function getQty() {
        return $this->qty;
    }

    public function setQty($qty) {
        $this->qty = $qty;
    }

    public function getUnitCost() {
        return $this->unitCost;
    }

    public function setUnitCost($unitCost) {
        $this->unitCost = $unitCost;
    }

    public function getDiscountTotal() {
        return $this->discountTotal;
    }

    public function setDiscountTotal($discountTotal) {
        $this->discountTotal = $discountTotal;
    }

    public function getPromoDiscAmt() {
        return $this->promoDiscAmt;
    }

    public function setPromoDiscAmt($promoDiscAmt) {
        $this->promoDiscAmt = $promoDiscAmt;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getListingStatus() {
        return $this->listingStatus;
    }

    public function setListingStatus($listingStatus) {
        $this->listingStatus = $listingStatus;
    }

    public function getWebsiteStatus() {
        return $this->listingStatus;
    }

    public function setWebsiteStatus($websiteStatus) {
        $this->websiteStatus = $websiteStatus;
    }

    public function getSourcingStatus() {
        return $this->sourcingStatus;
    }

    public function setSourcingStatus($sourcingStatus) {
        $this->sourcingStatus = $sourcingStatus;
    }

    public function getWarrantyInMonth() {
        return $this->warrantyInMonth;
    }

    public function setWarrantyInMonth($warrantyInMonth) {
        $this->warrantyInMonth = $warrantyInMonth;
    }

    public function getDecPlace() {
        return $this->decPlace;
    }

    public function setDecPlace($decPlace) {
        $this->decPlace = $decPlace;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }
}