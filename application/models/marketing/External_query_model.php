<?php
class External_query_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/yandex_product_feed_service');
		$this->load->library('service/ceneo_product_feed_service');
	}

	public function get_yandex_xml($platform_id)
	{
		$this->yandex_product_feed_service->get_yandex_xml($platform_id);
	}

	public function get_ceneo_xml($platform_id)
	{
		$this->ceneo_product_feed_service->get_ceneo_xml($platform_id);
	}


}
