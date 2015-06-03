<?php
class Refund_reason_report_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/rpt_refund_reason_report_service');
	}

	public function get_csv($where = array())
	{
		return $this->rpt_refund_reason_report_service->get_csv($where);
	}

	public function send_email($filename, $csv, $msg)
	{
		if ($filename && $csv && $msg)
			return $this->rpt_refund_reason_report_service->email_report($filename, $csv, $msg);
		else
			return FALSE;
	}

}
