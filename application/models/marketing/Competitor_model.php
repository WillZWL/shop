<?php

class Competitor_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/competitor_service');
		$this->load->library('service/competitor_reprice_service');
	}

	public function __autoload_competitor_vo()
	{
		$this->competitor_service->get_dao()->include_vo();
	}

	public function get_competitor_obj($where = array())
	{
		return $this->competitor_service->get($where);
	}

	public function insert($obj)
	{
		return $this->competitor_service->insert($obj);
	}

	public function update($obj)
	{
		return $this->competitor_service->update($obj);
	}

	public function seq_next_val()
	{
		return $this->competitor_service->get_dao()->seq_next_val();
	}

	public function reprice($platform_id, $echo_file, $debug_sku)
	{
		return $this->competitor_reprice_service->reprice($platform_id, $echo_file, $debug_sku);
	}

}
?>