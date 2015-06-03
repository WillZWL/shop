<?php

class Adbanner_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/adbanner_service');
	}

	public function get_adbanner($value="")
	{
		return $this->adbanner_service->get($value);
	}

	public function get_banner_list()
	{
		return $this->adbanner_service->get_list_with_name("Adbanner_category_dto");
	}

	public function edit_adbanner($data)
	{
		return $this->adbanner_service->update($data);
	}

}

?>