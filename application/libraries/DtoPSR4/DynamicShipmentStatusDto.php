<?php
class DynamicShipmentStatusDto
{
    private $so_no;
    private $pay_date;
    private $order_status;
    private $aftership_status;
    private $last_update_time;
    private $comment;
    private $dispatch_date;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPayDate($pay_date)
    {
        $this->pay_date = $pay_date;
    }

    public function getPayDate()
    {
        return $this->pay_date;
    }

    public function setOrderStatus($order_status)
    {
        $this->order_status = $order_status;
    }

    public function getOrderStatus()
    {
        return $this->order_status;
    }

    public function setAftershipStatus($aftership_status)
    {
        $this->aftership_status = $aftership_status;
    }

    public function getAftershipStatus()
    {
        return $this->aftership_status;
    }

    public function setLastUpdateTime($last_update_time)
    {
        $this->last_update_time = $last_update_time;
    }

    public function getLastUpdateTime()
    {
        return $this->last_update_time;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

}
