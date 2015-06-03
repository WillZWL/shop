<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Worldpay_parent_pmgw_report_service.php";

class Worldpay_pmgw_report_service extends Worldpay_parent_pmgw_report_service
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_pmgw()
	{
		return "worldpay";
	}
}
