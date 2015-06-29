<?php
include_once "Base_dto.php";

class aftership_data_dto extends Base_dto
{
    private $courier;
    private $trackingno;
    private $clientemail;
    private $buyertel;
    private $so_no;
    private $bill_name;
    private $country_code;
    private $dispatch_date;

    public function get_courier()
    {
        return $this->courier;
    }

    public function set_courier($value)
    {
        $this->courier = $value;
    }

    public function get_trackingno()
    {
        return $this->trackingno;
    }

    public function set_trackingno($value)
    {
        $this->trackingno = $value;
    }

    public function get_clientemail()
    {
        return $this->clientemail;
    }

    public function set_clientemail($value)
    {
        $this->clientemail = $value;
    }

    public function get_buyertel()
    {
        return $this->buyertel;
    }

    public function set_buyertel($value)
    {
        $this->buyertel = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_bill_name()
    {
        return $this->bill_name;
    }

    public function set_bill_name($value)
    {
        $this->bill_name = $value;
    }

    public function get_country_code()
    {
        return $this->country_code;
    }

    public function set_country_code($value)
    {
        $this->country_code = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

}