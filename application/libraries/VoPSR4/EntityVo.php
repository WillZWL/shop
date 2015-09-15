<?php
class EntityVo extends \BaseVo
{
    private $id;
    private $entity_id;
    private $name;
    private $country_id;
    private $business_registration_no;
    private $gst_no;
    private $registration_address;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;
    }

    public function getEntityId()
    {
        return $this->entity_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setBusinessRegistrationNo($business_registration_no)
    {
        $this->business_registration_no = $business_registration_no;
    }

    public function getBusinessRegistrationNo()
    {
        return $this->business_registration_no;
    }

    public function setGstNo($gst_no)
    {
        $this->gst_no = $gst_no;
    }

    public function getGstNo()
    {
        return $this->gst_no;
    }

    public function setRegistrationAddress($registration_address)
    {
        $this->registration_address = $registration_address;
    }

    public function getRegistrationAddress()
    {
        return $this->registration_address;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
