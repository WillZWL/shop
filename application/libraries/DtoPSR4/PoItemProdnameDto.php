<?php
class PoItemProdnameDto
{
    private $line_number;
    private $sku;
    private $name;
    private $order_qty;
    private $shipped_qty;
    private $unit_price;
    private $status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;

    public function setLineNumber($line_number)
    {
        $this->line_number = $line_number;
    }

    public function getLineNumber()
    {
        return $this->line_number;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOrderQty($order_qty)
    {
        $this->order_qty = $order_qty;
    }

    public function getOrderQty()
    {
        return $this->order_qty;
    }

    public function setShippedQty($shipped_qty)
    {
        $this->shipped_qty = $shipped_qty;
    }

    public function getShippedQty()
    {
        return $this->shipped_qty;
    }

    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

}
