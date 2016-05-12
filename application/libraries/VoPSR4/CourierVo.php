<?php

class CourierVo extends \BaseVo
{
    private $id;
    private $courier_id = '';
    private $aftership_id = '';
    private $courier_name = '';
    private $description = '';
    private $type = 'F';
    private $tracking_link = '';
    private $weight_type = '';
    private $show_status = '0';

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

    public function setCourierId($courier_id)
    {
        if ($courier_id !== null) {
            $this->courier_id = $courier_id;
        }
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setAftershipId($aftership_id)
    {
        if ($aftership_id !== null) {
            $this->aftership_id = $aftership_id;
        }
    }

    public function getAftershipId()
    {
        return $this->aftership_id;
    }

    public function setCourierName($courier_name)
    {
        if ($courier_name !== null) {
            $this->courier_name = $courier_name;
        }
    }

    public function getCourierName()
    {
        return $this->courier_name;
    }

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTrackingLink($tracking_link)
    {
        if ($tracking_link !== null) {
            $this->tracking_link = $tracking_link;
        }
    }

    public function getTrackingLink()
    {
        return $this->tracking_link;
    }

    public function setWeightType($weight_type)
    {
        if ($weight_type !== null) {
            $this->weight_type = $weight_type;
        }
    }

    public function getWeightType()
    {
        return $this->weight_type;
    }

    public function setShowStatus($show_status)
    {
        if ($show_status !== null) {
            $this->show_status = $show_status;
        }
    }

    public function getShowStatus()
    {
        return $this->show_status;
    }

}
