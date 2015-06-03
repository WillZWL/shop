<?php

class Selling_platform_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/selling_platform_service');
	}

	public function get_list($where = array(), $option = array())
	{
		return $this->selling_platform_service->get_dao()->get_list($where, $option);
	}

	public function get_platform_by_lang($where = array(), $option = array())
	{
		return $this->selling_platform_service->get_platform_by_lang($where, $option);
	}

	public function get_selling_platform_w_lang_id($where = array(), $option = array())
	{
		return $this->selling_platform_service->get_selling_platform_w_lang_id($where, $option);
	}
}


?>