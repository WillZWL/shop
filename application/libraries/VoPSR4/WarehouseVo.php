<?php
class WarehouseVo extends \BaseVo
{
    private $id;
    private $warehouse_id;
    private $name;
    private $fc_id = '';
    private $address;
    private $region_id = '0';
    private $currency_id;

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

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFcId($fc_id)
    {
        if ($fc_id !== null) {
            $this->fc_id = $fc_id;
        }
    }

    public function getFcId()
    {
        return $this->fc_id;
    }

    public function setAddress($address)
    {
        if ($address !== null) {
            $this->address = $address;
        }
    }

    public function getAddress()
    {
        return $this->address;
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
