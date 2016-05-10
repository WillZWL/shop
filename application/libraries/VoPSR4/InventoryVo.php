<?php
class InventoryVo extends \BaseVo
{
    private $id;
    private $warehouse_id = '';
    private $prod_sku = '';
    private $inventory = '0';
    private $git = '0';
    private $surplus_qty = '0';


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

    public function setProdSku($prod_sku)
    {
        if ($prod_sku !== null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
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

    public function setSurplusQty($surplus_qty)
    {
        if ($surplus_qty !== null) {
            $this->surplus_qty = $surplus_qty;
        }
    }

    public function getSurplusQty()
    {
        return $this->surplus_qty;
    }

}
