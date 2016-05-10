<?php
class PlatformBizVarVo extends \BaseVo
{
    private $id;
    private $selling_platform_id = '';
    private $need_round_nearest = 'N';
    private $latency_in_stock = '0';
    private $latency_out_of_stock = '0';
    private $vat_percent = '0.00';
    private $admin_fee = '0.00';
    private $platform_region_id = '0';
    private $platform_country_id;
    private $dest_country;
    private $platform_currency_id;
    private $sign_pos = '';
    private $dec_place = '0';
    private $dec_point = '';
    private $thousands_sep = '';
    private $language_id = 'en';
    private $payment_charge_percent = '0.00';
    private $forex_fee_percent = '0.00';
    private $delivery_type;
    private $free_delivery_limit = '0';
    private $default_shiptype = '0';
    private $tax_theresholds = '0.00';


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

    public function setSellingPlatformId($selling_platform_id)
    {
        if ($selling_platform_id !== null) {
            $this->selling_platform_id = $selling_platform_id;
        }
    }

    public function getSellingPlatformId()
    {
        return $this->selling_platform_id;
    }

    public function setNeedRoundNearest($need_round_nearest)
    {
        if ($need_round_nearest !== null) {
            $this->need_round_nearest = $need_round_nearest;
        }
    }

    public function getNeedRoundNearest()
    {
        return $this->need_round_nearest;
    }

    public function setLatencyInStock($latency_in_stock)
    {
        if ($latency_in_stock !== null) {
            $this->latency_in_stock = $latency_in_stock;
        }
    }

    public function getLatencyInStock()
    {
        return $this->latency_in_stock;
    }

    public function setLatencyOutOfStock($latency_out_of_stock)
    {
        if ($latency_out_of_stock !== null) {
            $this->latency_out_of_stock = $latency_out_of_stock;
        }
    }

    public function getLatencyOutOfStock()
    {
        return $this->latency_out_of_stock;
    }

    public function setVatPercent($vat_percent)
    {
        if ($vat_percent !== null) {
            $this->vat_percent = $vat_percent;
        }
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setAdminFee($admin_fee)
    {
        if ($admin_fee !== null) {
            $this->admin_fee = $admin_fee;
        }
    }

    public function getAdminFee()
    {
        return $this->admin_fee;
    }

    public function setPlatformRegionId($platform_region_id)
    {
        if ($platform_region_id !== null) {
            $this->platform_region_id = $platform_region_id;
        }
    }

    public function getPlatformRegionId()
    {
        return $this->platform_region_id;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        if ($platform_country_id !== null) {
            $this->platform_country_id = $platform_country_id;
        }
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setDestCountry($dest_country)
    {
        if ($dest_country !== null) {
            $this->dest_country = $dest_country;
        }
    }

    public function getDestCountry()
    {
        return $this->dest_country;
    }

    public function setPlatformCurrencyId($platform_currency_id)
    {
        if ($platform_currency_id !== null) {
            $this->platform_currency_id = $platform_currency_id;
        }
    }

    public function getPlatformCurrencyId()
    {
        return $this->platform_currency_id;
    }

    public function setSignPos($sign_pos)
    {
        if ($sign_pos !== null) {
            $this->sign_pos = $sign_pos;
        }
    }

    public function getSignPos()
    {
        return $this->sign_pos;
    }

    public function setDecPlace($dec_place)
    {
        if ($dec_place !== null) {
            $this->dec_place = $dec_place;
        }
    }

    public function getDecPlace()
    {
        return $this->dec_place;
    }

    public function setDecPoint($dec_point)
    {
        if ($dec_point !== null) {
            $this->dec_point = $dec_point;
        }
    }

    public function getDecPoint()
    {
        return $this->dec_point;
    }

    public function setThousandsSep($thousands_sep)
    {
        if ($thousands_sep !== null) {
            $this->thousands_sep = $thousands_sep;
        }
    }

    public function getThousandsSep()
    {
        return $this->thousands_sep;
    }

    public function setLanguageId($language_id)
    {
        if ($language_id !== null) {
            $this->language_id = $language_id;
        }
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setPaymentChargePercent($payment_charge_percent)
    {
        if ($payment_charge_percent !== null) {
            $this->payment_charge_percent = $payment_charge_percent;
        }
    }

    public function getPaymentChargePercent()
    {
        return $this->payment_charge_percent;
    }

    public function setForexFeePercent($forex_fee_percent)
    {
        if ($forex_fee_percent !== null) {
            $this->forex_fee_percent = $forex_fee_percent;
        }
    }

    public function getForexFeePercent()
    {
        return $this->forex_fee_percent;
    }

    public function setDeliveryType($delivery_type)
    {
        if ($delivery_type !== null) {
            $this->delivery_type = $delivery_type;
        }
    }

    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    public function setFreeDeliveryLimit($free_delivery_limit)
    {
        if ($free_delivery_limit !== null) {
            $this->free_delivery_limit = $free_delivery_limit;
        }
    }

    public function getFreeDeliveryLimit()
    {
        return $this->free_delivery_limit;
    }

    public function setDefaultShiptype($default_shiptype)
    {
        if ($default_shiptype !== null) {
            $this->default_shiptype = $default_shiptype;
        }
    }

    public function getDefaultShiptype()
    {
        return $this->default_shiptype;
    }

    public function setTaxTheresholds($tax_theresholds)
    {
        if ($tax_theresholds !== null) {
            $this->tax_theresholds = $tax_theresholds;
        }
    }

    public function getTaxTheresholds()
    {
        return $this->tax_theresholds;
    }

}
