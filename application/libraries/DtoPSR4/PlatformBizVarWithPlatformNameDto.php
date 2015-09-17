<?php
class PlatformBizVarWithPlatformNameDto
{
    private $selling_platform_id;
    private $platform_name;
    private $latency_in_stock;
    private $latency_out_of_stock;
    private $vat_percent = "0.00";
    private $admin_fee;
    private $platform_region_id;
    private $platform_country_id;
    private $platform_country;
    private $platform_currency_id;
    private $sign_pos;
    private $dec_place;
    private $dec_point;
    private $thousands_sep;
    private $language_id = "en";
    private $payment_charge_percent = "0.00";
    private $delivery_type;
    private $free_delivery_limit;
    private $default_shiptype;
    private $create_on = "0000-00-00 00:00:00";
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function setSellingPlatformId($selling_platform_id)
    {
        $this->selling_platform_id = $selling_platform_id;
    }

    public function getSellingPlatformId()
    {
        return $this->selling_platform_id;
    }

    public function setPlatformName($platform_name)
    {
        $this->platform_name = $platform_name;
    }

    public function getPlatformName()
    {
        return $this->platform_name;
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

    public function setVatPercent($vat_percent)
    {
        $this->vat_percent = $vat_percent;
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setAdminFee($admin_fee)
    {
        $this->admin_fee = $admin_fee;
    }

    public function getAdminFee()
    {
        return $this->admin_fee;
    }

    public function setPlatformRegionId($platform_region_id)
    {
        $this->platform_region_id = $platform_region_id;
    }

    public function getPlatformRegionId()
    {
        return $this->platform_region_id;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setPlatformCountry($platform_country)
    {
        $this->platform_country = $platform_country;
    }

    public function getPlatformCountry()
    {
        return $this->platform_country;
    }

    public function setPlatformCurrencyId($platform_currency_id)
    {
        $this->platform_currency_id = $platform_currency_id;
    }

    public function getPlatformCurrencyId()
    {
        return $this->platform_currency_id;
    }

    public function setSignPos($sign_pos)
    {
        $this->sign_pos = $sign_pos;
    }

    public function getSignPos()
    {
        return $this->sign_pos;
    }

    public function setDecPlace($dec_place)
    {
        $this->dec_place = $dec_place;
    }

    public function getDecPlace()
    {
        return $this->dec_place;
    }

    public function setDecPoint($dec_point)
    {
        $this->dec_point = $dec_point;
    }

    public function getDecPoint()
    {
        return $this->dec_point;
    }

    public function setThousandsSep($thousands_sep)
    {
        $this->thousands_sep = $thousands_sep;
    }

    public function getThousandsSep()
    {
        return $this->thousands_sep;
    }

    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setPaymentChargePercent($payment_charge_percent)
    {
        $this->payment_charge_percent = $payment_charge_percent;
    }

    public function getPaymentChargePercent()
    {
        return $this->payment_charge_percent;
    }

    public function setDeliveryType($delivery_type)
    {
        $this->delivery_type = $delivery_type;
    }

    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    public function setFreeDeliveryLimit($free_delivery_limit)
    {
        $this->free_delivery_limit = $free_delivery_limit;
    }

    public function getFreeDeliveryLimit()
    {
        return $this->free_delivery_limit;
    }

    public function setDefaultShiptype($default_shiptype)
    {
        $this->default_shiptype = $default_shiptype;
    }

    public function getDefaultShiptype()
    {
        return $this->default_shiptype;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

}
