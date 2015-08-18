<?php

include_once 'Base_dto.php';

class Shipment_info_dto extends Base_dto
{
    private $sid;
    private $detail;
    private $status;
    private $reason;
    private $remark;
    private $tracking_no;
    private $courier;

    public function get_sid()
    {
        return $this->sid;
    }

    public function set_sid($value)
    {
        $this->sid = $value;
    }

    public function get_detail()
    {
        return $this->detail;
    }

    public function set_detail($value)
    {
        $this->detail = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }

    public function get_remark()
    {
        return $this->remark;
    }

    public function set_remark($value)
    {
        $this->remark = $value;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
    }

    public function get_courier()
    {
        return $this->courier;
    }

    public function set_courier($value)
    {
        $this->courier = $value;
    }
}

?>