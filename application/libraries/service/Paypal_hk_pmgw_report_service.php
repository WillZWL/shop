<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Paypal_pmgw_report_service.php";

class Paypal_hk_pmgw_report_service extends Paypal_pmgw_report_service
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_pmgw()
	{
		return "paypal_hk";
	}
}
