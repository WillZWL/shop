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
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setScenarioid($scenarioid)
    {
        if ($scenarioid != null) {
            $this->scenarioid = $scenarioid;
        }
    }

    public function getScenarioid()
    {
        return $this->scenarioid;
    }

    public function setCountryId($country_id)
    {
        if ($country_id != null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setShipMinDay($ship_min_day)
    {
        if ($ship_min_day != null) {
            $this->ship_min_day = $ship_min_day;
        }
    }

    public function getShipMinDay()
    {
        return $this->ship_min_day;
    }

    public function setShipMaxDay($ship_max_day)
    {
        if ($ship_max_day != null) {
            $this->ship_max_day = $ship_max_day;
        }
    }

    public function getShipMaxDay()
    {
        return $this->ship_max_day;
    }

    public function setDelMinDay($del_min_day)
    {
        if ($del_min_day != null) {
            $this->del_min_day = $del_min_day;
        }
    }

    public function getDelMinDay()
    {
        return $this->del_min_day;
    }

    public function setDelMaxDay($del_max_day)
    {
        if ($del_max_day != null) {
            $this->del_max_day = $del_max_day;
        }
    }

    public function getDelMaxDay()
    {
        return $this->del_max_day;
    }

    public function setMargin($margin)
    {
        if ($margin != null) {
            $this->margin = $margin;
        }
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
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
