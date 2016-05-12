<?php

class EventVo extends \BaseVo
{
    private $id;
    private $event_id;
    private $event_name;
    private $description;
    private $status = '0';

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

    public function setEventId($event_id)
    {
        if ($event_id !== null) {
            $this->event_id = $event_id;
        }
    }

    public function getEventId()
    {
        return $this->event_id;
    }

    public function setEventName($event_name)
    {
        if ($event_name !== null) {
            $this->event_name = $event_name;
        }
    }

    public function getEventName()
    {
        return $this->event_name;
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
