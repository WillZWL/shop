<?php
class EventDto
{
    private $event_id;

    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
    }

    public function getEventId()
    {
        return $this->event_id;
    }

}
