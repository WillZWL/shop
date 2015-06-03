<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Delivery_type_service extends Base_service
{
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Delivery_type_dao.php");
		$this->set_dao(new Delivery_type_dao());
	}

	public function get_delivery_type_list()
	{
		if($list = $this->get_dao()->get_list())
		{
			foreach($list as $obj)
			{
				$rs[$obj->get_id()] = $obj->get_name();
			}
		}
		return $rs;
	}
}
