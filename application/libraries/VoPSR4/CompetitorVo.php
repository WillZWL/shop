<?php

class CompetitorVo extends \BaseVo
{
    private $id;
    private $competitor_name;
    private $country_id;
    private $status;

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

    public function setCompetitorName($competitor_name)
    {
        if ($competitor_name !== null) {
            $this->competitor_name = $competitor_name;
        }
    }

    public function getCompetitorName()
    {
        return $this->competitor_name;
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
