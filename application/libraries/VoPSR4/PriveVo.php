<?php
class PriveVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform;
    private $price;
    private $is_advertised;
    private $listing_status;
    private $auto_price;
    private $fixed_rrp;
    private $rrp_factor;

    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    private $primary_key = array("id");

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getIsAdvertised()
    {
        return $this->is_advertised;
    }

    public function setIsAdvertised($is_advertised)
    {
        $this->is_advertised = $is_advertised;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getAutoPrice()
    {
        return $this->auto_price;
    }

    public function setAutoPrice($auto_price)
    {
        $this->auto_price = $auto_price;
    }

    public function getFixedRrp()
    {
        return $this->fixed_rrp;
    }

    public function setFixedRrp($fixed_rrp)
    {
        $this->fixed_rrp = $fixed_rrp;
    }

    public function getRrpFactor()
    {
        return $this->rrp_factor;
    }

    public function setRrpFactor($rrp_factor)
    {
        $this->rrp_factor = $rrp_factor;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }
}
