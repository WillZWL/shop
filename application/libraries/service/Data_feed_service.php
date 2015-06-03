<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

abstract class Data_feed_service extends Base_service
{
	private $prod_srv;
	private $so_srv;
	private $dex_srv;
	private $config_srv;
	private $ftp;
	private $fi_dao;
	private $sj_dao;
	private $tlog_dao;
	private $delimiter;
	private $vo2xml_mapping;
	private $xml2csv_mapping;
	protected $lang;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Product_service.php");
		$this->set_prod_srv(new Product_service());
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_srv(new So_service());
		include_once(APPPATH."libraries/service/Data_exchange_service.php");
		$this->set_dex_srv(new Data_exchange_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config_srv(new Context_config_service());
		include_once(APPPATH."libraries/service/Ftp_connector.php");
		$this->set_ftp(new Ftp_connector());
		include_once(APPPATH."libraries/dao/Ftp_info_dao.php");
		$this->set_fi_dao(new Ftp_info_dao());
		include_once(APPPATH."libraries/dao/Schedule_job_dao.php");
		$this->set_sj_dao(new Schedule_job_dao());
		include_once(APPPATH."libraries/dao/Transmission_log_dao.php");
		$this->set_tlog_dao(new Transmission_log_dao());

		$this->vo2xml_mapping = $this->get_default_vo2xml_mapping();
		$this->xml2csv_mapping = $this->get_default_xml2csv_mapping();
	}

	abstract protected function get_default_vo2xml_mapping();
	abstract protected function get_default_xml2csv_mapping();

	abstract protected function get_data_list($where = array(), $option = array());

	public function get_dex_srv()
	{
		return $this->dex_srv;
	}

	public function set_dex_srv(Base_service $srv)
	{
		$this->dex_srv = $srv;
	}

	public function get_prod_srv()
	{
		return $this->prod_srv;
	}

	public function set_prod_srv(Base_service $srv)
	{
		$this->prod_srv = $srv;
	}

	public function get_so_srv()
	{
		return $this->so_srv;
	}

	public function set_so_srv(Base_service $srv)
	{
		$this->so_srv = $srv;
	}

	public function get_config_srv()
	{
		return $this->config_srv;
	}

	public function set_config_srv(Base_service $srv)
	{
		$this->config_srv = $srv;
	}

	public function get_tlog_dao()
	{
		return $this->tlog_dao;
	}

