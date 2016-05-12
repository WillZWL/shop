<?php

class CountryLocalWarehouseVo extends \BaseVo
{
    private $country_id;
    private $warehouse_id;
    private $status;

    protected $primary_key = ['country_id', 'warehouse_id'];
    protected $increment_field = '';

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setWarehouseId($warehouse_id)
    {
        if ($warehouse_id !== null) {
            $this->warehouse_id = $warehouse_id;
        }
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
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
