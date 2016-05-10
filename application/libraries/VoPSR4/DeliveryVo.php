<?php
class DeliveryVo extends \BaseVo
{
    private $id;
    private $delivery_type_id = '';
    private $country_id;
    private $min_day = '0';
    private $max_day = '0';
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

    public function setDeliveryTypeId($delivery_type_id)
    {
        if ($delivery_type_id !== null) {
            $this->delivery_type_id = $delivery_type_id;
        }
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
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

    public function setMinDay($min_day)
    {
        if ($min_day !== null) {
            $this->min_day = $min_day;
        }
    }

    public function getMinDay()
    {
        return $this->min_day;
    }

    public function setMaxDay($max_day)
    {
        if ($max_day !== null) {
            $this->max_day = $max_day;
        }
    }

    public function getMaxDay()
    {
        return $this->max_day;
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
