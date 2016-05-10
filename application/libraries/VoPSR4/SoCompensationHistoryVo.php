<?php
class SoCompensationHistoryVo extends \BaseVo
{
    private $id;
    private $compensation_id;
    private $so_no;
    private $item_sku;
    private $note;
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

    public function setCompensationId($compensation_id)
    {
        if ($compensation_id !== null) {
            $this->compensation_id = $compensation_id;
        }
    }

    public function getCompensationId()
    {
        return $this->compensation_id;
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

    public function setNote($note)
    {
        if ($note !== null) {
            $this->note = $note;
        }
    }

    public function getNote()
    {
        return $this->note;
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
