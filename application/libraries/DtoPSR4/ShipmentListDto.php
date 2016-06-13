<?php
class ShipmentListDto
{
    private $so_no;
    private $warehouse_id;

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    public function setWarehouseId($value)
    {
        $this->warehouse_id = $value;
        return $this;
    }

}