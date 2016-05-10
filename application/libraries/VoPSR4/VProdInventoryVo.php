<?php
class VProdInventoryVo extends \BaseVo
{
    private $prod_sku = '';
    private $inventory;

    protected $primary_key = [];
    protected $increment_field = '';

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


}
