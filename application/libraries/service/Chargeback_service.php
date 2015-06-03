<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Chargeback_service extends Base_service
{
	public $dex_service;
	public $delivery_option_service;
	public $encrypt;
	private $so_dao;

	public function __construct()
	{
		$CI =& get_instance();
		$this->load = $CI->load;
		include_once(APPPATH."libraries/dao/Chargeback_dao.php");
		$this->set_dao(new Chargeback_dao());
		include_once(APPPATH."libraries/dao/So_dao.php");
		$this->set_so_dao(new So_dao());
		include_once(APPPATH."libraries/service/Data_exchange_service.php");
		$this->dex_service = new Data_exchange_service();
		include_once(APPPATH."libraries/service/Delivery_option_service.php");
		$this->delivery_option_service = new Delivery_option_service();
		include_once(BASEPATH . "libraries/Encrypt.php");
		$this->encrypt = new CI_Encrypt();
	}

	public function get_so_dao()
	{
		return $this->so_dao;
	}

	public function set_so_dao(Base_dao $dao)
	{
		$this->so_dao = $dao;
	}

	public function get_chargeback_data($filter=array())
	{
		return $this->get_dao()->get_chargeback_data($filter);
	}

	public function process_data($data=array(), $format = 'csv')
	{
		if(empty($data))
		{
			return;
		}

		$delivery_data = end($this->delivery_option_service->get_list_w_key(array("lang_id"=>"en")));

		$i = 0;
		foreach ($data as $obj)
		{
			$password = $this->encrypt->decode($obj->get_password());
			$obj->set_password($password);

			$del_mode = $obj->get_delivery_mode();
			$obj->set_ship_service_level($delivery_data[$del_mode]->get_display_name());

			// functions set in Chargeback_orders_dto
			$obj->set_bill_address($obj->get_bill_address());
			$obj->set_delivery_address($obj->get_delivery_address());
			$obj->set_payment_status($obj->get_payment_status());
			$obj->set_order_create_date_time($obj->get_order_create_date_time());
			$obj->set_hold_date_time($obj->get_hold_date_time());
		}

		if($format == "csv")
		{
			$result = $this->convert_to_csv($data);
		}

		return $result;
	}

	private function convert_to_csv($data = array())
	{
		if(empty($data))
		{
			return;
		}

		$i = 0;
		$data_str = "";
		$data_csv = array();
		$ignore = array(
						"get_hold_date_time","get_payment_status","get_bill_name","get_bill_address","get_delivery_forename",
						"get_delivery_surname","get_delivery_address","get_tel_1","get_tel_2","get_tel_3","get_delivery_mode","get_pay_to_account"
						);

		foreach ($data as $obj)
		{
			// all methods in chargeback_orders_dto gets constructed automatically with headers
			$classname = get_class($obj);
			if($methods = get_class_methods($classname))
			{
				foreach ($methods as $method)
				{
					if(in_array($method, $ignore))
						continue;

					if(strpos($method, "get") !== FALSE)
					{
						if($i == 0)
						{
							// create header
							$header .= str_replace("get_", "", $method) . ",";
						}

						// actual data
						if(method_exists($obj, $method))
						{
							$data_csv[$i] .= str_replace(',', ' ', $obj->$method()) .",";
						}
						else
						{
							$data_csv[$i] .= " ,";
						}
					}
				}
			}
			$i++;
		}
		if($data_csv)
		{
			array_unshift($data_csv, $header);
			foreach ($data_csv as $v)
			{
				$data_str .= trim($v,',')."\r\n";
			}
		}

		return $data_str;
	}

}

