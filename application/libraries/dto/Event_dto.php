<?php
include_once 'Base_dto.php';

Class Event_dto extends Base_dto
{
    private $event_id;

    public function event_dto()
    {
    }

    public function get_event_id()
    {
        return $this->event_id;
    }

    public function set_event_id($data)
    {
        $this->event_id = $event_id;
    }
}

/* End of file event_dto.php */
/* Location: ./app/libraries/dto/event_dto.php */