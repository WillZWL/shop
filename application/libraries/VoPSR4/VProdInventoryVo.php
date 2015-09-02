<?php
class VProdInventoryVo extends \BaseVo
{
    private $id;
    private $prod_sku;
    private $inventory;

    private $primary_key = [];
    private $increment_field = '';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
