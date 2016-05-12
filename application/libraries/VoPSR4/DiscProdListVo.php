<?php

class DiscProdListVo extends \BaseVo
{
    private $platform_id;
    private $sku;

    protected $primary_key = ['platform_id', 'sku'];
    protected $increment_field = '';

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

}
