<?php
class ShoppydooProductFeedDto
{
    private $sku;
    private $ext_sku;
    private $detail_desc;
    private $prod_name;
    private $prod_url;
    private $image_url;
    private $price;
    private $rrp;
    private $category;
    private $ean;
    private $mpn;
    private $brand_name;
    private $availability;
    private $delivery_cost;
    private $delivery_time;
    private $warranty;
    private $condition;
    private $empty_field;
    private $platform_id;
    private $current_platform_price;
    private $default_platform_converted_price;
    private $prod_weight;
    private $default_delivery_charge;
    private $free_delivery_limit;
    private $delivery_charge;
    private $platform_country_id;
    private $declared_pcent;
    private $platform_commission;
    private $sales_commission;
    private $declared_value;
    private $duty_pcent;
    private $duty;
    private $payment_charge_percent;
    private $payment_charge;
    private $forex_fee_percent;
    private $forex_fee;
    private $vat_percent;
    private $vat;
    private $supplier_cost;
    private $logistic_cost;
    private $listing_fee;
    private $sub_cat_margin;
    private $auto_total_charge;
    private $cost;
    private $admin_fee;
    private $profit;
    private $margin;
    private $fixed_rrp;
    private $rrp_factor;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setExtSku($ext_sku)
    {
        $this->ext_sku = $ext_sku;
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setDetailDesc($detail_desc)
    {
        $this->detail_desc = $detail_desc;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdUrl($prod_url)
    {
        $this->prod_url = $prod_url;
    }

    public function getProdUrl()
    {
        return $this->prod_url;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setRrp($rrp)
    {
        $this->rrp = $rrp;
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setDeliveryCost($delivery_cost)
    {
        $this->delivery_cost = $delivery_cost;
    }

    public function getDeliveryCost()
    {
        return $this->delivery_cost;
    }

    public function setDeliveryTime($delivery_time)
    {
        $this->delivery_time = $delivery_time;
    }

    public function getDeliveryTime()
    {
        return $this->delivery_time;
    }

    public function setWarranty($warranty)
    {
        $this->warranty = $warranty;
    }

    public function getWarranty()
    {
        return $this->warranty;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function setEmptyField($empty_field)
    {
        $this->empty_field = $empty_field;
    }

    public function getEmptyField()
    {
        return $this->empty_field;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setCurrentPlatformPrice($current_platform_price)
    {
        $this->current_platform_price = $current_platform_price;
    }

    public function getCurrentPlatformPrice()
    {
        return $this->current_platform_price;
    }

    public function setDefaultPlatformConvertedPrice($default_platform_converted_price)
    {
        $this->default_platform_converted_price = $default_platform_converted_price;
    }

    public function getDefaultPlatformConvertedPrice()
    {
        return $this->default_platform_converted_price;
    }

    public function setProdWeight($prod_weight)
    {
        $this->prod_weight = $prod_weight;
    }

    public function getProdWeight()
    {
        return $this->prod_weight;
    }

    public function setDefaultDeliveryCharge($default_delivery_charge)
    {
        $this->default_delivery_charge = $default_delivery_charge;
    }

    public function getDefaultDeliveryCharge()
    {
        return $this->default_delivery_charge;
    }

    public function setFreeDeliveryLimit($free_delivery_limit)
    {
        $this->free_delivery_limit = $free_delivery_limit;
    }

    public function getFreeDeliveryLimit()
    {
        return $this->free_delivery_limit;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        $this->delivery_charge = $delivery_charge;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setDeclaredPcent($declared_pcent)
    {
        $this->declared_pcent = $declared_pcent;
    }

    public function getDeclaredPcent()
    {
        return $this->declared_pcent;
    }

    public function setPlatformCommission($platform_commission)
    {
        $this->platform_commission = $platform_commission;
    }

    public function getPlatformCommission()
    {
        return $this->platform_commission;
    }

    public function setSalesCommission($sales_commission)
    {
        $this->sales_commission = $sales_commission;
    }

    public function getSalesCommission()
    {
        return $this->sales_commission;
    }

    public function setDeclaredValue($declared_value)
    {
        $this->declared_value = $declared_value;
    }

    public function getDeclaredValue()
    {
        return $this->declared_value;
    }

    public function setDutyPcent($duty_pcent)
    {
        $this->duty_pcent = $duty_pcent;
    }

    public function getDutyPcent()
    {
        return $this->duty_pcent;
    }

    public function setDuty($duty)
    {
        $this->duty = $duty;
    }

    public function getDuty()
    {
        return $this->duty;
    }

    public function setPaymentChargePercent($payment_charge_percent)
    {
        $this->payment_charge_percent = $payment_charge_percent;
    }

    public function getPaymentChargePercent()
    {
        return $this->payment_charge_percent;
    }

    public function setPaymentCharge($payment_charge)
    {
        $this->payment_charge = $payment_charge;
    }

    public function getPaymentCharge()
    {
        return $this->payment_charge;
    }

    public function setForexFeePercent($forex_fee_percent)
    {
        $this->forex_fee_percent = $forex_fee_percent;
    }

    public function getForexFeePercent()
    {
        return $this->forex_fee_percent;
    }

    public function setForexFee($forex_fee)
    {
        $this->forex_fee = $forex_fee;
    }

    public function getForexFee()
    {
        return $this->forex_fee;
    }

    public function setVatPercent($vat_percent)
    {
        $this->vat_percent = $vat_percent;
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setSupplierCost($supplier_cost)
    {
        $this->supplier_cost = $supplier_cost;
    }

    public function getSupplierCost()
    {
        return $this->supplier_cost;
    }

    public function setLogisticCost($logistic_cost)
    {
        $this->logistic_cost = $logistic_cost;
    }

    public function getLogisticCost()
    {
        return $this->logistic_cost;
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

    public function setAutoTotalCharge($auto_total_charge)
    {
        $this->auto_total_charge = $auto_total_charge;
    }

    public function getAutoTotalCharge()
    {
        return $this->auto_total_charge;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setAdminFee($admin_fee)
    {
        $this->admin_fee = $admin_fee;
    }

    public function getAdminFee()
    {
        return $this->admin_fee;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setFixedRrp($fixed_rrp)
    {
        $this->fixed_rrp = $fixed_rrp;
    }

    public function getFixedRrp()
    {
        return $this->fixed_rrp;
    }

    public function setRrpFactor($rrp_factor)
    {
        $this->rrp_factor = $rrp_factor;
    }

    public function getRrpFactor()
    {
        return $this->rrp_factor;
    }

}
