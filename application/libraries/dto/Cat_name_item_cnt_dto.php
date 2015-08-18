<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Cat_name_item_cnt_dto extends Base_dto
{

    private $id;
    private $name;
    private $total;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_total()
    {
        return $this->total;
    }

    public function set_total($value)
    {
        $this->total = $value;
    }
}



