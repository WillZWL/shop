<?php
include_once 'Base_vo.php';

class Ipligence_vo extends Base_vo
{

    //class variable
    private $ip_from = '0000000000';
    private $ip_to = '0000000000';
    private $country_code;
    private $country_name;
    private $continent_code;
    private $continent_name;

    //primary key
    private $primary_key = array("ip_to");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_ip_from()
    {
        return $this->ip_from;
    }

    public function set_ip_from($value)
    {
        $this->ip_from = $value;
        return $this;
    }

    public function get_ip_to()
    {
        return $this->ip_to;
    }

    public function set_ip_to($value)
    {
        $this->ip_to = $value;
        return $this;
    }

    public function get_country_code()
    {
        return $this->country_code;
    }

    public function set_country_code($value)
    {
        $this->country_code = $value;
        return $this;
    }

    public function get_country_name()
    {
        return $this->country_name;
    }

    public function set_country_name($value)
    {
        $this->country_name = $value;
        return $this;
    }

    public function get_continent_code()
    {
        return $this->continent_code;
    }

    public function set_continent_code($value)
    {
        $this->continent_code = $value;
        return $this;
    }

    public function get_continent_name()
    {
        return $this->continent_name;
    }

    public function set_continent_name($value)
    {
        $this->continent_name = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

}

?>