<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class T3m_handle_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once APPPATH."libraries/service/So_service.php";
		$this->so_svc = new So_service();
		include_once APPPATH."libraries/service/Client_service.php";
		$this->client_svc = new Client_service();
		include_once APPPATH."libraries/service/Product_service.php";
		$this->prod_svc = new Product_service();
		include_once(APPPATH."libraries/service/Event_service.php");
		$this->event_srv = new Event_service();
		include_once(APPPATH."libraries/service/T3m_filelog_service.php");
		$this->t3mfile_srv = new T3m_filelog_service();
		include_once(BASEPATH."libraries/Encrypt.php");
		$this->encrypt = new CI_Encrypt();
		include_once(APPPATH."libraries/service/Batch_service.php");
		$this->batch_svc = new Batch_service();
		include_once(APPPATH."libraries/service/Interface_t3m_score_service.php");
		$this->int_t3m_svc = new Interface_t3m_score_service();
		include_once(APPPATH."libraries/service/Validation_service.php");
		$this->valid_svc = new Validation_service();
		include_once(APPPATH."libraries/service/Data_exchange_service.php");
		$this->dex_svc = new Data_exchange_service();
		include_once(APPPATH."libraries/dao/Transmission_log_dao.php");
		$this->tlog_dao = new Transmission_log_dao();
	}

	public function send_request()
	{
		//get unchecked list
		$so_list = $this->so_svc->get_socc_dao()->get_list(array("t3m_is_sent"=>'N'));

		$result = array();


		if(array($so_list))
		{
			$result = array();

			$tmp_cnt = $this->t3mfile_srv->get_dao()->seq_next_val();
			$filenum = sprintf("%07d",$tmp_cnt);

			//$this->so_svc->get_socc_dao()->update_seq($tmp_cnt);
			$obj_list = array();

			foreach($so_list as $obj)
			{
				$line = array();
				for($i = 1; $i <= 77;$i++)
				{
					 $line[$i] = "";
				}
				$so_obj = $this->so_svc->get_dao()->get(array("so_no"=>$obj->get_so_no()));
				if($so_obj->get_status() < 2 || $so_obj->get_status() == 6)
				{
					continue;
				}
				$soid_list = $this->so_svc->get_soid_dao()->get_list(array("so_no"=>$obj->get_so_no()));
				$client_obj = $this->client_svc->get_dao()->get(array("id"=>$so_obj->get_client_id()));
				$sops_obj = $this->so_svc->get_sops_dao()->get(array("so_no"=>$obj->get_so_no()));


				if($sops_obj)
				{
					//header
					switch($sops_obj->get_payment_gateway_id())
					{
						case "bibit":
						$trans_ref = "pgbibit-".$client_obj->get_id()."-".ereg_replace("^SO","",$so_obj->get_so_no());
						$salesChannel = 3;
						break;

						case "google":
						$trans_ref = "pggoogle-".$client_obj->get_id()."-".ereg_replace("^SO","",$so_obj->get_so_no());
						$salesChannel = 3;
						break;

						default:
						break;
					}
				}
				else
				{
					if($so_obj->get_biz_type() == "OFFLINE")
					{
						$trans_ref = "pgoffline-".$client_obj->get_id()."-".ereg_replace("^SO","",$so_obj->get_so_no());
						$salesChannel = 2;
					}
					else
					{
						continue;
					}
				}

				$cardHash = sha1("1404568".trim($this->encrypt->decode($obj->get_card_no()))."63885");

				$line[1] = $trans_ref;//salesChannel
				$line[2] = $so_obj->get_order_create_date();
				$line[4] = $client_obj->get_id()."-".ereg_replace("^SO","",$so_obj->get_so_no());
				$line[5] = $client_obj->get_id()."-".ereg_replace("^SO","",$so_obj->get_so_no());
				$line[6] = $salesChannel;

				//card information
				$line[9] = $obj->get_card_holder();
				$line[11] = $obj->get_card_bin();
				$line[12] = $obj->get_card_last4();
				$line[13] = $cardHash;
				if($obj->get_card_start_month() != "" && $obj->get_card_start_year() != "")
				{
					$line[14] = sprintf("%02d",$obj->get_card_start_month()).substr($obj->get_card_start_year(),-2);
				}

				$line[15] = sprintf("%02d",$obj->get_card_exp_month()).substr($obj->get_card_exp_year(),-2);
				$line[31] = $obj->get_create_at();

				$line[16] = $so_obj->get_amount();
				$line[17] = 'GBP'; //$so_obj->get_currency_id();
				if($so_obj->get_status() != 2)
				{
					$line[18] = "Rejected";
					$line[19] = "Unknown";
				}
				else
				{
					$line[18] = "Authorised";
					$line[19] = "Unknown";
				}

				$line[22] = 2;
				$line[23] = 2;
				$line[24] = 2;
				$line[27] = $client_obj->get_tel_1()." ".$client_obj->get_tel_2()." ".$client_obj->get_tel_3(); //str_replace('|',' ',mysql_result($getClientInfo,0,'tel'));
				$line[29] = $client_obj->get_mobile();
				$line[30] = $client_obj->get_email();
				//so_information_detail
				$history = $this->so_svc->get_dao()->get_client_stat($client_obj->get_id());
				if($history != FALSE)
				{
					$line[33] = $history["times"];
					$line[34] = $history["total"];
					$line[35] = $history["first"];
				}
				//client_info
				list($line[37],$line[38],$line[39]) = explode("|",$so_obj->get_bill_address());
				$line[41] = $so_obj->get_bill_country_id();
				$line[42] = $so_obj->get_bill_postcode();
				$bnarr = explode(" ",$so_obj->get_bill_name());
				$line[45] = $bnarr[count($bnarr) - 1];
				unset($bnarr[count($bnarr) - 1]);
				$line[44] = ereg_replace("^".$client_obj->get_title()." ","",implode(" ",$bnarr));
				list($line[46],$line[47],$line[48]) = explode("|",$so_obj->get_delivery_address());
				$line[50] = $so_obj->get_delivery_country_id();
				$line[51] = $so_obj->get_delivery_postcode();
				$line[52] = "Standard (2-5 days)";
				$line[54] = 0 ;

				$pos = 70;
				$iterate = 0;
				//product_info
				$line[70] = count($soid_list);
				foreach($soid_list as $item)
				{
					$infoall = $this->prod_svc->get_dao()->get_t3m_product_info($item->get_item_sku());
					if($info !== FALSE)
					{


						$risk = "low";
						/*if(in_array($info->get_cat_id(),array("4")) || in_array($info->get_sub_cat_id(),array("21","59")) )
						{
							$risk = "high";
						}*/
						$info = $infoall[0];

						$line[$pos + $iterate*7+1] = $item->get_item_sku();
						$line[$pos + $iterate*7+2] = $item->get_qty();
						$line[$pos + $iterate*7+3] = $item->get_unit_price();
						$line[$pos + $iterate*7+4] = str_replace("|"," ",$info->get_cat_name());
						$line[$pos + $iterate*7+5] = str_replace("|"," ",$info->get_sub_cat_name());
						$line[$pos + $iterate*7+6] = str_replace("|"," ",$info->get_name());;
						$line[$pos + $iterate*7+7] = $risk;

						$iterate++;
					}
					else
					{
						$title = "T3M Cron job notice";
						$reason = "Order that requires T3M checking is not found on ".date("Y-m-d H:iIs");
						$this->send_email($title, $reason);
					}
				}

				$line[$pos + $iterate*7+1] = 'Delivery';
				$line[$pos + $iterate*7+2] = 1;
				$line[$pos + $iterate*7+3] = $so_obj->get_delivery_charge();
				$line[$pos + $iterate*7+4] = 'Non-item Entry';
				$line[$pos + $iterate*7+5] = 'Delivery Charge';
				$line[$pos + $iterate*7+6] = 'Standard Delivery';
				$line[$pos + $iterate*7+7] = 'low';

				$result[] = implode("|",$line);

				$tmpobj = clone $obj;
				unset($line);

				$tmpobj->set_t3m_in_file($filenum) ;
				$obj_list[] = $tmpobj;
			}


			if(count($obj_list))
			{
				//create file for upload
				$path = "/var/data/valuebasket.com/t3m/upload/";

				$file = "T3MFILE".$filenum.".TXT";

				if( $fp = fopen($path.$file, "w"))
				{
					@fwrite($fp, implode("\r\n",$result));
					@fclose($fp);

					$t3mf_obj = $this->t3mfile_srv->get_dao()->get();
					$t3mf_obj->set_file_num($filenum);
					$t3mf_obj->set_uploaded('N');



					//$this->t3mfile_srv->get_dao()->trans_start();
					$ret = $this->t3mfile_srv->get_dao()->insert($t3mf_obj);
					//$this->t3mfile_srv->get_dao()->update_seq($tmp_cnt);
					foreach($obj_list as $obj)
					{
						//echo $obj->get_so_no()."<br>";
						$result = $this->so_svc->get_socc_dao()->update($obj);
						//echo $result." ".$this->so_svc->get_socc_dao()->db->last_query()." ".$this->so_svc->get_socc_dao()->db->_error_message()."<br>";
					}
					//$this->t3mfile_srv->get_dao()->trans_complete();

					return 1;
				}
				else
				{
					$title = "T3M Cron job notice";
					$reason = "Order that requires T3M checking is not found on ".date("Y-m-d H:iIs");
					$this->send_email($title, $reason);
					return 0;
				}
			}
		}
		else
		{
			$title = "T3M Cron job notice";
			$reason = "Order that requires T3M checking is not found on ".date("Y-m-d H:iIs");
			$this->send_email($title, $reason);
			return 0;
		}
	}

	public function updaterecord($filename = "")
	{
		if($filename == "" )
		{

			exit;
		}
		else
		{
			if(strstr($filename,"T3MFILE") == "")
			{
				exit;
			}

			$filenum = substr($filename,7,7);


			$list = $this->so_svc->get_socc_dao()->get_list(array("t3m_in_file"=>$filenum));
			$t3mf_obj = $this->t3mfile_srv->get_dao()->get(array("file_num"=>$filenum));

			if(!$list || !$t3mf_obj)
			{
				$this->send_email($title, $reason);
				return 0;
			}

			$t3mf_obj->set_uploaded('Y');

			$this->t3mfile_srv->get_dao()->update($t3mf_obj);
			$this->t3mfile_srv->get_dao()->update_seq($this->t3mfile_srv->get_dao()->seq_next_val());
			foreach($list as $obj)
			{
				$obj->set_t3m_is_sent('Y');
				$result = $this->so_svc->get_socc_dao()->update($obj);
				//var_dump($obj->get_so_no()." ".$result." ".$this->so_svc->get_socc_dao()->db->_error_message());
			}

			return 1;
		}
	}


	public function process_response($filename="")
	{
		$path = "/var/data/valuebasket.com/t3m/download/";
		$func = "t3m_resp";
		if($filename == "")
		{
			$this->send_email($title, $reason);
		}
		else
		{
			//echo $path.$filename;
			if($fp = fopen($path.$filename,'r'))
			{

				$tlog_vo = $this->tlog_dao->get();

				$skip = 0;
				$rules = array();
				$rules[0] = array("not_empty");
				$rules[1] = array("not_empty");
				$rules[2] = array("not_empty");
				$rules[3] = array("not_empty");
				$this->valid_svc->set_rules($rules);

				while(($line = fgetcsv($fp,5000,",")) && !$skip)
				{
					$this->valid_svc->set_data($line);

					try
					{
						$rs = $this->valid_svc->run();
					}

					catch(Exception $e)
					{
						$obj = clone $tlog_vo;
						$obj->set_message($e->getMessage());
						$this->tlog_dao->insert($tlog_obj);
						$skip = 1;
					}
				}

				@fclose($fp);

				if(!$skip)
				{
					$batch_obj = $this->batch_svc->get_dao()->get();
					$batch_obj->set_remark($filename);
					$batch_obj->set_func_name($func);
					$batch_obj->set_status("N");
					$batch_obj->set_listed(1);
					$this->batch_svc->get_dao()->insert($batch_obj);
				}
			}
			else
			{
				$title = "T3M Cron job notice";
				$reason = "Cannot open file for updating database";
				$this->send_email($title,$reason);
			}
		}
		$this->t3m_result();
	}

	function t3m_result()
	{
		$path = "/var/data/valuebasket.com/t3m/download/";
		$func = "t3m_resp";
		$it3m_obj = $this->int_t3m_svc->get_dao()->get();
		$batch_list = $this->batch_svc->get_dao()->get_list(array("func_name"=>$func,"status"=>'N'));
		foreach($batch_list as $bobj)
		{
			$obj_csv = new Csv_to_xml($path.$bobj->get_remark(),APPPATH.'data/t3m_response_csv2xml.txt',FALSE,',',TRUE);
			$obj_xml = new Xml_to_xml();

			$out  = $this->dex_svc->convert($obj_csv,$obj_xml);
			$xml = simplexml_load_string($out, 'SimpleXMLElement', LIBXML_NOCDATA);

			foreach($xml as $key=>$xobj)
			{

				$tmp = clone $it3m_obj;
				$oid = (string)$xobj->order_number;
				list($clientid, $ono) = explode("-",$oid);
				$action = (string)$xobj->action;
				$score = (string)$xobj->score;
				$tmp->set_batch_status('N');
				$tmp->set_batch_id($bobj->get_id());
				$tmp->set_client_id($clientid);
				$tmp->set_so_no("SO".$ono);
				$tmp->set_t3m_score($action."|".$score);
				$ret = $this->int_t3m_svc->get_dao()->insert($tmp);
				if($ret === FALSE)
				{
					var_dump($this->int_t3m_svc->get_dao()->db->last_query()." ".$this->int_t3m_svc->get_dao()->db->_error_message());
					$berr++;
					break;
				}
			}

			if($berr)
			{
				$bobj->set_status("BE");
				$this->batch_svc->get_dao()->update($bobj);
				continue;
			}

			$so_list = $this->so_svc->get_socc_dao()->get_cc_list();
			$bobj->set_status("P");
			$this->batch_svc->get_dao()->update($bobj);

			$this->valid_svc->set_exists_in(array("so_no"=>$so_list));

			$it3m_list = $this->int_t3m_svc->get_dao()->get_list(array("batch_id"=>$bobj->get_id()));

			$rules = array();
			$rules["so_no"] = array("not_empty","exists_in=so_no");
			$rules["t3m_score"] = array("not_empty");
			$rules["client_id"] = array("not_empty");

			$success = 1;
			foreach($it3m_list as $it3m_obj)
			{

				$this->valid_svc->set_rules($rules);

				$this->valid_svc->set_data($it3m_obj);

				$rs = FALSE;
				try
				{
					$rs = $this->valid_svc->run();
				}
				catch(Exception $e)
				{
					$it3m_obj->set_failed_reason($e->getMessage());
				}

				if($rs)
				{
					$it3m_obj->set_batch_status("R");
					unset($e);
				}
				else
				{
					$it3m_obj->set_batch_status("F");
					$success = 0;
				}
				unset($e);
				$this->int_t3m_svc->get_dao()->update($it3m_obj);
			}

			$this->int_t3m_svc->update_t3m_score($bobj->get_id());

			if(!$success)
			{
				$bobj->set_status("CE");
				$bobj->set_end_time(date("Y-m-d H:i:s"));
				$this->batch_svc->get_dao()->update($bobj);
			}
		}
	}

	private function send_email($title="",$reason="")
	{
		include_once APPPATH."libraries/dto/event_email_dto.php";
		$tmp = new Event_email_dto();

		$tmp->set_event_id("notification");
		$tmp->set_mail_to(array("itsupport@eservicesgroup.net"));
		$tmp->set_mail_from("do_not_reply@valuebasket.com");
		$tmp->set_tpl_id("general_alert");
		$tmp->set_replace(array("title"=>$title,"message"=>$message));
		$this->event_srv->fire_event($tmp);
	}

}


?>