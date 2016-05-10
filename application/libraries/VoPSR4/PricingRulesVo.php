<?php
class PricingRulesVo extends \BaseVo
{
    private $id;
    private $country_id;
    private $range_min = '0.00';
    private $range_max = '0.00';
    private $mark_up_value = '0.00';
    private $mark_up_type;
    private $min_margin = '0.00';
    private $monday = '0';
    private $tuesday = '0';
    private $wednesday = '0';
    private $thursday = '0';
    private $friday = '0';
    private $saturday = '0';
    private $sunday = '0';
    private $status = '1';


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

    public function setRangeMin($range_min)
    {
        if ($range_min !== null) {
            $this->range_min = $range_min;
        }
    }

    public function getRangeMin()
    {
        return $this->range_min;
    }

    public function setRangeMax($range_max)
    {
        if ($range_max !== null) {
            $this->range_max = $range_max;
        }
    }

    public function getRangeMax()
    {
        return $this->range_max;
    }

    public function setMarkUpValue($mark_up_value)
    {
        if ($mark_up_value !== null) {
            $this->mark_up_value = $mark_up_value;
        }
    }

    public function getMarkUpValue()
    {
        return $this->mark_up_value;
    }

    public function setMarkUpType($mark_up_type)
    {
        if ($mark_up_type !== null) {
            $this->mark_up_type = $mark_up_type;
        }
    }

    public function getMarkUpType()
    {
        return $this->mark_up_type;
    }

    public function setMinMargin($min_margin)
    {
        if ($min_margin !== null) {
            $this->min_margin = $min_margin;
        }
    }

    public function getMinMargin()
    {
        return $this->min_margin;
    }

    public function setMonday($monday)
    {
        if ($monday !== null) {
            $this->monday = $monday;
        }
    }

    public function getMonday()
    {
        return $this->monday;
    }

    public function setTuesday($tuesday)
    {
        if ($tuesday !== null) {
            $this->tuesday = $tuesday;
        }
    }

    public function getTuesday()
    {
        return $this->tuesday;
    }

    public function setWednesday($wednesday)
    {
        if ($wednesday !== null) {
            $this->wednesday = $wednesday;
        }
    }

    public function getWednesday()
    {
        return $this->wednesday;
    }

    public function setThursday($thursday)
    {
        if ($thursday !== null) {
            $this->thursday = $thursday;
        }
    }

    public function getThursday()
    {
        return $this->thursday;
    }

    public function setFriday($friday)
    {
        if ($friday !== null) {
            $this->friday = $friday;
        }
    }

    public function getFriday()
    {
        return $this->friday;
    }

    public function setSaturday($saturday)
    {
        if ($saturday !== null) {
            $this->saturday = $saturday;
        }
    }

    public function getSaturday()
    {
        return $this->saturday;
    }

    public function setSunday($sunday)
    {
        if ($sunday !== null) {
            $this->sunday = $sunday;
        }
    }

    public function getSunday()
    {
        return $this->sunday;
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
