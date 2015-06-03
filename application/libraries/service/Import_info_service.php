<?php

include_once(APPPATH."libraries/service/Import_info_service.php");

interface Import_info_service_interface
{
	public function validate_import_data($data);
	public function process_data($batch_id, $is_reprocess = false);

/* for the csv file */
	public function get_data_exchange_file();
	public function get_is_first_line_header();
	public function get_delimiter();
	public function get_check_quote();
}

abstract class Import_info_service extends Base_service implements Import_info_service_interface
{
	const VALUE_IS_EMPTY = -1;
	const VALUE_IS_NOT_AN_INTEGER = -2;
	const VALUE_IS_NOT_A_VALID_DATE = -3;

	public $batch_import_dao;
	public $import_interface_info_dao;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Data_exchange_service.php");
		$this->set_dex_srv(new Data_exchange_service());
		include_once(APPPATH."libraries/dao/Batch_import_dao.php");
		$this->set_batch_import_dao(new Batch_import_dao());
		$dao_name = "Interface_" . $this->get_function_name() . "_dao";
		include_once(APPPATH."libraries/dao/" . ucfirst($dao_name) . ".php");
		$this->set_import_interface_info_dao(new $dao_name);
	}

	public function create_new_batch($function_name, $status, $remark)
	{
		$batch_id = -1;
		$batch_import_vo = $this->get_batch_import_dao()->get();
		$new_batch = clone $batch_import_vo;
		$new_batch->set_function_name($function_name);
		$new_batch->set_remark($remark);
		$new_batch->set_status($status);

		if (!$this->get_batch_import_dao()->insert($new_batch, FALSE))
		{
			$_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->get_batch_import_dao()->db->_error_message();
			return false;
		}
		else
		{
			$confirmed_record = $this->get_batch_import_dao()->get(array("function_name" => $function_name, "status"=>0, "remark"=>$remark));
			$batch_id = $confirmed_record->get_batch_id();
		}
		return $batch_id;
	}

	public function update_batch_status($batch_id, $status, $end_time = NULL)
	{
		$batch_obj = $this->get_batch_import_dao()->get(array("batch_id" => $batch_id));
		if ($batch_obj)
		{
			$batch_obj->set_status($status);
			if ($end_time == null)
				$end_time = date("Y-m-d H:i:s");
			$batch_obj->set_end_time($end_time);
			$this->get_batch_import_dao()->update($batch_obj);
		}
	}

	public function validSoNo($so_no)
	{
		if (preg_match('/^\d+$/', $so_no))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function validDate($postedDate)
	{
		if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $postedDate))
		{
			list($year , $month , $day) = explode('-', $postedDate);
			return checkdate($month , $day , $year);
		}
		else
		{
			return false;
		}
	}

	public function get_file_data($filename)
	{
		$dex_srv = $this->get_dex_srv();
		$obj_csv = new Csv_to_xml($filename, APPPATH . $this->get_data_exchange_file(), $this->get_is_first_line_header(), $this->get_delimiter(), $this->get_check_quote());

		$out_vo = new Xml_to_vo();
		return $dex_srv->convert($obj_csv, $out_vo);
	}

	public function set_dex_srv($srv)
	{
		$this->dex_srv = $srv;
	}

	public function get_dex_srv()
	{
		return $this->dex_srv;
	}

	public function set_batch_import_dao($dao)
	{
		$this->batch_import_dao = $dao;
	}

	public function get_batch_import_dao()
	{
		return $this->batch_import_dao;
	}

	public function set_import_interface_info_dao($dao)
	{
		$this->import_interface_info_dao = $dao;
	}

	public function get_import_interface_info_dao()
	{
		return $this->import_interface_info_dao;
	}
}
