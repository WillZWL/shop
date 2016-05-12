<?php

class FulfillmentCentreVo extends \BaseVo
{
    private $id;
    private $fulfillment_centre_id = '';
    private $country_id;
    private $name;

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

    public function setFulfillmentCentreId($fulfillment_centre_id)
    {
        if ($fulfillment_centre_id !== null) {
            $this->fulfillment_centre_id = $fulfillment_centre_id;
        }
    }

    public function getFulfillmentCentreId()
    {
        return $this->fulfillment_centre_id;
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

}
