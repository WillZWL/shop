<?php

class BankAccountVo extends \BaseVo
{
    private $id;
    private $acc_no;
    private $status;
    private $currency_id;
    private $timezone_gmt = '0';

    protected $primary_key = ['id', 'acc_no'];
    protected $increment_field = 'id';

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

    public function setAccNo($acc_no)
    {
        if ($acc_no !== null) {
            $this->acc_no = $acc_no;
        }
    }

    public function getAccNo()
    {
        return $this->acc_no;
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

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setTimezoneGmt($timezone_gmt)
    {
        if ($timezone_gmt !== null) {
            $this->timezone_gmt = $timezone_gmt;
        }
    }

    public function getTimezoneGmt()
    {
        return $this->timezone_gmt;
    }

}
