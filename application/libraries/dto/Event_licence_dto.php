<?php
include_once 'Base_dto.php';

class Event_licence_dto extends Base_dto
{

    //class variable
    private $event_id;
    private $so_no;
    private $line_no;
    private $sku;
    private $licence_key;

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

    public function get_line_no()
    {
        return $this->line_no;
    }

    public function set_line_no($value)
    {
        $this->line_no = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_licence_key()
    {
        return $this->licence_key;
    }

    public function set_licence_key($value)
    {
        $this->licence_key = $value;
    }

}


