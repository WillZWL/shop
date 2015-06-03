<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Platform_integration extends MY_Controller
{
	private $app_id="CRN0007";

	function __construct()
	{
		parent::__construct();
		$this->load->model('marketing/platform_integration_model');
	}

	public function qoo10_orders($action, $country_id = "SG", $test=FALSE)
	{
		$this->platform_integration_model->get_qoo10_orders($action, $country_id, $test);
	}

	public function rakuten_orders($action, $country_id = "ES", $value="", $test=FALSE)
	{
		$this->platform_integration_model->get_rakuten_orders($action, $country_id, $value, $test);
	}

	public function client_delivery_contact_by_platform($platform_id = "ES", $day_diff=0)
	{
		$this->platform_integration_model->get_client_delivery_contact($platform_id, $day_diff);
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

}