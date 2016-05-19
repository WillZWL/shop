<?php
class ProductWebsiteQtyDto
{
    private $sku;
    private $prod_name;
    private $item_cost;
    private $vb_sku;
    private $master_sku;
    private $supply_status;

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
    }

    public function getItemCost()
    {
        return $this->item_cost;
    }

    public function setItemCost($value)
    {
        $this->item_cost = $value;
    }

    public function getVbSku()
    {
        return $this->vb_sku;
    }

    public function setVbSku($value)
    {
        $this->vb_sku = $value;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($value)
    {
        $this->master_sku = $value;
    }

    public function getSupplyStatus()
    {
        return $this->supply_status;
    }

    public function setSupplyStatus($value)
    {
        $this->supply_status = $value;
    }
}