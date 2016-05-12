<?php

class CountryStateVo extends \BaseVo
{
    private $country_id;
    private $name = '';
    private $state_id;
    private $status;

    protected $primary_key = ['country_id', 'name'];
    protected $increment_field = '';

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

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setStateId($state_id)
    {
        if ($state_id !== null) {
            $this->state_id = $state_id;
        }
    }

    public function getStateId()
    {
        return $this->state_id;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
