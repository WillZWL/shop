<?php

class CountryBlockedPostalCodeVo extends \BaseVo
{
    private $id;
    private $country_id;
    private $blocked_postal_code;

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
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

    public function setBlockedPostalCode($blocked_postal_code)
    {
        if ($blocked_postal_code !== null) {
            $this->blocked_postal_code = $blocked_postal_code;
        }
    }

    public function getBlockedPostalCode()
    {
        return $this->blocked_postal_code;
    }

}
