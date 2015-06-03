<?php
class Dispatch_with_hs_code_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/rpt_dispatch_with_hs_code_service');
	}

	public function get_csv($from_date, $to_date, $where = array())
	{
		return $this->rpt_dispatch_with_hs_code_service->get_csv($from_date, $to_date, $where);
	}

	public function get_header()
	{
		return $this->rpt_dispatch_with_hs_code_service->get_header();
	}

	public function get_obj_list($where = array(), $option = array())
	{
		return $this->rpt_dispatch_with_hs_code_service->get_obj_list($where, $option);
	}
}
