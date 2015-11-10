<?php
class ShipmentItemDto
{
    private $sid;
    private $po_number;
    private $sku;
    private $to_location;
    private $line_number;
    private $qty;
    private $status;
    private $reason_code;
    private $create_on;
    private $create_by;

    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    public function getSid()
    {
        return $this->sid;
    }

    public function setPoNumber($po_number)
    {
        $this->po_number = $po_number;
    }

    public function getPoNumber()
    {
        return $this->po_number;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setToLocation($to_location)
    {
        $this->to_location = $to_location;
    }

    public function getToLocation()
    {
        return $this->to_location;
    }

    public function setLineNumber($line_number)
    {
        $this->line_number = $line_number;
    }

    public function getLineNumber()
    {
        return $this->line_number;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setReasonCode($reason_code)
    {
        $this->reason_code = $reason_code;
    }

    public function getReasonCode()
    {
        return $this->reason_code;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

}
