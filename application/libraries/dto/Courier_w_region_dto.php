<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Courier_w_region_dto extends Base_dto {

    //class variable
    private $courier_id;
    private $region_id;
    private $region_name;

    //instance method
    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
    }

    public function get_region_id()
    {
        return $this->region_id;
    }

    public function set_region_id($value)
    {
        $this->region_id = $value;
    }

    public function get_region_name()
    {
        return $this->region_name;
    }

    public function set_region_name($value)
    {
        $this->region_name = $value;
    }
}

/* End of file courier_w_region_dto.php */
/* Location: ./system/application/libraries/dto/courier_w_region_dto.php */