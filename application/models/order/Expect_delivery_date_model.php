<?php

class Expect_delivery_date_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/expect_delivery_date_service');
	}

	public function send_report($type)
	{
		if ($type)
			$this->expect_delivery_date_service->send_report($type);
	}
}

