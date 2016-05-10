<?php
class WmsInventoryVo extends \BaseVo
{
    private $id;
    private $warehouse_id;
    private $master_sku;
    private $inventory;
    private $git;


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

    public function setMasterSku($master_sku)
    {
        if ($master_sku !== null) {
            $this->master_sku = $master_sku;
        }
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setInventory($inventory)
    {
        if ($inventory !== null) {
            $this->inventory = $inventory;
        }
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setGit($git)
    {
        if ($git !== null) {
            $this->git = $git;
        }
    }

    public function getGit()
    {
        return $this->git;
    }

}
