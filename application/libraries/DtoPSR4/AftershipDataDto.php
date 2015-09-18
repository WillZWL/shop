<?php
class AftershipDataDto
{
    private $courier;
    private $trackingno;
    private $clientemail;
    private $buyertel;
    private $so_no;
    private $bill_name;
    private $country_code;
    private $dispatch_date;

    public function setCourier($courier)
    {
        $this->courier = $courier;
    }

    public function getCourier()
    {
        return $this->courier;
    }

    public function setTrackingno($trackingno)
    {
        $this->trackingno = $trackingno;
    }

    public function getTrackingno()
    {
        return $this->trackingno;
    }

    public function setClientemail($clientemail)
    {
        $this->clientemail = $clientemail;
    }

    public function getClientemail()
    {
        return $this->clientemail;
    }

    public function setBuyertel($buyertel)
    {
        $this->buyertel = $buyertel;
    }

    public function getBuyertel()
    {
        return $this->buyertel;
    }

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

    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
    }

    public function getCountryCode()
    {
        return $this->country_code;
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
