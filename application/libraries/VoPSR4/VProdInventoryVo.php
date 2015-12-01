<?php
class VProdInventoryVo extends \BaseVo
{
    private $prod_sku = '';
    private $inventory;

    private $primary_key = [];
    private $increment_field = '';

    public function setProdSku($prod_sku)
    {
        if ($prod_sku != null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setInventory($inventory)
    {
        if ($inventory != null) {
            $this->inventory = $inventory;
        }
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
