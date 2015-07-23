<?php
include_once 'Base_dto.php';

class Event_voucher_dto extends Base_dto
{

    //class variable
    private $event_id;
    private $so_no;
    private $voucher_detail_id;
    private $voucher_code;

    //instance method
    public function get_event_id()
    {
        return $this->event_id;
    }

    public function set_event_id($value)
    {
        $this->event_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_voucher_detail_id()
    {
        return $this->voucher_detail_id;
    }

    public function set_voucher_detail_id($value)
    {
        $this->voucher_detail_id = $value;
    }

    public function get_voucher_code()
    {
        return $this->voucher_code;
    }

    public function set_voucher_code($value)
    {
        $this->voucher_code = $value;
    }

}


