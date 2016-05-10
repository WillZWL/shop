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

    public function setScenarioid($scenarioid)
    {
        if ($scenarioid !== null) {
            $this->scenarioid = $scenarioid;
        }
    }

    public function getScenarioid()
    {
        return $this->scenarioid;
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

    public function setShipMinDay($ship_min_day)
    {
        if ($ship_min_day !== null) {
            $this->ship_min_day = $ship_min_day;
        }
    }

    public function getShipMinDay()
    {
        return $this->ship_min_day;
    }

    public function setShipMaxDay($ship_max_day)
    {
        if ($ship_max_day !== null) {
            $this->ship_max_day = $ship_max_day;
        }
    }

    public function getShipMaxDay()
    {
        return $this->ship_max_day;
    }

    public function setDelMinDay($del_min_day)
    {
        if ($del_min_day !== null) {
            $this->del_min_day = $del_min_day;
        }
    }

    public function getDelMinDay()
    {
        return $this->del_min_day;
    }

    public function setDelMaxDay($del_max_day)
    {
        if ($del_max_day !== null) {
            $this->del_max_day = $del_max_day;
        }
    }

    public function getDelMaxDay()
    {
        return $this->del_max_day;
    }

    public function setMargin($margin)
    {
        if ($margin !== null) {
            $this->margin = $margin;
        }
    }

    public function getMargin()
    {
        return $this->margin;
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
