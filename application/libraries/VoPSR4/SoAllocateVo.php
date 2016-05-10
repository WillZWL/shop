<?php
class SoAllocateVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $line_no;
    private $item_sku;
    private $qty;
    private $warehouse_id;
    private $sh_no;
    private $status = '0';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setLineNo($line_no)
    {
        if ($line_no !== null) {
            $this->line_no = $line_no;
        }
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setItemSku($item_sku)
    {
        if ($item_sku !== null) {
            $this->item_sku = $item_sku;
        }
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setQty($qty)
    {
        if ($qty !== null) {
            $this->qty = $qty;
        }
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setWarehouseId($warehouse_id)
    {
        if ($warehouse_id !== null) {
            $this->warehouse_id = $warehouse_id;
        }
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    public function setShNo($sh_no)
    {
        if ($sh_no !== null) {
            $this->sh_no = $sh_no;
        }
    }

    public function getShNo()
    {
        return $this->sh_no;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
