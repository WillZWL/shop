<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Region_countryname_dto extends base_dto
{
    private $region_id;
    private $country_id;
    private $name;
    private $region_name;

    public function region_countryname_dto()
    {
        parent::__construct();
    }

    public function get_region_id()
    {
        return $this->region_id;
    }

    public function set_region_id($value)
    {
        $this->region_id = $value;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        return $this->country_id;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
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
?>