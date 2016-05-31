<?php
class PriceVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform_id;
    private $default_shiptype = '0';
    private $sales_qty = '0';
    private $price = '0.00';
    private $vb_price;
    private $allow_express = 'N';
    private $is_advertised = 'N';
    private $google_promo_id = '';
    private $ext_mapping_code = '';
    private $latency = '0';
    private $oos_latency = '0';
    private $listing_status = 'N';
    private $platform_code = '';
    private $max_order_qty = '100';
    private $auto_price = 'N';
    private $fixed_rrp = 'Y';
    private $rrp_factor = '1.34';
    private $delivery_scenarioid = '1';
    private $google_status = '';
    private $google_update_result = '';


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

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
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

    public function setSalesQty($sales_qty)
    {
        if ($sales_qty !== null) {
            $this->sales_qty = $sales_qty;
        }
    }

    public function getSalesQty()
    {
        return $this->sales_qty;
    }

    public function setPrice($price)
    {
        if ($price !== null) {
            $this->price = $price;
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setVbPrice($vb_price)
    {
        if ($vb_price !== null) {
            $this->vb_price = $vb_price;
        }
    }

    public function getVbPrice()
    {
        return $this->vb_price;
    }

    public function setAllowExpress($allow_express)
    {
        if ($allow_express !== null) {
            $this->allow_express = $allow_express;
        }
    }

    public function getAllowExpress()
    {
        return $this->allow_express;
    }

    public function setIsAdvertised($is_advertised)
    {
        if ($is_advertised !== null) {
            $this->is_advertised = $is_advertised;
        }
    }

    public function getIsAdvertised()
    {
        return $this->is_advertised;
    }

    public function setGooglePromoId($google_promo_id)
    {
        if ($google_promo_id !== null) {
            $this->google_promo_id = $google_promo_id;
        }
    }

    public function getGooglePromoId()
    {
        return $this->google_promo_id;
    }

    public function setExtMappingCode($ext_mapping_code)
    {
        if ($ext_mapping_code !== null) {
            $this->ext_mapping_code = $ext_mapping_code;
        }
    }

    public function getExtMappingCode()
    {
        return $this->ext_mapping_code;
    }

    public function setLatency($latency)
    {
        if ($latency !== null) {
            $this->latency = $latency;
        }
    }

    public function getLatency()
    {
        return $this->latency;
    }

    public function setOosLatency($oos_latency)
    {
        if ($oos_latency !== null) {
            $this->oos_latency = $oos_latency;
        }
    }

    public function getOosLatency()
    {
        return $this->oos_latency;
    }

    public function setListingStatus($listing_status)
    {
        if ($listing_status !== null) {
            $this->listing_status = $listing_status;
        }
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setPlatformCode($platform_code)
    {
        if ($platform_code !== null) {
            $this->platform_code = $platform_code;
        }
    }

    public function getPlatformCode()
    {
        return $this->platform_code;
    }

    public function setMaxOrderQty($max_order_qty)
    {
        if ($max_order_qty !== null) {
            $this->max_order_qty = $max_order_qty;
        }
    }

    public function getMaxOrderQty()
    {
        return $this->max_order_qty;
    }

    public function setAutoPrice($auto_price)
    {
        if ($auto_price !== null) {
            $this->auto_price = $auto_price;
        }
    }

    public function getAutoPrice()
    {
        return $this->auto_price;
    }

    public function setFixedRrp($fixed_rrp)
    {
        if ($fixed_rrp !== null) {
            $this->fixed_rrp = $fixed_rrp;
        }
    }

    public function getFixedRrp()
    {
        return $this->fixed_rrp;
    }

    public function setRrpFactor($rrp_factor)
    {
        if ($rrp_factor !== null) {
            $this->rrp_factor = $rrp_factor;
        }
    }

    public function getRrpFactor()
    {
        return $this->rrp_factor;
    }

    public function setDeliveryScenarioid($delivery_scenarioid)
    {
        if ($delivery_scenarioid !== null) {
            $this->delivery_scenarioid = $delivery_scenarioid;
        }
    }

    public function getDeliveryScenarioid()
    {
        return $this->delivery_scenarioid;
    }

    public function setGoogleStatus($google_status)
    {
        if ($google_status !== null) {
            $this->google_status = $google_status;
        }
    }

    public function getGoogleStatus()
    {
        return $this->google_status;
    }

    public function setGoogleUpdateResult($google_update_result)
    {
        if ($google_update_result !== null) {
            $this->google_update_result = $google_update_result;
        }
    }

    public function getGoogleUpdateResult()
    {
        return $this->google_update_result;
    }

}
