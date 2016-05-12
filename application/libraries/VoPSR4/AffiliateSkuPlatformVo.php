<?php

class AffiliateSkuPlatformVo extends \BaseVo
{
    private $id;
    private $affiliate_id;
    private $sku;
    private $vb_sku;
    private $platform_id = 'WEBSITE';
    private $status = '1';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setVbSku($vb_sku)
    {
        if ($vb_sku !== null) {
            $this->vb_sku = $vb_sku;
        }
    }

    public function getVbSku()
    {
        return $this->vb_sku;
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
