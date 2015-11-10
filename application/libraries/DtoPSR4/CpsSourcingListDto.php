<?php
class CpsSourcingListDto
{
    private $master_sku;
    private $item_sku;
    private $order_info;
    private $required_qty;
    private $inventory;
    private $avg_cost;

    public function setMasterSku($master_sku)
    {
        $this->master_sku = $master_sku;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setOrderInfo($order_info)
    {
        $this->order_info = $order_info;
    }

    public function getOrderInfo()
    {
        return $this->order_info;
    }

    public function setRequiredQty($required_qty)
    {
        $this->required_qty = $required_qty;
    }

    public function getRequiredQty()
    {
        return $this->required_qty;
    }

    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setAvgCost($avg_cost)
    {
        $this->avg_cost = $avg_cost;
    }

    public function getAvgCost()
    {
        return $this->avg_cost;
    }

}
