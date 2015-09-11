<?php
class DeliveryTimeVo extends \BaseVo
{
    private $id;
    private $scenarioid;
    private $country_id;
    private $ship_min_day;
    private $ship_max_day;
    private $del_min_day;
    private $del_max_day;
    private $margin;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '127.0.0.1';
    private $modify_by;

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

    public function setScenarioid($scenarioid)
    {
        $this->scenarioid = $scenarioid;
    }

    public function getScenarioid()
    {
        return $this->scenarioid;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setShipMinDay($ship_min_day)
    {
        $this->ship_min_day = $ship_min_day;
    }

    public function getShipMinDay()
    {
        return $this->ship_min_day;
    }

    public function setShipMaxDay($ship_max_day)
    {
        $this->ship_max_day = $ship_max_day;
    }

    public function getShipMaxDay()
    {
        return $this->ship_max_day;
    }

    public function setDelMinDay($del_min_day)
    {
        $this->del_min_day = $del_min_day;
    }

    public function getDelMinDay()
    {
        return $this->del_min_day;
    }

    public function setDelMaxDay($del_max_day)
    {
        $this->del_max_day = $del_max_day;
    }

    public function getDelMaxDay()
    {
        return $this->del_max_day;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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
