<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_competitor_mapping extends MY_Controller
{
	private $app_id="CRN0018";

	function __construct()
	{
		parent::__construct();
		$this->load->model('marketing/competitor_mapping_model');
	}

	public function process_mapping_file($country_id = "GB", $debug_filename="")
	{
		$this->competitor_mapping_model->process_mapping_file($country_id, $debug_filename);
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

}