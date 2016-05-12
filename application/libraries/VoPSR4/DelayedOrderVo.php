<?php

class DelayedOrderVo extends \BaseVo
{
    private $so_no;
    private $status = '0';

    protected $primary_key = ['so_no'];
    protected $increment_field = '';

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
