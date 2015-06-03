<?php
include_once (CTRLPATH . '../models/cache/cache_model.php');
class Db_cache_model extends Cache_model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library("service/db_cache_service.php");
	}

	function save_xml_skype_feed($data = array())
	{
		$this->db_cache_service->save_xml_skype_feed($data);
	}

	function get_xml_skype_feed($data = array())
	{
		return $this->db_cache_service->get_xml_skype_feed($data);
	}
}
