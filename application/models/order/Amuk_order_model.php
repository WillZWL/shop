<?php

class Amuk_order_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/amuk_order_service');
	}

	public function get_ftp_info($name)
	{
		return $this->amuk_order_service->get_ftp_dao()->get(array("name"=>$name));
	}
}

?>