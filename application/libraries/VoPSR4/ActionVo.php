<?php
class ActionVo extends \BaseVo
{
    private $id;
    private $event_id;
    private $action;
    private $description;
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

    public function setAction($action)
    {
        if ($action !== null) {
            $this->action = $action;
        }
    }

    public function getAction()
    {
        return $this->action;
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
