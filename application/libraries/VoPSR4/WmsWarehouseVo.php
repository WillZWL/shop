<?php
class WmsWarehouseVo extends \BaseVo
{
    private $type;
    private $warehouse_id;
    private $status = '1';
    private $modify_on = '';

    private $primary_key = ['type', 'warehouse_id'];
    private $increment_field = '';

    public function setType($type)
    {
        if ($type != null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setWarehouseId($warehouse_id)
    {
        if ($warehouse_id != null) {
            $this->warehouse_id = $warehouse_id;
        }
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
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
