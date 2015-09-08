<?php
class CourierWithRegionDto
{
    private $courier_id;
    private $region_id;
    private $region_name;

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCourierId($value)
    {
        $this->courier_id = $value;
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function setRegionId($value)
    {
        $this->region_id = $value;
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
