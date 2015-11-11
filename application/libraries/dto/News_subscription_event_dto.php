<?php
include_once 'event_dto.php';

Class News_subscription_event_dto extends Event_dto
{
    private $email;

    public function news_subscription_event_dto()
    {
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($data)
    {
        $this->email = $data;
    }
}


