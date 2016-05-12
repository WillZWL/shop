<?php

class EntityVo extends \BaseVo
{
    private $id;
    private $entity_id = '0';
    private $name = '';
    private $country_id;
    private $business_registration_no;
    private $gst_no = '';
    private $registration_address;

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

    public function setEntityId($entity_id)
    {
        if ($entity_id !== null) {
            $this->entity_id = $entity_id;
        }
    }

    public function getEntityId()
    {
        return $this->entity_id;
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

    public function setBusinessRegistrationNo($business_registration_no)
    {
        if ($business_registration_no !== null) {
            $this->business_registration_no = $business_registration_no;
        }
    }

    public function getBusinessRegistrationNo()
    {
        return $this->business_registration_no;
    }

    public function setGstNo($gst_no)
    {
        if ($gst_no !== null) {
            $this->gst_no = $gst_no;
        }
    }

    public function getGstNo()
    {
        return $this->gst_no;
    }

    public function setRegistrationAddress($registration_address)
    {
        if ($registration_address !== null) {
            $this->registration_address = $registration_address;
        }
    }

    public function getRegistrationAddress()
    {
        return $this->registration_address;
    }

}
