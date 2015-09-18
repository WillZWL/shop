<?php
class ShipmentInfoDto
{
    private $sid;
    private $detail;
    private $status;
    private $reason;
    private $remark;
    private $tracking_no;
    private $courier;

    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    public function getSid()
    {
        return $this->sid;
    }

    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setCourier($courier)
    {
        $this->courier = $courier;
    }

    public function getCourier()
    {
        return $this->courier;
    }

}
