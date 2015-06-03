<?php
class Clwms extends PUB_Controller
{

	public function __construct()
	{
		parent::PUB_Controller();
		$this->load->library('service/clwms_service');
	}

	public function index($include_cc = 0)
	{
		$feed = $this->clwms_service->get_sales_order($include_cc);
		header('Content-type: text/xml');
		print $feed;
	}
}