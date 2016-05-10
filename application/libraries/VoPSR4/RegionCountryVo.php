<?php
class RegionCountryVo extends \BaseVo
{
    private $region_id;
    private $country_id;

    protected $primary_key = ['region_id', 'country_id'];
    protected $increment_field = '';

    public function setRegionId($region_id)
    {
        if ($region_id !== null) {
            $this->region_id = $region_id;
        }
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

}
