<?php

include_once "Base_service.php";

class Affiliate_sku_platform_service extends Base_service
{
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Affiliate_sku_platform_dao.php");
		$this->set_dao(new Affiliate_sku_platform_dao());
	}

	public function set_sku_feed_status($sku, $platform_id, $affiliate_id, $status_id)
	{
		return $this->get_dao()->set_sku_feed_status($sku, $platform_id, $affiliate_id, $status_id);
	}

	public function get_sku_feed_status($sku, $platform_id)
	{
		return $this->get_dao()->get_sku_feed_status($sku, $platform_id);
	}

	public function get_sku_feed_list($affiliate_id)
	{
		return $this->get_dao()->get_sku_feed_list($affiliate_id);
	}

	public function get_feed_list($platform_id = "")
	{
		return $this->get_dao()->get_feed_list($platform_id);
	}

	public function get_feed_list_by_sku($sku, $platform_id, $status=0)
	{
		return $this->get_dao()->get_feed_list_by_sku($sku, $platform_id, $status);
	}
}