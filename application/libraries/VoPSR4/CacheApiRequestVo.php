<?php

class CacheApiRequestVo extends \BaseVo
{
    private $id;
    private $api;
    private $sku;
    private $platform_id;
    private $stock_update = 'N';
    private $price_update = 'N';
    private $item_create;
    private $exec = '1';

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

    public function setApi($api)
    {
        if ($api !== null) {
            $this->api = $api;
        }
    }

    public function getApi()
    {
        return $this->api;
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

    public function setStockUpdate($stock_update)
    {
        if ($stock_update !== null) {
            $this->stock_update = $stock_update;
        }
    }

    public function getStockUpdate()
    {
        return $this->stock_update;
    }

    public function setPriceUpdate($price_update)
    {
        if ($price_update !== null) {
            $this->price_update = $price_update;
        }
    }

    public function getPriceUpdate()
    {
        return $this->price_update;
    }

    public function setItemCreate($item_create)
    {
        if ($item_create !== null) {
            $this->item_create = $item_create;
        }
    }

    public function getItemCreate()
    {
        return $this->item_create;
    }

    public function setExec($exec)
    {
        if ($exec !== null) {
            $this->exec = $exec;
        }
    }

    public function getExec()
    {
        return $this->exec;
    }

}
