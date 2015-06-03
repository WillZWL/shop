<?php

class Price_margin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->library('service/pagination_service');
		// $this->load->library('service/exchange_rate_service');
		// $this->load->library('service/currency_service');
		$this->load->library('service/price_margin_service');
	}

	public function refresh_all_platform_margin($platform_where=array())
	{
		return $this->price_margin_service->refresh_all_platform_margin($platform_where);
	}

	public function refresh_margin($platform_id = "")
	{
		return $this->price_margin_service->refresh_margin($platform_id );
	}

}
?>