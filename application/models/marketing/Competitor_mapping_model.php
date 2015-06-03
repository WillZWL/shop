<?php
class Competitor_mapping_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/competitor_price_mapping_service');
	}

	public function process_mapping_file($country_id, $debug_filename)
	{
		$this->competitor_price_mapping_service->process_mapping_file($country_id, $debug_filename);
	}

}