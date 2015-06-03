<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_order_held_for_cc extends MY_Controller
{
	private $app_id="RPT0067";

	function __construct()
	{
		parent::__construct();
		$this->load->model('order/order_held_for_cc_model');
	}

	public function cron_cc_order_held_report($duration = null)
	{
		if ($type != null)
			$this->order_held_for_cc_model->send_report($duration);
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}
}