<?php

include_once "Base_service.php";

class Sku_mapping_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Sku_mapping_dao.php");
		$this->set_dao(new Sku_mapping_dao());
	}

	public function get_master_sku($where = array())
	{
		if($obj = $this->get_dao()->get($where))
		{
			return $obj->get_ext_sku();
		}
		else
		{
			return false;
		}
	}

	public function get_local_sku($master_sku)
	{
		$where = array("ext_sku"=>$master_sku);
		if($obj = $this->get_dao()->get($where))
		{
			return $obj->get_sku();
		}
		else
		{
			return false;
		}
	}
}

/* End of file sku_mapping_service.php */
/* Location: ./app/libraries/service/Sku_mapping_service.php */