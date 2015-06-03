<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Currency_service extends Base_service
{
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Currency_dao.php");
		$this->set_dao(new Currency_dao());
		include_once(APPPATH."helpers/price_helper.php");
	}

	public function get_sign_w_id_key()
	{
		$data = array();
		if ($objlist = $this->get_list(array(),array("limit"=>-1)))
		{
			foreach ($objlist as $obj)
			{
				$data[$obj->get_id()] = $obj->get_sign();
			}
		}
		return $data;
	}

	public function get_name_w_id_key()
	{
		$data = array();
		if ($objlist = $this->get_list(array(),array("limit"=>-1)))
		{
			foreach ($objlist as $obj)
			{
				$data[$obj->get_id()] = $obj->get_name();
			}
		}
		return $data;
	}

	public function get_list_w_key($where=array(), $option=array())
	{
		$data = array();
		if ($objlist = $this->get_list($where, $option))
		{
			foreach ($objlist as $obj)
			{
				$data[$obj->get_id()] = $obj;
			}
		}
		return $data;
	}

	public function pre_load_currency_list($currency_id = NULL)
	{
		$data = array();
		$where = array();

		if ($currency_id)
		{
			$where["id"] = $currency_id;
		}

		if ($objlist = $this->get_list($where, array("limit"=>-1)))
		{
			foreach ($objlist as $obj)
			{
				$curr_id = $obj->get_id();
				$data[$curr_id] = array(
									"sign" => $obj->get_sign(),
									"sign_pos" => $obj->get_sign_pos(),
									"dec_place" => $obj->get_dec_place(),
									"dec_point" => $obj->get_dec_point(),
									"thousands_sep" => $obj->get_thousands_sep()
									);
			}
		}
		return $data;
	}

	public function round_up_of($currency_id)
	{
		return $this->get_dao()->get_round_up($currency_id);
	}

	public function get_platform_currency($platform)
	{
		return $this->get_dao()->get_by_platform($platform);
	}

	public function get_sign($platform)
	{
		return $this->get_dao()->get_sign($platform);
	}

}

/* End of file currency_service.php */
/* Location: ./system/application/libraries/service/Currency_service.php */