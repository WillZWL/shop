<?php
include_once 'event_dto.php';

Class News_subscription_event_dto extends Event_dto 
{
	private $email;
	
	public function news_subscription_event_dto()
	{
	}

	public function set_email($data)
	{
		$this->email = $data;
	}
	
	public function get_email()
	{
		return $this->email;
	}
}

/* End of file news_subscription_evenht_dto.php */
/* Location: ./app/libraries/dto/news_subscription_event_dto.php */