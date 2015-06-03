<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Import_info_service.php";

class Import_finance_dispatch_service extends Import_info_service
{
	const FUNCTION_NAME = "finance_dispatch";

	function __construct()
	{
		parent::__construct();
		include_once(APPPATH . "libraries/service/So_service.php");
		$this->set_so_service(new So_service());
	}

	public function validate_import_data($data)
	{
		$result = array();
		$i = 0;
		foreach($data as $row)
		{
			$column = 0;
			$so_no = trim($row->get_so_no());
			if ((is_null($so_no)) || ($so_no == ""))
				$result[] = array("row" => $i, "column" => $column, "error_code" => Import_info_service::VALUE_IS_EMPTY);
			elseif (!$this->validSoNo($so_no))
				$result[] = array("row" => $i, "column" => $column, "error_code" => Import_info_service::VALUE_IS_NOT_AN_INTEGER);

			$column = 1;
			$dispatch_date = trim($row->get_finance_dispatch_date());
			if (!$this->validDate($dispatch_date))
				$result[] = array("row" => $i, "column" => $column, "error_code" => Import_info_service::VALUE_IS_NOT_A_VALID_DATE);
			$i++;
		}
		return $result;
	}

	public function process_data($batch_id, $is_reprocess = false)
	{
		$error_occur = false;
		$so_srv = $this->get_so_service();
		$option = array("limit" => -1);
		$where = array("batch_id" => $batch_id, "status" => "R");
		$process_list = $this->get_import_interface_info_dao()->get_list($where, $option);

		foreach($process_list as $data)
		{
			$so_obj = $so_srv->get_dao()->get(array("so_no" => trim($data->get_so_no())));
			if ($so_obj)
			{
				if ((!$is_reprocess) && ($so_obj->get_finance_dispatch_date() != '') and (!is_null($so_obj->get_finance_dispatch_date())))
				{
					$data->set_failed_reason("Dispatch Date uploaded before:" . $so_obj->get_finance_dispatch_date());
					$data->set_status("F");
					$error_occur = true;
				}
				else
				{
					$so_obj->set_finance_dispatch_date(trim($data->get_finance_dispatch_date()));
					$result = $so_srv->get_dao()->update($so_obj);
					if ($result !== FALSE)
						$data->set_status("S");
					else
					{
						$data->set_failed_reason("Cannot update so" . $so_srv->get_dao()->db->_error_message());
						$data->set_status("F");
						$error_occur = true;
					}
				}
			}
			else
			{
				$data->set_failed_reason("Cannot find this order number");
				$data->set_status("F");
				$error_occur = true;
			}
			$this->get_import_interface_info_dao()->update($data);
		}
		return $error_occur;
	}

	public function get_function_name()
	{
		return self::FUNCTION_NAME;
	}

	public function get_is_first_line_header()
	{
		return false;
	}

	public function get_delimiter()
	{
		return ",";
	}

	public function get_data_exchange_file()
	{
		return 'data/import_' . self::FUNCTION_NAME . '.txt';
	}

	public function get_check_quote()
	{
		return false;
	}

	public function set_so_service($serv)
	{
		$this->so_service = $serv;
	}

	public function get_so_service()
	{
		return $this->so_service;
	}

}


/* End of file batch_tracking_info_service.php */
/* Location: ./system/application/libraries/service/Batch_tracking_info_service.php */
