<?php
class PricingRulesDto
{
    private $id;
    private $country_id;
    private $range_min = '0.00';
    private $range_max = '0.00';
    private $mark_up_value = '0.00';
    private $mark_up_type;
	private $mark_up_desc;
    private $min_margin = '0.00';
    private $monday = '0';
    private $tuesday = '0';
    private $wednesday = '0';
    private $thursday = '0';
    private $friday = '0';
    private $saturday = '0';
    private $sunday = '0';
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setRangeMin($range_min)
    {
        $this->range_min = $range_min;
    }

    public function getRangeMin()
    {
        return $this->range_min;
    }

    public function setRangeMax($range_max)
    {
        $this->range_max = $range_max;
    }

    public function getRangeMax()
    {
        return $this->range_max;
    }

    public function setMarkUpValue($mark_up_value)
    {
        $this->mark_up_value = $mark_up_value;
    }

    public function getMarkUpValue()
    {
        return $this->mark_up_value;
    }

    public function setMarkUpType($mark_up_type)
    {
        $this->mark_up_type = $mark_up_type;
    }

    public function getMarkUpType()
    {
        return $this->mark_up_type;
    }

    public function setMarkUpDesc($mark_up_desc)
    {
        $this->mark_up_desc = $mark_up_desc;
    }

    public function getMarkUpDesc()
    {
        return $this->mark_up_desc;
    }

    public function setMinMargin($min_margin)
    {
        $this->min_margin = $min_margin;
    }

    public function getMinMargin()
    {
        return $this->min_margin;
    }

    public function setMonday($monday)
    {
        $this->monday = $monday;
    }

    public function getMonday()
    {
        return $this->monday;
    }

    public function setTuesday($tuesday)
    {
        $this->tuesday = $tuesday;
    }

    public function getTuesday()
    {
        return $this->tuesday;
    }

    public function setWednesday($wednesday)
    {
        $this->wednesday = $wednesday;
    }

    public function getWednesday()
    {
        return $this->wednesday;
    }

    public function setThursday($thursday)
    {
        $this->thursday = $thursday;
    }

    public function getThursday()
    {
        return $this->thursday;
    }

    public function setFriday($friday)
    {
        $this->friday = $friday;
    }

    public function getFriday()
    {
        return $this->friday;
    }

    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;
    }

    public function getSaturday()
    {
        return $this->saturday;
    }

    public function setSunday($sunday)
    {
        $this->sunday = $sunday;
    }

    public function getSunday()
    {
        return $this->sunday;
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
}
