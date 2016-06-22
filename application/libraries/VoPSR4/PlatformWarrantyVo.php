<?php

class PlatformWarrantyVo extends \BaseVo
{
    private $id;
    private $platform_id = '';
    private $accessories = '0';
    private $waterproof = '0';
    private $main_items = '0';
    private $action_camera = '0';
    private $drones = '0';
    private $refurbished = '0';
    private $no_warranty = '0';

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

    public function setAccessories($accessories)
    {
        if ($accessories !== null) {
            $this->accessories = $accessories;
        }
    }

    public function getAccessories()
    {
        return $this->accessories;
    }

    public function setWaterproof($waterproof)
    {
        if ($waterproof !== null) {
            $this->waterproof = $waterproof;
        }
    }

    public function getWaterproof()
    {
        return $this->waterproof;
    }

    public function setMainItems($main_items)
    {
        if ($main_items !== null) {
            $this->main_items = $main_items;
        }
    }

    public function getMainItems()
    {
        return $this->main_items;
    }

    public function setActionCamera($action_camera)
    {
        if ($action_camera !== null) {
            $this->action_camera = $action_camera;
        }
    }

    public function getActionCamera()
    {
        return $this->action_camera;
    }

    public function setDrones($drones)
    {
        if ($drones !== null) {
            $this->drones = $drones;
        }
    }

    public function getDrones()
    {
        return $this->drones;
    }

    public function setRefurbished($refurbished)
    {
        if ($refurbished !== null) {
            $this->refurbished = $refurbished;
        }
    }

    public function getRefurbished()
    {
        return $this->refurbished;
    }

    public function setNoWarranty($no_warranty)
    {
        if ($no_warranty !== null) {
            $this->no_warranty = $no_warranty;
        }
    }

    public function getNoWarranty()
    {
        return $this->no_warranty;
    }

}
