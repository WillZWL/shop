<?php
class CourierVo extends \BaseVo
{
    private $id;
    private $courier_id;
    private $aftership_id;
    private $courier_name;
    private $description;
    private $type = 'F';
    private $tracking_link;
    private $weight_type;
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

    public function setCourierId($courier_id)
    {
        $this->courier_id = $courier_id;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setAftershipId($aftership_id)
    {
        $this->aftership_id = $aftership_id;
    }

    public function getAftershipId()
    {
        return $this->aftership_id;
    }

    public function setCourierName($courier_name)
    {
        $this->courier_name = $courier_name;
    }

    public function getCourierName()
    {
        return $this->courier_name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTrackingLink($tracking_link)
    {
        $this->tracking_link = $tracking_link;
    }

    public function getTrackingLink()
    {
        return $this->tracking_link;
    }

    public function setWeightType($weight_type)
    {
        $this->weight_type = $weight_type;
    }

    public function getWeightType()
    {
        return $this->weight_type;
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
