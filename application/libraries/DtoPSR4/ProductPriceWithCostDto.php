<?php
class ProductPriceWithCostDto
{
    private $sku;
    private $vat;
    private $platform_id;
    private $platform_country_id;
    private $vat_percent;
    private $payment_charge_percent;
    private $free_delivery_limit;
    private $admin_fee;
    private $delivery_cost;
    private $delivery_charge;
    private $declared_pcent;
    private $prod_weight;
    private $supplier_cost;
    private $duty_pcent;
    private $platform_commission;
    private $listing_fee;
    private $sub_cat_margin;
    private $platform_currency_id;
    private $forex_fee_percent;
    private $logistic_cost;
    private $forex_fee;
    private $cost;
    private $price;
    private $sales_commission;
    private $declared_value;
    private $duty;
    private $listing_status;
    private $payment_charge;
    private $auto_total_charge;
    private $complementary_acc_cost;
    private $current_platform_price;
    private $default_platform_converted_price;
    private $default_delivery_charge;
    private $profit;
    private $margin;

    private $whfc_cost;
    private $amazon_efn_cost;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setVatPercent($vat_percent)
    {
        $this->vat_percent = $vat_percent;
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setPaymentChargePercent($payment_charge_percent)
    {
        $this->payment_charge_percent = $payment_charge_percent;
    }

    public function getPaymentChargePercent()
    {
        return $this->payment_charge_percent;
    }

    public function setFreeDeliveryLimit($free_delivery_limit)
    {
        $this->free_delivery_limit = $free_delivery_limit;
    }

    public function getFreeDeliveryLimit()
    {
        return $this->free_delivery_limit;
    }

    public function setAdminFee($admin_fee)
    {
        $this->admin_fee = $admin_fee;
    }

    public function getAdminFee()
    {
        return $this->admin_fee;
    }

    public function setDeliveryCost($delivery_cost)
    {
        $this->delivery_cost = $delivery_cost;
    }

    public function getDeliveryCost()
    {
        return $this->delivery_cost;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        $this->delivery_charge = $delivery_charge;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setDeclaredPcent($declared_pcent)
    {
        $this->declared_pcent = $declared_pcent;
    }

    public function getDeclaredPcent()
    {
        return $this->declared_pcent;
    }

    public function setProdWeight($prod_weight)
    {
        $this->prod_weight = $prod_weight;
    }

    public function getProdWeight()
    {
        return $this->prod_weight;
    }

    public function setSupplierCost($supplier_cost)
    {
        $this->supplier_cost = $supplier_cost;
    }

    public function getSupplierCost()
    {
        return $this->supplier_cost;
    }

    public function setDutyPcent($duty_pcent)
    {
        $this->duty_pcent = $duty_pcent;
    }

    public function getDutyPcent()
    {
        return $this->duty_pcent;
    }

    public function setPlatformCommission($platform_commission)
    {
        $this->platform_commission = $platform_commission;
    }

    public function getPlatformCommission()
    {
        return $this->platform_commission;
    }

    public function setListingFee($listing_fee)
    {
        $this->listing_fee = $listing_fee;
    }

    public function getListingFee()
    {
        return $this->listing_fee;
    }

    public function setSubCatMargin($sub_cat_margin)
    {
        $this->sub_cat_margin = $sub_cat_margin;
    }

    public function getSubCatMargin()
    {
        return $this->sub_cat_margin;
    }

    public function setPlatformCurrencyId($platform_currency_id)
    {
        $this->platform_currency_id = $platform_currency_id;
    }

    public function getPlatformCurrencyId()
    {
        return $this->platform_currency_id;
    }

    public function setForexFeePercent($forex_fee_percent)
    {
        $this->forex_fee_percent = $forex_fee_percent;
    }

    public function getForexFeePercent()
    {
        return $this->forex_fee_percent;
    }

    public function getLogisticCost()
    {
        return $this->logistic_cost;
    }

    public function setLogisticCost($logistic_cost)
    {
        $this->logistic_cost = $logistic_cost;
    }

    public function getForexFee()
    {
        return $this->forex_fee;
    }

    public function setForexFee($forex_fee)
    {
        $this->forex_fee = $forex_fee;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getSalesCommission()
    {
        return $this->sales_commission;
    }

    public function setSalesCommission($sales_commission)
    {
        $this->sales_commission = $sales_commission;
    }

    public function getDeclaredValue()
    {
        return $this->declared_value;
    }

    public function setDeclaredValue($declared_value)
    {
        $this->declared_value = $declared_value;
    }

    public function getDuty()
    {
        return $this->duty;
    }

    public function setDuty($duty)
    {
        $this->duty = $duty;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getPaymentCharge()
    {
        return $this->payment_charge;
    }

    public function setPaymentCharge($payment_charge)
    {
        $this->payment_charge = $payment_charge;
    }

    public function getAutoTotalCharge()
    {
        return $this->auto_total_charge;
    }

    public function setAutoTotalCharge($auto_total_charge)
    {
        $this->auto_total_charge = $auto_total_charge;
    }

    public function getComplementaryAccCost()
    {
        return $this->complementary_acc_cost;
    }

    public function setComplementaryAccCost($complementary_acc_cost)
    {
        $this->complementary_acc_cost = $complementary_acc_cost;
    }

    public function getCurrentPlatformPrice()
    {
        return $this->current_platform_price;
    }

    public function setCurrentPlatformPrice($current_platform_price)
    {
        $this->current_platform_price = $current_platform_price;
    }

    public function getDefaultPlatformConvertedPrice()
    {
        return $this->default_platform_converted_price;
    }

    public function setDefaultPlatformConvertedPrice($default_platform_converted_price)
    {
        $this->default_platform_converted_price = $default_platform_converted_price;
    }

    public function getDefaultDeliveryCharge()
    {
        return $this->default_delivery_charge;
    }

    public function setDefaultDeliveryCharge($default_delivery_charge)
    {
        $this->default_delivery_charge = $default_delivery_charge;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }
        public function getWhfcCost()
    {
        return number_format($this->whfc_cost, 2, ".", "");
    }

    public function setWhfcCost($whfc_cost)
    {
        $this->whfc_cost = $whfc_cost;
        return $this;
    }

    public function getAmazonEfnCost()
    {
        return number_format($this->amazon_efn_cost, 2, ".", "");
    }

    public function setAmazonEfnCost($amazon_efn_cost)
    {
        $this->amazon_efn_cost = $amazon_efn_cost;
        return $this;
    }
}