	public function set_tlog_dao(Base_dao $dao)
	{
		$this->tlog_dao = $dao;
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

	public function set_fi_dao(Base_dao $dao)
	{
		$this->fi_dao = $dao;
	}

	public function get_sj_dao()
	{
		return $this->sj_dao;
	}

	public function set_sj_dao(Base_dao $dao)
	{
		$this->sj_dao = $dao;
	}

	public function get_vo2xml_mapping()
	{
		return $this->vo2xml_mapping;
	}

	public function set_vo2xml_mapping($mapping = '')
	{
		$this->vo2xml_mapping = $mapping;
	}

	public function get_xml2csv_mapping()
	{
		return $this->xml2csv_mapping;
	}

	public function set_xml2csv_mapping($mapping = '')
	{
		$this->xml2csv_mapping = $mapping;
	}

	public function set_output_delimiter($str = '')
	{
		if (is_object($str))
		{
			return; // Nothing should be set.
		}

		$this->delimiter = $str;
		settype($this->delimiter, 'string');
	}

	public function get_output_delimiter()
	{
		return $this->delimiter;
	}

	public function get_contact_email()
	{
		return 'itsupport@eservicesgroup.net';
	}

	public function gen_data_feed()
	{
		$data_feed = $this->get_data_feed();
	}

	public function get_data_feed($first_line_headling = TRUE, $where = array(), $option = array())
	{
		$arr = $this->get_data_list($where, $option);

		if (!$arr)
		{
			return;
		}

		$new_list = array();

		foreach ($arr as $row)
		{
			$new_list[] = $this->process_data_row($row);
		}

		$content = $this->convert($new_list, $first_line_headling);

		return $content;
	}

	public function process_data_row($data = NULL)
	{
		if (!is_object($data))
		{
			return NULL;
		}

		return $data;
	}

	public function convert($list = array(), $first_line_headling = TRUE)
	{
		$out_xml = new Vo_to_xml($list, $this->get_vo2xml_mapping());
		$out_csv = new Xml_to_csv("", $this->get_xml2csv_mapping(), $first_line_headling, $this->get_output_delimiter());

		return $this->get_dex_srv()->convert($out_xml, $out_csv);
	}

	function ftp_feeds($local_file, $remote_file, $ftp_name)
	{
		include_once(BASEPATH."libraries/Encrypt.php");
		$encrypt = new CI_Encrypt();
		$ftp = $this->get_ftp();
		if($ftp_obj = $this->get_fi_dao()->get(array("name"=>$ftp_name)))
		{
			$ftp->set_remote_site($server = $ftp_obj->get_server());
			$ftp->set_username($ftp_obj->get_username());
			$ftp->set_password($encrypt->decode($ftp_obj->get_password()));
			$ftp->set_port($ftp_obj->get_port());
			$ftp->set_is_passive($ftp_obj->get_pasv());
			$tlog_dao = $this->get_tlog_dao();
			$tlog_vo = $tlog_dao->get();

			if($ftp->connect() !== FALSE)
			{
				if($ftp->login()!== FALSE)
				{
					if($ftp->putfile($local_file, $remote_file))
					{
						$this->update_schedule_job_record();
					}
					else
					{
						$this->log_error_message("file can not be uploaded");
						return FALSE;
					}
				}
				else
				{
					$this->log_error_message("unable_to_login_to_server '".$server."'");
					return FALSE;
				}
			}
			else
			{
				$this->log_error_message("connot_connect_to_server '".$server."'");
				return FALSE;
			}
		}
		else
		{
			$this->log_error_message("ftp_info not found '".$server."'");
			return FALSE;
		}
	}

	public function update_schedule_job_record()
	{
		$update_time = date("Y-m-d H:i:s");
		if($sj_obj = $this->get_sj_dao()->get(array("id"=>$this->get_sj_id())))
		{
			$sj_obj->set_last_access_time($update_time);
			if($this->get_sj_dao()->update($sj_obj) === FALSE)
			{
				$this->log_error_message("Schedule Job table Update Error\n".$this->get_sj_dao()->db->last_query());
				return FALSE;
			}
		}
		else
		{
			$sj_obj = $this->get_sj_dao()->get();
			$sj_obj->set_id($this->get_sj_id());
			$sj_obj->set_name($this->get_sj_name());
			$sj_obj->set_last_access_time($update_time);
			$sj_obj->set_status(1);
			$sj_obj->set_create_on($update_time);
			$sj_obj->set_create_at('localhost');
			$sj_obj->set_create_by('system');
			$sj_obj->set_modify_on($update_time);
			$sj_obj->set_modify_at('localhost');
			$sj_obj->set_modify_by('system');
			if(!$this->get_sj_dao()->insert($sj_obj))
			{
				$this->log_error_message("Schedule Job table Insert Error\n".$this->get_sj_dao()->db->last_query());
				return FALSE;
			}
		}
	}

	// get last access time from scheduled job table
	protected function get_last_access_time()
	{
		return null;
	}

	public function error_handler($subject = '', $msg = '', $is_dead = false)
	{
		//echo $msg;
		$subject = $subject?$subject:'Data Feed Failed';

		if ($subject)
		{
			mail($this->get_contact_email(), $subject,
				$msg, 'From: itsupport@eservicesgroup.net');
		}

		if ($is_dead)
		{
			exit;
		}
	}

	public function log_error_message($message)
	{
		$tlog_dao = $this->get_tlog_dao();
		$tlog_vo = $tlog_dao->get();
		$tlog_obj = clone $tlog_vo;
		$tlog_obj->set_func_name($this->get_sj_id());
		$tlog_obj->set_message($message);
		$tlog_dao->insert($tlog_obj);

		$title = empty($this->id)?"Data Feed":$this->id;

		mail($this->get_contact_email(), '[VB]' . $title . ' error', $message);
	}

	protected function load_language($lang_id='en')
	{
		$language_path = APPPATH . "/language/" . $lang_id . "/nocontroller/data_feed.ini";
		if (file_exists($language_path))
		{
			$this->lang = parse_ini_file($language_path);
		}
	}

	protected function get_affiliate_id_prefix()
	{
		return "";
	}

	public function del_dir($dir)
	{
		if(!is_readable($dir))
	    {
	        is_file($dir) or mkdir($dir,0777);
	    }
		$dir_arr = scandir($dir);
        foreach($dir_arr as $key=>$val){
            if($val == '.' || $val == '..'){}
            else {
                if(is_dir($dir.'/'.$val))
                {
                 	if(@rmdir($dir.'/'.$val) == 'true'){}
                    else
                    	$this->del_dir($dir.'/'.$val);
                }
                else
                	unlink($dir.'/'.$val);
         	}
        }
	}
}

/* End of file data_feed_service.php */
/* Location: ./system/application/libraries/service/Data_feed_service.php */