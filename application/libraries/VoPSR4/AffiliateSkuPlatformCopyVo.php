<?php

class AffiliateSkuPlatformCopyVo extends \BaseVo
{
    private $affiliate_id;
    private $sku = 'WEBSITE';
    private $platform_id;
    private $status = '1';

    protected $primary_key = ['affiliate_id', 'sku'];
    protected $increment_field = '';

    public function setAffiliateId($affiliate_id)
    {
        if ($affiliate_id !== null) {
            $this->affiliate_id = $affiliate_id;
        }
    }

    public function getAffiliateId()
    {
        return $this->affiliate_id;
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

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
