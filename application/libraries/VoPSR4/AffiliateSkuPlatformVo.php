<?php
class AffiliateSkuPlatformVo extends \BaseVo
{
    private $id;
    private $affiliate_id;
    private $sku;
    private $platform_id = 'WEBSITE';
    private $status = '1';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAffiliateId($affiliate_id)
    {
        $this->affiliate_id = $affiliate_id;
    }

    public function getAffiliateId()
    {
        return $this->affiliate_id;
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

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }



}
