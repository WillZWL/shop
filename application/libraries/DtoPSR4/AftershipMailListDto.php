<?php
class AftershipMailListDto
{
    private $so_no;
    private $bill_name;
    private $date_to_delivery;
    private $no_of_partial_shipment;
    private $courier_id;
    private $expect_ship_days;
    private $expect_del_days;
    private $dispatch_date;
    private $order_create_date;
    private $aftership_checkpoint;
    private $aftership_status;
    private $aftership_token;
    private $pay_date;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setBillName($bill_name)
    {
        $this->bill_name = $bill_name;
    }

    public function getBillName()
    {
        return $this->bill_name;
    }

    public function setDateToDelivery($date_to_delivery)
    {
        $this->date_to_delivery = $date_to_delivery;
    }

    public function getDateToDelivery()
    {
        return $this->date_to_delivery;
    }

    public function setNoOfPartialShipment($no_of_partial_shipment)
    {
        $this->no_of_partial_shipment = $no_of_partial_shipment;
    }

    public function getNoOfPartialShipment()
    {
        return $this->no_of_partial_shipment;
    }

    public function setCourierId($courier_id)
    {
        $this->courier_id = $courier_id;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setExpectShipDays($expect_ship_days)
    {
        $this->expect_ship_days = $expect_ship_days;
    }

    public function getExpectShipDays()
    {
        return $this->expect_ship_days;
    }

    public function setExpectDelDays($expect_del_days)
    {
        $this->expect_del_days = $expect_del_days;
    }

    public function getExpectDelDays()
    {
        return $this->expect_del_days;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setAftershipCheckpoint($aftership_checkpoint)
    {
        $this->aftership_checkpoint = $aftership_checkpoint;
    }

    public function getAftershipCheckpoint()
    {
        return $this->aftership_checkpoint;
    }

    public function setAftershipStatus($aftership_status)
    {
        $this->aftership_status = $aftership_status;
    }

    public function getAftershipStatus()
    {
        return $this->aftership_status;
    }

    public function setAftershipToken($aftership_token)
    {
        $this->aftership_token = $aftership_token;
    }

    public function getAftershipToken()
    {
        return $this->aftership_token;
    }

    public function setPayDate($pay_date)
    {
        $this->pay_date = $pay_date;
    }

    public function getPayDate()
    {
        return $this->pay_date;
    }

}
