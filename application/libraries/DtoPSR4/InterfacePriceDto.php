<?php
class InterfacePriceDto
{
    private $batch_id;
    private $sku;
    private $platform_id = "AMUK";
    private $default_shiptype;
    private $price;
    private $status;
    private $allow_express;
    private $is_advertised;
    private $ext_mapping_code;
    private $listing_status;
    private $platform_code;
    private $batch_status;
    private $failed_reason;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $margin;

    public function setBatchId($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function getBatchId()
    {
        return $this->batch_id;
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

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
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

    public function setBatchStatus($batch_status)
    {
        $this->batch_status = $batch_status;
    }

    public function getBatchStatus()
    {
        return $this->batch_status;
    }

    public function setFailedReason($failed_reason)
    {
        $this->failed_reason = $failed_reason;
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
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

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getMargin()
    {
        return $this->margin;
    }

}
