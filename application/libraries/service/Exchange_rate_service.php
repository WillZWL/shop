<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Exchange_rate_service extends Base_service
{
	public function __construct()
	{
		$CI =& get_instance();
		$CI->load->library('dao/currency_dao');
		$CI->load->library('dao/exchange_rate_dao');
		$CI->load->library('dao/currency_dao');
		$CI->load->library('dao/exchange_rate_approval_dao');
		$CI->load->library('dao/exchange_rate_history_dao');
		$this->currency_dao = $CI->currency_dao;
		$this->exchange_rate_dao = $CI->exchange_rate_dao;
		$this->exchange_rate_approval_dao = $CI->exchange_rate_approval_dao;
		$this->exchange_rate_history_dao = $CI->exchange_rate_history_dao;
		include_once(APPPATH."libraries/dao/Transmission_log_dao.php");
		$this->set_tlog_dao(new Transmission_log_dao());
		include_once(APPPATH."libraries/service/Ftp_connector.php");
		$this->set_ftp(new Ftp_connector());
		include_once(APPPATH."libraries/dao/Ftp_info_dao.php");
		$this->set_fi_dao(new Ftp_info_dao());
		include_once(APPPATH."libraries/service/Event_service.php");
		$this->set_event(new Event_service());
		include_once(APPPATH."libraries/dao/Exchange_rate_dao.php");
		$this->set_dao(new Exchange_rate_dao());
		include_once(APPPATH."libraries/dao/Interface_exchange_rate_dao.php");
		$this->set_iex_dao(new Interface_exchange_rate_dao());
		include_once(APPPATH."libraries/dao/Batch_dao.php");
		$this->set_batch_dao(new batch_dao());
		include_once(APPPATH."libraries/service/Validation_service.php");
		$this->set_valid(new Validation_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config(new Context_config_service());
	}

	public function get_ftp()
	{
		return $this->ftp;
	}

	public function set_ftp($value)
	{
		$this->ftp = $value;
	}

	public function get_fi_dao()
	{
		return $this->fi_dao;
	}

	public function set_fi_dao($value)
	{
		$this->fi_dao = $value;
	}

	public function get_tlog_dao()
	{
		return $this->tlog_dao;
	}

	public function set_tlog_dao($value)
	{
		$this->tlog_dao = $value;
	}

	public function get_iex_dao()
	{
		return $this->iex_dao;
	}

	public function set_iex_dao($value)
	{
		$this->iex_dao = $value;
	}

	public function get_batch_dao()
	{
		return $this->batch_dao;
	}

	public function set_batch_dao($value)
	{
		$this->batch_dao = $value;
	}

	public function get_valid()
	{
		return $this->valid;
	}

	public function set_valid($value)
	{
		$this->valid = $value;
	}

	public function get_active_currency_list()
	{
		$rtn = array();
		$where = array();
		$option = array();
		$obj_array = $this->currency_dao->get_active_currency_list($where, $option);
		foreach($obj_array as $obj)
		{
			$rtn[$obj->get_id()] = $obj->get_name();
		}

		return $rtn;
	}

	public function get_active_currency_obj_list()
	{
		return $this->currency_dao->get_active_currency_list($where = array(), $option = array());
	}

	public function get_currency_list($where=array(),$option=array())
	{
		$rtn = array();
		$obj_array = $this->currency_dao->get_list($where, $option);
		foreach($obj_array as $obj)
		{
			$rtn[$obj->get_id()] = $obj->get_name();
		}

		return $rtn;
	}

	public function alter_exchange_rate($from,$to,$rate,$dao)
	{
		if($from != "" && $to != "")
		{
			$obj = $this->$dao->get();
			$obj->set_from_currency_id($from);
			$obj->set_to_currency_id($to);
			$obj->set_rate($rate);
			if($dao == "exchange_rate_approval_dao")
			{
				$obj->set_approval_status("0");
			}
			elseif($dao == "exchange_rate_dao")
			{
				$app_obj = $this->exchange_rate_approval_dao->get();
				$app_obj->set_from_currency_id($from);
				$app_obj->set_to_currency_id($to);
				$app_obj->set_rate($rate);
				$app_obj->set_approval_status("1");
			}
			$num_row = $this->$dao->get_num_rows(array("from_currency_id"=>$from,"to_currency_id"=>$to));
			if($num_row)
			{
				//update
				$rtn = $this->$dao->update($obj);
				if($rtn && $dao == "exchange_rate_dao")
				{
					$rtn = $this->exchange_rate_approval_dao->update($app_obj);
				}
			}
			else
			{
				//insert
				$rtn = $this->$dao->insert($obj);
				if($rtn && $dao == "exchange_rate_dao")
				{
					$rtn = $this->exchange_rate_approval_dao->insert($app_obj);
				}
			}
		}
		else
		{
			$rtn =  FALSE;
		}

		return $rtn;
	}

	public function get_based_rate($base, $currency_list, $dao)
	{
		if($base != "")
		{
			$ret = array();
			foreach($currency_list as $key=>$value)
			{
				if($base != $key)
				{
					$obj = $this->$dao->get(array("from_currency_id"=>$base, "to_currency_id"=>$key));
					if(empty($obj))
					{
						$ret[$key] = 1.00;
					}
					else
					{
						$ret[$key] = $obj->get_rate();
					}
				}
				else
				{
					$ret[$base] = 1.00;
				}
			}
			$rtn = $ret ;
		}
		else
		{
			$rtn = FALSE;
		}
		return $rtn;
	}

	public function get_exchange_rate($from="",$to="")
	{
		if($from != "" && $to != "")
		{
			$ret = $this->exchange_rate_dao->get(array("from_currency_id"=>$from,"to_currency_id"=>$to));
		}
		else
		{
			$ret = $this->exchange_rate_dao->get();
		}

		return $ret;
	}

	public function get_exchange_rate_approval_list($where=array(), $option=array())
	{
		return $this->exchange_rate_approval_dao->get_list($where, $option);
	}

	function notification_email($sent_to, $value,$title='ex_rate_notification', $tpl_id="ex_rate_notice_email", $subject="")
	{
		include_once APPPATH."libraries/dto/event_email_dto.php";
		$email_dto = new Event_email_dto();

		$now_access_time = date("Y-m-d H:i:s");
		if($sent_to)
		{
			$dispatch_email = $email;
			$bill_name = $name;
			$tmp = clone $email_dto;
			$tmp->set_event_id($title);
			$tmp->set_mail_to($sent_to);
			$tmp->set_mail_from("no_reply@valuebasket.com");
			$tmp->set_tpl_id($tpl_id);
			$tmp->set_replace(array("remark"=>$value, "subject"=>$subject));
			$this->get_event()->fire_event($tmp);
		}
	}

	public function get_event()
	{
		return $this->event;
	}

	public function set_event($value)
	{
		$this->event = $value;
	}

	public function upload_exchange_rate()
	{
		$url = $this->get_config()->value_of('xrate_url');
		$currency_list = $this->currency_dao->get_list(array());
		$i = 0;
		foreach($currency_list as $obj)
		{
			if($i > 0)
			{
				$curr .= "_".$obj->get_id();
			}
			else
			{
				$curr .= $obj->get_id();
			}
			$i++;
		}
		$url = $url."&expr=".$curr."&exch=".$curr;
		$tmp[] = $xrate[] = array();
		$tmp = file_get_contents($url);
		$tmp = trim($tmp);
		if($tmp != "")
		{
			$filename = "exchange_rate".date("Ymd").".csv";
			$path = "/var/data/valuebasket.com/exchange_rate";
			if (file_exists($path.'/'.$filename))
			{
				unlink($path.'/'.$filename);
			}
			if($fp = @fopen($path.'/'.$filename,'w'))
			{
				@fwrite($fp,$tmp);
				@fclose($fp);
			}
		}
		$tmp = explode("\n", $tmp);
		for($i = 2; $i< count($tmp); $i++)
		{
			$xrate[$i-2] = $tmp[$i];
		}
		$remark = "";
		foreach($xrate AS $obj)
		{
			set_time_limit(120);
			$difference = 0;
			$data = explode(",", $obj);
			$date = $this->change_date_format($data[3]);
			if(!($this->exchange_rate_history_dao->get(array("from_currency_id"=>$data[0], "to_currency_id"=>$data[1], "date"=>$date["today"]))))
			{
				$xrate_today = $this->exchange_rate_history_dao->get();
				$xrate_today->set_from_currency_id($data[0]);
				$xrate_today->set_to_currency_id($data[1]);
				$xrate_today->set_date($date["today"]);
				$xrate_today->set_rate($data[4]);
				$this->exchange_rate_history_dao->insert($xrate_today);
			}
			$xrate_system = $this->exchange_rate_dao->get(array("from_currency_id"=>$data[0], "to_currency_id"=>$data[1]));
			if($xrate_system)
			{
				$difference = $this->calc_daily_diff($xrate_system->get_rate(), $data[4]);
			}
			if($difference)
			{
				if($diff_currency != $data[0] && $remark !="")
				{
					$remark.= "<br>";
				}

				$remark .= $data[0]."|".$data[1]."|".$xrate_system->get_rate()."|".$data[4]." => ".$difference."% difference<br>";
				$diff_currency = $data[0];
			}
			else
			{
				$xrate = $this->exchange_rate_dao->get(array("from_currency_id"=>$data[0], "to_currency_id"=>$data[1]));
				if($xrate)
				{
					$xrate->set_rate($data[4]);
				}
				else
				{
//					var_dump(__LINE__.$this->db->last_query());
				}
				$this->exchange_rate_dao->update($xrate);
			}
			$xrate_obj = $this->exchange_rate_approval_dao->get(array("from_currency_id"=>$data[0], "to_currency_id"=>$data[1]));
			if($xrate_obj)
			{
				$xrate_obj->set_rate($data[4]);
				if($difference)
				{
					$xrate_obj->set_approval_status("0");
				}
				else
				{
					$xrate_obj->set_approval_status("1");
				}
				$this->exchange_rate_approval_dao->update($xrate_obj);
			}
			else
			{
				$xrate_obj = $this->exchange_rate_approval_dao->get();
				$xrate_obj->set_from_currency_id($data[0]);
				$xrate_obj->set_to_currency_id($data[1]);
				$xrate_obj->set_rate($data[4]);
				if($difference)
				{
					$xrate_obj->set_approval_status("0");
				}
				else
				{
					$xrate_obj->set_approval_status("1");
				}
				$this->exchange_rate_approval_dao->insert($xrate_obj);
			}
		}
		$email_to = $this->get_config()->value_of('alan_email');
		if($remark)
		{
			$line = explode("<br><br>", $remark);
			foreach($line AS $line_obj)
			{
				if($line_obj)
				{
					$temp_obj = explode("<br>", $line_obj);
					if($temp_obj)
					{
						$num_obj = count($temp_obj);
						$header = "(".$num_obj." entries changed)<br>";
						$remark_revise .= $header.$line_obj."<br><br>";
					}
				}
			}
			$default_msg = "Daily Currency has been updated. Some currency need approval to effective.<br>From|To|Was Rate|Now Rate=>difference<br>";
			$remark_revise = $default_msg.$remark_revise."<br><br>Click <a href='http://admincentre.valuebasket.com/mastercfg/exchange_rate/view/'>here</a> to approve.";
			if($email_to && $remark_revise)
			{
				$subject = "Daily Exchange rates updated. Pending for approval.";
				$this->notification_email($email_to, $remark_revise,"daily_ex_rate", "daily_ex_rate_email", $subject);
			}
		}
		else
		{
			$header = "All exchange rates were approved automatically.<br>";
			$remark_revise = $header."<br><br>Click <a href='http://admincentre.valuebasket.com/mastercfg/exchange_rate/view/'>here</a> to view.";
			if($email_to && $remark_revise)
			{
				$subject = "Daily Exchange rates updated. No Approval Required.";
				$this->notification_email($email_to, $remark_revise,"daily_ex_rate", "daily_ex_rate_email", $subject);
			}
		}

	}

	public function get_exchange_rate_file()
	{
		include_once(BASEPATH."libraries/Encrypt.php");
		$encrypt = new CI_Encrypt();
		$local_path = $this->get_config()->value_of("ex_rate_data_path");
		$ftp = $this->get_ftp();
		$ftp_obj = $this->get_fi_dao()->get(array("name"=>"CV_EXCHANGE_RATE"));
		$ftp->set_remote_site($server = $ftp_obj->get_server());
		$ftp->set_username($ftp_obj->get_username());
		$ftp->set_password($encrypt->decode($ftp_obj->get_password()));
		$ftp->set_port($ftp_obj->get_port());
		$ftp->set_is_passive($ftp_obj->get_pasv());
		$remote_path = "/";
		$dao = $this->get_dao();
		$tlog_dao = $this->get_tlog_dao();
		$tlog_vo = $tlog_dao->get();
		$filename = "cv_exchange_rate_".date("Ymd").".csv";

		if($ftp->connect() !== FALSE)
		{
			if($ftp->login()!== FALSE)
			{
				if($ftp->getfile($local_path.$filename, $remote_path.$filename))
				{
					return $filename;
				}
				else
				{
					$tlog_obj = clone $tlog_vo;
					$tlog_obj->set_func_name($func);
					$tlog_obj->set_message("failed_to_download_cv_exchange_rate_file '".$server."'");
					$tlog_dao->insert($tlog_obj);
				}
			}
			else
			{
				$tlog_obj = clone $tlog_vo;
				$tlog_obj->set_func_name($func);
				$tlog_obj->set_message("failed_login_to_server '".$server."'");
				$tlog_dao->insert($tlog_obj);
			}
			$ftp->close();
		}
		else
		{
			$tlog_obj = clone $tlog_vo;
			$tlog_obj->set_func_name($func);
			$tlog_obj->set_message("cannot_connect_to_server '".$server."'");
			$tlog_dao->insert($tlog_obj);
		}
		return FALSE;
	}

	public function batch_exchange_rate($batch_id, $data)
	{
		if(empty($batch_id))
		{
			return false;
		}

		set_time_limit(180);
		$success = 1;
		if($batch_obj = $this->get_batch_dao()->get(array("id"=>$batch_id)))
		{
			$objlist = $this->get_iex_dao()->get_list(array("batch_id"=>$batch_id, "batch_status"=>"N"), array("limit"=>-1));
			if($objlist)
			{
				foreach($objlist AS $iex_obj)
				{
					$action = null;
					$rules["from_currency_id"]=array("not_empty");
					$rules["to_currency_id"]=array("not_empty");
					$rules["rate"] =array("is_number", "min=0");

					$rs = $this->validate_data_row($iex_obj, $rules);
					if ($rs["valid"])
					{
						if($ex_rate_obj = $this->get_dao()->get(array("from_currency_id"=>$iex_obj->get_from_currency_id(), "to_currency_id"=>$iex_obj->get_to_currency_id())))
						{
							if($iex_obj->get_rate() != $ex_rate_obj->get_rate())
							{
								$iex_obj->set_batch_status("R");
							}
							else
							{
								$iex_obj->set_batch_status("S");
							}
						}
						else
						{
							$iex_obj->set_batch_status("R");
						}
					}
					else
					{
						$iex_obj->set_batch_status("F");
						$iex_obj->get_failed_reason($rs["err_msg"]);
						$success = 0;
					}
					$this->get_iex_dao()->update($iex_obj);
				}
			}
		}

		if(!$success)
		{
			$batch_obj->set_status("CE");
			$batch_obj->set_end_time(date("Y-m-d H:i:s"));
			$this->get_batch_dao()->update($batch_obj);
		}

		$this->proceed_exchange_rate($batch_id);
	}

	public function proceed_exchange_rate($batch_id)
	{
		set_time_limit(180);
		$batch_err = 0;
		$err_msg = "";
		$batch_obj = $this->get_batch_dao()->get(array("id"=>$batch_id));
		if($batch_obj)
		{
			$batch_obj->set_status("P");
			$this->get_batch_dao()->update($batch_obj);

			$iex_list = $this->get_iex_dao()->get_list(array("batch_id"=>$batch_id, "batch_status"=>"R"), array("limit"=>-1));
			if(!empty($iex_list))
			{
				$ex_vo = $this->get_dao()->get();
				foreach($iex_list as $iex_obj)
				{
					if(!($this->exchange_rate_history_dao->get(array("from_currency_id"=>$iex_obj->get_from_currency_id(), "to_currency_id"=>$iex_obj->get_to_currency_id(), "date"=>date("Ymd 00:00:00")))))
					{
						$xrate_today = $this->exchange_rate_history_dao->get();
						$xrate_today->set_from_currency_id($iex_obj->get_from_currency_id());
						$xrate_today->set_to_currency_id($iex_obj->get_to_currency_id());
						$xrate_today->set_date(date("Ymd 00:00:00"));
						$xrate_today->set_rate($iex_obj->get_rate());
						$this->exchange_rate_history_dao->insert($xrate_today);
					}

					if($ex_obj = $this->get_dao()->get(array("from_currency_id"=>$iex_obj->get_from_currency_id(), "to_currency_id"=>$iex_obj->get_to_currency_id())))
					{
						$action = "update";
					}
					else
					{
						$action = "insert";
						$ex_obj = clone $ex_vo;
					}
					$ex_obj->set_from_currency_id($iex_obj->get_from_currency_id());
					$ex_obj->set_to_currency_id($iex_obj->get_to_currency_id());
					$ex_obj->set_rate($iex_obj->get_rate());

					$ret = $this->get_dao()->$action($ex_obj);
					if($ret === FALSE)
					{
						$iex_obj->set_batch_status('F');
						$iex_obj->set_failed_reason($this->get_dao()->db->_error_message());
						$this->get_iex_dao()->update($iex_obj);

						var_dump($this->get_dao()->db->_error_message());
						$err_msg .= "LINE: ".__LINE__."\nREASON: ".$this->get_dao()->db->_error_message()."\nSQL: ".$this->get_dao()->db->last_query()."\n\n\n";
						$batch_err = 1;
//						break;
					}
					else
					{
						$iex_obj->set_batch_status('S');
						$this->get_iex_dao()->update($iex_obj);
						$this->update_exchange_rate_approval($ex_obj);
					}
				}
			}
		}

		if($batch_err)
		{
			$batch_obj->set_status("CE");
			$batch_obj->set_end_time(date("Y-m-d H:i:s"));
			$this->get_batch_dao()->update($batch_obj);
			mail ("oswald-alert@eservicesgroup.com", "[VB] Batch Error", $err_msg , "From: Admin <itsupport@eservicesgroup.net>\r\n");
		}
		else
		{
			$batch_obj->set_status("C");
			$batch_obj->set_end_time(date("Y-m-d H:i:s"));
			$this->get_batch_dao()->update($batch_obj);
		}
	}

	public function update_exchange_rate_approval($ex_obj)
	{
		set_time_limit(180);
		$approval_obj = $this->exchange_rate_approval_dao->get(array("from_currency_id"=>$ex_obj->get_from_currency_id(), "to_currency_id"=>$ex_obj->get_to_currency_id()));
		if($approval_obj)
		{
			$approval_obj->set_rate($ex_obj->get_rate());
			$approval_obj->set_approval_status("1");
			$ret = $this->exchange_rate_approval_dao->update($approval_obj);
		}
		else
		{
			$approval_obj = $this->exchange_rate_approval_dao->get();
			$approval_obj->set_from_currency_id($ex_obj->get_from_currency_id());
			$approval_obj->set_to_currency_id($ex_obj->get_to_currency_id());
			$approval_obj->set_rate($ex_obj->get_rate());
			$approval_obj->set_approval_status("1");
			$ret = $this->exchange_rate_approval_dao->insert($approval_obj);
		}
		if($ret === FALSE)
		{
			mail ("steven@eservicesgroup.net", "[VB] Batch Error", "Unable to Approve the Exchange Rate\r\n" . $this->db->last_query() , "From: Admin <itsupport@eservicesgroup.net>\r\n");
		}
	}

	public function validate_data_row($data = array(), $rules = array())
	{
		if(empty($data))
		{
			return array("valid"=>0, "error_msg"=>"empty data set");
		}

		$this->get_valid()->set_data($data);
		$this->get_valid()->set_rules($rules);

		$rs = false;
		$err_msg = "";
		try
		{
			$rs = $this->get_valid()->run();
		}
		catch(Exception $e)
		{
			$err_msg = $e->getMessage();
		}

		if($rs)
		{
			return array("valid"=>1, "err_msg"=>null);
		}

		return array("valid"=>0, "err_msg"=>$err_msg);
	}

	public function validate_exchange_rate_file($filename)
	{
		if(empty($filename))
		{
			return false;
		}

		$local_path = $this->get_config()->value_of("ex_rate_data_path");
		$tlog_obj = $this->get_tlog_dao()->get();
		$tlog_obj->set_func_name("exchange_rate");
		$batch_obj = $this->get_batch_dao()->get(array("remark"=>$filename));
		$success = 1;
		if (!empty($batch_obj))
		{
			$tlog_obj->set_message($filename." already_in_batch");
			$this->get_tlog_dao()->insert($tlog_obj);
		}
		else
		{
			if ($handle = @fopen($local_path.$filename, "r"))
			{
				while (!feof($handle))
				{
					$tmp = trim(fgets($handle));
					if (!empty($tmp))
					{
						$ret[] = $ar_tmp = @explode(",", $tmp);
					}
					$rules[0]=array("not_empty");
					$rules[1]=array("not_empty");
					$rules[2]=array("is_number", "min=0");

					$rs = $this->validate_data_row($ar_tmp, $rules);
					if(!$rs["valid"])
					{
						$tlog_obj->set_message($rs["err_msg"]);
						$this->get_tlog_dao()->insert($tlog_obj);
						$success = 0;
					}
				}
				fclose($handle);
				if($success)
				{
					return $ret;
				}
			}
			else
			{
				$tlog_obj->set_message($filename." not_found");
				$this->get_tlog_dao()->insert($tlog_obj);
			}
		}
		return false;
	}

	public function update_exchange_rate_from_cv()
	{
		//if($filename = "cv_exchange_rate_20120328.csv")
		if($filename = $this->get_exchange_rate_file())
		{
			$batch_vo = $this->get_batch_dao()->get();
			if($rs = $this->validate_exchange_rate_file($filename))
			{
				$batch_obj = clone $batch_vo;
				$batch_obj->set_func_name("exchange_rate");
				$batch_obj->set_status("N");
				$batch_obj->set_listed(1);
				$batch_obj->set_remark($filename);
				$this->get_batch_dao()->insert($batch_obj);
			}
			else
			{
				mail ("steven@eservicesgroup.net", "[VB] Exchange Rate file format does not meet requirement", "For more details, please refer to transmission_log table", "From: Admin <itsupport@eservicesgroup.net>\r\n");
				exit;
			}

			$batch_obj = $this->get_batch_dao()->get(array("remark"=>$filename));
			$batch_id = $batch_obj->get_id();

			$iex_vo = $this->get_iex_dao()->get();
			foreach($rs AS $row)
			{
				$iex_obj = clone $iex_vo;
				$iex_obj->set_batch_id($batch_id);
				$iex_obj->set_batch_status("N");
				$iex_obj->set_from_currency_id($row[0]);
				$iex_obj->set_to_currency_id($row[1]);
				$iex_obj->set_rate($row[2]);
				$this->get_iex_dao()->insert($iex_obj);
			}

			$this->batch_exchange_rate($batch_id, $rs);

			// // return TRUE;
			include_once(APPPATH."libraries/service/Price_margin_service.php");
			$price_margin_service = new Price_margin_service();
			$result = $price_margin_service->refresh_all_platform_margin();

			if(isset($result["error_message"]))
			{
				mail($this->notification_email, "VB Error updating Price Margin", $result["error_message"]);
			}
		}
	}

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

	public function change_date_format($date)
	{
		$temp = explode("-", $date);
		$today = $temp[2]."-".$temp[0]."-".$temp[1]." 00:00:00";
		$yesterday = date('Y-m-d 00:00:00', mktime(0, 0, 0, $temp[0] , $temp[1] - 1, $temp[2]));
		return array("today"=>$today, "yesterday"=>$yesterday);
	}

	public function calc_daily_diff($was_rate, $now_rate)
	{
		if($was_rate != 0)
		{
			$diff = (($now_rate - $was_rate) / $was_rate)*100;
			if($diff > 5 || $diff < -5)
			{
				return number_format($diff, 4, '.', '');
			}
		}
	}

	public function compare_difference($from, $to, $rate)
	{
		$xrate_obj = $this->exchange_rate_dao->get(array("from_currency_id"=>$from, "to_currency_id"=>$to));
		if($xrate_obj->get_rate() != $rate)
		{
			$diff = number_format((($rate - $xrate_obj->get_rate()) / $xrate_obj->get_rate())*100,4,'.','');
			return $from."|".$to."|".$xrate_obj->get_rate()."|".$rate." => ".$diff ."% difference <br>";
		}
	}
}

?>