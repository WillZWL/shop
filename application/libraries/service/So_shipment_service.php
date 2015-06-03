<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class So_shipment_service extends Base_service
{
	private $config;
	private $dex_service;

	public function __construct()
	{
		parent::__construct();

		include_once(APPPATH."libraries/dao/So_shipment_dao.php");
		$this->set_dao(new So_shipment_dao());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config(new Context_config_service());
		include_once(APPPATH."helpers/image_helper.php");
		include_once(APPPATH."libraries/service/Data_exchange_service.php");
		$this->set_dex_service(new Data_exchange_service());
	}

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

	public function set_dex_service($srv)
	{
		$this->dex_service = $srv;
	}

	public function get_dex_service()
	{
		return $this->dex_service;
	}
}

/* End of file so_shipment_service.php */
/* Location: ./app/libraries/service/So_service.php */
