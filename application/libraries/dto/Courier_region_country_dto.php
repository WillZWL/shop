<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Courier_region_country_dto extends Base_dto
{

    //class variable
    private $courier_id;
    private $region_name;
    private $countries;

    //instance method
    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
    }

    public function get_region_name()
    {
        return $this->region_name;
    }

    public function set_region_name($value)
    {
        $this->region_name = $value;
    }

    public function get_countries()
    {
        return $this->countries;
    }

    public function set_countries($value)
    {
        $this->countries = $value;
    }
}

/* End of file courier_region_country_dto.php */
/* Location: ./system/application/libraries/dto/courier_region_country_dto.php */