<?php
class WmsWarehouseVo extends \BaseVo
{
    private $type;
    private $warehouse_id;
    private $status = '1';
    private $modify_on = 'CURRENT_TIMESTAMP';

    private $primary_key = ['type', 'warehouse_id'];

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }
}
