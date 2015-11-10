<?php
class RegionCountrynameDto
{
    private $region_id;
    private $country_id;
    private $name;
    private $region_name;

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function setRegionId($value)
    {
        $this->region_id = $value;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($value)
    {
        return $this->country_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getRegionName()
    {
        return $this->region_name;
    }

    public function setRegionName($value)
    {
        $this->region_name = $value;
    }
}