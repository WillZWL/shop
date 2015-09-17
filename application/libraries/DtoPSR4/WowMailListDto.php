<?php
class WowMailListDto
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

    public function setPayDate($pay_date)
    {
        $this->pay_date = $pay_date;
    }

    public function getPayDate()
    {
        return $this->pay_date;
    }

}
