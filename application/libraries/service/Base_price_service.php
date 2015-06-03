<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

abstract class Base_price_service extends Base_service
{
	private $dto;
	private $fcc_service;
	private $wcc_service;
	private $region_service;
	private $platform_id;
	private $platform_curr_id;
	private $fulfillment_centre_id;

	public function __construct()
	{
		parent::__construct();
		include_once APPPATH."libraries/service/Freight_cat_service.php";
		$this->set_fcc_service(new Freight_cat_service());
		include_once APPPATH."libraries/service/Weight_cat_service.php";
		$this->set_wcc_service(new Weight_cat_service());
		include_once APPPATH."libraries/service/Region_service.php";
		$this->set_region_service(new Region_service());
	}

	abstract public function get_supp_to_fc_cost();

	abstract public function get_wh_fc_cost();

	abstract public function get_fc_to_customer_cost();

	protected function get_dto()
	{
		return $this->dto;
	}

	protected function set_dto(Base_dto $dto)
	{
		$this->dto = $dto;
	}

	protected function get_fcc_service()
	{
		return $this->fcc_service;
	}

	protected function set_fcc_service(Base_service $svc)
	{
		$this->fcc_service = $svc;
		return $this;
	}

	protected function get_wcc_service()
	{
		return $this->wcc_service;
	}

	protected function set_wcc_service(Base_service $svc)
	{
		$this->wcc_service = $svc;
		return;
	}

	protected function get_region_service()
	{
		return $this->region_service;
	}

	protected function set_region_service(Base_service $svc)
	{
		$this->region_service = $svc;
		return;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($platform_id = "")
	{
		$this->platform_id = $platform_id;
	}

	public function get_platform_curr_id()
	{
		return $this->platform_curr_id;
	}

	public function set_platform_curr_id($platform_curr_id = "")
	{
		$this->platform_curr_id = $platform_curr_id;
	}

	public function get_fulfillment_centre_id()
	{
		return $this->fulfillment_centre_id;
	}

	public function set_fulfillment_centre_id($fulfillment_centre_id = "")
	{
		$this->fulfillment_centre_id = $fulfillment_centre_id;
	}
}


?>