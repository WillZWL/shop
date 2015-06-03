<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Delivery_option_service extends Base_service
{
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Delivery_option_dao.php");
		$this->set_dao(new Delivery_option_dao());
	}

	public function display_name_of($courier_id, $lang_id="en")
	{
		return $this->get_dao()->display_name_of($courier_id, $lang_id);
	}

	public function get_list_w_key($where=array(), $option=array())
	{
		$data = array();
		if ($obj_list = $this->get_list($where, $option))
		{
			foreach ($obj_list as $obj)
			{
				$data[$obj->get_lang_id()][$obj->get_courier_id()] = $obj;
			}
		}
		return $data;
	}

}
