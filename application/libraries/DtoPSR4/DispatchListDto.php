<?php
class DispatchListDto extends ShipmentInfoToCourierDto
{
    protected $warehouse_id;
    protected $bin;
    protected $empty;
    protected $no_info;

    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    public function setWarehouseId($value)
    {
        $this->warehouse_id = $value;
    }

    public function getBin()
    {
        return $this->bin;
    }

    public function setBin($value)
    {
        $this->bin = $value;
    }

    public function getEmpty()
    {
        return "";
    }

    public function setEmpty($value)
    {
        $this->empty = "";
    }

    public function getNoInfo()
    {
        return "no info";
    }

    public function setNoInfo($value)
    {
        $this->no_info = $value;
    }

    public function getOrderCreateDate()
    {
        if (!empty($this->order_create_date) && (!is_null($this->order_create_date))) {
            $result = explode(" ", $this->order_create_date);
            return $result[0];
        }
        return "";
    }
}
