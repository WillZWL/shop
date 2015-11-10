<?php
class CourierRegionCountryDto
{
    private $courier_id;
    private $region_name;
    private $countries;

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCourierId($value)
    {
        $this->courier_id = $value;
    }

    public function getRegionName()
    {
        return $this->region_name;
    }

    public function setRegionName($value)
    {
        $this->region_name = $value;
    }

    public function getCountries()
    {
        return $this->countries;
    }

    public function setCountries($value)
    {
        $this->countries = $value;
    }
}
