<?php
class DeliveryTimeListDto
{
    private $id;
    private $scenarioid;
    private $country_id;
    private $ship_min_day;
    private $ship_max_day;
    private $del_min_day;
    private $del_max_day;
    private $margin;
    private $dt_status;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $name;
    private $description;
    private $lookupscenario_status;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getScenarioid()
    {
        return $this->scenarioid;
    }

    public function setScenarioid($value)
    {
        $this->scenarioid = $value;
        return $this;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($value)
    {
        $this->country_id = $value;
        return $this;
    }

    public function getShipMinDay()
    {
        return $this->ship_min_day;
    }

    public function setShipMinDay($value)
    {
        $this->ship_min_day = $value;
        return $this;
    }

    public function getShipMaxDay()
    {
        return $this->ship_max_day;
    }

    public function setShipMaxDay($value)
    {
        $this->ship_max_day = $value;
        return $this;
    }

    public function getDelMinDay()
    {
        return $this->del_min_day;
    }

    public function setDelMinDay($value)
    {
        $this->del_min_day = $value;
        return $this;
    }

    public function getDelMaxDay()
    {
        return $this->del_max_day;
    }

    public function setDelMaxDay($value)
    {
        $this->del_max_day = $value;
        return $this;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMargin($value)
    {
        $this->margin = $value;
        return $this;
    }

    public function getDtStatus()
    {
        return $this->dt_status;
    }

    public function setDtStatus($value)
    {
        $this->dt_status = $value;
        return $this;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    public function getLookupscenarioStatus()
    {
        return $this->lookupscenario_status;
    }

    public function setLookupscenarioStatus($value)
    {
        $this->lookupscenario_status = $value;
        return $this;
    }
}

