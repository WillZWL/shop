<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Wms_warehouse_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Wms_warehouse_dao.php");
		$this->set_dao(new Wms_warehouse_dao());
	}

	public function get_retailer_list()
	{
		return $this->get_dao()->get_list(array('type'=>'R'), array('orderby'=>'warehouse_id', 'limit'=>-1));
	}

	public function get_warehouse_list()
	{
		return $this->get_dao()->get_list(array('type'=>'W'), array('orderby'=>'warehouse_id', 'limit'=>-1));
	}
}

/* End of file wms_warehouse_service.php */
/* Location: ./system/application/libraries/service/Wms_warehouse_service.php */