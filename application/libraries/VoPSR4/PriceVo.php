<?php
class PriceVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform_id;
    private $default_shiptype = '0';
    private $sales_qty = '0';
    private $price = '0.00';
    private $vb_price = '0.00';
    private $status = 'I';
    private $allow_express = 'N';
    private $is_advertised = 'N';
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
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setDefaultShiptype($default_shiptype)
    {
        $this->default_shiptype = $default_shiptype;
    }

    public function getDefaultShiptype()
    {
        return $this->default_shiptype;
    }

    public function setSalesQty($sales_qty)
    {
        $this->sales_qty = $sales_qty;
    }

    public function getSalesQty()
    {
        return $this->sales_qty;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setVbPrice($vb_price)
    {
        $this->vb_price = $vb_price;
    }

    public function getVbPrice()
    {
        return $this->vb_price;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAllowExpress($allow_express)
    {
        $this->allow_express = $allow_express;
    }

    public function getAllowExpress()
    {
        return $this->allow_express;
    }

    public function setIsAdvertised($is_advertised)
    {
        $this->is_advertised = $is_advertised;
    }

    public function getIsAdvertised()
    {
        return $this->is_advertised;
    }

    public function setExtMappingCode($ext_mapping_code)
    {
        $this->ext_mapping_code = $ext_mapping_code;
    }

    public function getExtMappingCode()
    {
        return $this->ext_mapping_code;
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

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setPlatformCode($platform_code)
    {
        $this->platform_code = $platform_code;
    }

    public function getPlatformCode()
    {
        return $this->platform_code;
    }

    public function setMaxOrderQty($max_order_qty)
    {
        $this->max_order_qty = $max_order_qty;
    }

    public function getMaxOrderQty()
    {
        return $this->max_order_qty;
    }

    public function setAutoPrice($auto_price)
    {
        $this->auto_price = $auto_price;
    }

    public function getAutoPrice()
    {
        return $this->auto_price;
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

    public function setDeliveryScenarioid($delivery_scenarioid)
    {
        $this->delivery_scenarioid = $delivery_scenarioid;
    }

    public function getDeliveryScenarioid()
    {
        return $this->delivery_scenarioid;
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

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
