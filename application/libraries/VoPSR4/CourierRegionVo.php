<?php

class CourierRegionVo extends \BaseVo
{
    private $id;
    private $courier_id;
    private $region_id;
    private $currency_id;

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

    public function setCourierId($courier_id)
    {
        if ($courier_id !== null) {
            $this->courier_id = $courier_id;
        }
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setRegionId($region_id)
    {
        if ($region_id !== null) {
            $this->region_id = $region_id;
        }
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

}
