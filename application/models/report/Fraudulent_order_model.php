<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fraudulent_order_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/fraudulent_order_service');
	}

	public function get_csv($start_date, $end_date, $where)
	{
		return $this->fraudulent_order_service->get_csv($start_date, $end_date, $where);
	}
}

/* End of file fraudulent_order_model.php */
/* Location: ./system/application/models/report/fraudulent_order_model.php */