<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Sku_mapping_feed_service extends Base_service
{
	const SKU_MAPPING_ERROR_NO_SCHEDULE_JOB_ID = 1;
	const SKU_MAPPING_ERROR_INVALID_MASTER_SKU = 2;
	const SKU_MAPPING_ERROR_CANNOT_CREATE_XML = 3;
	const SKU_MAPPING_ERROR_FAIL_TO_UPLOAD = 4;

	private $_support_email = "thomas@eservicesgroup.net";
	private $_schedule_job_service;
	private $_config_service;
	private $_ftp;
	private $_ftp_info_dao;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Sku_mapping_dao.php");
		$this->set_dao(new Sku_mapping_dao());
		include_once(APPPATH."libraries/service/Schedule_job_service.php");
		$this->_schedule_job_service = new Schedule_job_service();
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->_config_service = new Context_config_service();
		include_once(APPPATH."libraries/service/Ftp_connector.php");
		$this->_ftp = new Ftp_connector();
		include_once(APPPATH."libraries/dao/Ftp_info_dao.php");
		$this->_ftp_info_dao = new Ftp_info_dao();
	}

	public function generate_sku_mapping_difference($need_all_sku, $schedule_job_id)
	{
		$last_access_time = $this->_schedule_job_service->get_last_process_time($schedule_job_id);

		if (!$last_access_time)
		{
//alert IT
			$this->send_notification_email(Sku_mapping_feed_service::SKU_MAPPING_ERROR_NO_SCHEDULE_JOB_ID);
		}
		else
		{
			$where = array();
			if (!$need_all_sku)
			{
				$where['modify_on >'] = $last_access_time;
			}
			$where['status'] = 1;
			$where['ext_sys'] = 'WMS';

//			var_dump($where);
			$modified_sku = $this->get_dao()->get_list($where, array("order_by" => "modify_on asc", "groupby" => "sku", "limit" => -1));
//			print "<br><br>";
//			var_dump($modified_sku);
			if ($modified_sku)
			{
//new update found
				$xml = "<products>\n";
				foreach($modified_sku as $sku_record)
				{
					if($sku_record->get_ext_sku() != "")
					{
//var_dump($sku_record->get_master_sku());
						$xml .= "<mapping_vo>\n";
							$xml .= "<ext_sys>" . 'VB' . "</ext_sys>\n";
							if (strlen($sku_record->get_ext_sku()) >= 7)
							{
								//$master_sku = "M" . substr($sku_record->get_ext_sku(), 1, (strlen($sku_record->get_ext_sku()) - 1));
								$master_sku = $sku_record->get_ext_sku();
								$xml .= "<sku>" . trim($master_sku) . "</sku>\n";
								$xml .= "<ext_sku>" . trim($sku_record->get_sku()) . "</ext_sku>\n";
								$xml .= "<status>" . $sku_record->get_status() . "</status>\n";
							}
							else
							{
								$this->send_notification_email(Sku_mapping_feed_service::SKU_MAPPING_ERROR_INVALID_MASTER_SKU, $sku_record->get_ext_sku());
								exit;
							}
						$xml .= "</mapping_vo>\n";
					}
				}
				$xml .= "</products>\n";
//				print $xml;
				if((!is_null($sku_record)) && (!empty($sku_record)))
				{
					$date = date("YmdHis");
					$path = $this->_config_service->value_of("sku_mapping_feed_path");
					$remote_path = $this->_config_service->value_of("sku_mapping_feed_remote_path");
					$filename = "VBSkuMappingFeed" . $date . ".xml";

//at least there is 1 record to update
					$file_handle = fopen($path . $filename, 'w');
					if ($file_handle)
					{
						fwrite($file_handle, $xml);
						fclose($file_handle);

						if ($this->_upload_to_wms($path, $remote_path, $filename))
						{
//update last_access_time
							$this->_schedule_job_service->update_last_process_time($schedule_job_id, date("Y-m-d H:i:s"));
						}
						else
						{
							$this->send_notification_email(Sku_mapping_feed_service::SKU_MAPPING_ERROR_FAIL_TO_UPLOAD, $path . $filename);
						}
					}
					else
					{
						$this->send_notification_email(Sku_mapping_feed_service::SKU_MAPPING_ERROR_CANNOT_CREATE_XML, $path . $filename);
					}
				}
			}
		}
	}

	public function send_notification_email($error_id, $message = "")
	{
		$title = "[VB] Create SKU Mapping Error";
		switch ($error_id)
		{
			case Sku_mapping_feed_service::SKU_MAPPING_ERROR_NO_SCHEDULE_JOB_ID:
				$message = "There is no active schedule job id:" . $message . " in the database.";
				break;
			case Sku_mapping_feed_service::SKU_MAPPING_ERROR_INVALID_MASTER_SKU:
				$message = "There is an invalid master sku record, master_sku:" . $message;
				break;
			case Sku_mapping_feed_service::SKU_MAPPING_ERROR_CANNOT_CREATE_XML:
				$message = "Cannot create file:" . $message;
				break;
			case Sku_mapping_feed_service::SKU_MAPPING_ERROR_FAIL_TO_UPLOAD:
				$message = "File to upload:" . $message;
				break;
		}
		mail($this->_support_email, $title, $message);
	}

	private function _upload_to_wms($local_path, $remote_path, $filename)
	{
		include_once(BASEPATH."libraries/Encrypt.php");
		$encrypt = new CI_Encrypt();

		$ftp_info_obj = $this->_ftp_info_dao->get(array("name"=>"SKU_MAPPING"));
		$this->_ftp->set_remote_site($server = $ftp_info_obj->get_server());
		$this->_ftp->set_username($ftp_info_obj->get_username());
		$this->_ftp->set_password($encrypt->decode($ftp_info_obj->get_password()));
		$this->_ftp->set_port($ftp_info_obj->get_port());
		$this->_ftp->set_is_passive($ftp_info_obj->get_pasv());

		if($this->_ftp->connect() !== FALSE)
		{
			if($this->_ftp->login()!== FALSE)
			{
				return $this->_ftp->putfile($local_path . $filename, $remote_path . $filename);
			}
			else
				return FALSE;
		}
		else
			return FALSE;
	}
}

