<?php
class Price_comparison_report_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/rpt_price_comparison_report_service');
	}

	public function get_csv($where)
	{
		return $this->rpt_price_comparison_report_service->get_csv($where);
	}

}
?>