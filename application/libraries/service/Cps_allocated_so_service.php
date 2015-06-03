<?php

include_once "Base_service.php";

class Cps_allocated_so_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Cps_allocated_so_dao.php");
		$this->set_dao(new Cps_allocated_so_dao());
		include_once(APPPATH."libraries/service/Data_exchange_service.php");
		$this->set_dex(new Data_exchange_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config(new Context_config_service());
	}

	public function get_dex()
	{
		return $this->dex;
	}

	public function set_dex($value)
	{
		$this->dex = $value;
	}

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

	public function cps_allocated_so_no()
	{
		$i = 0;
		if($this->inactive_all_record())
		{
			$url = 'http://cps.eservicesgroup.net/xml.autoallocate.php?id=6&name=VB';
			$use_curl = true;
			if ($use_curl)
			{
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				$result = curl_exec($ch);
				curl_close($ch);
			}
			else
				$result = file_get_contents($url);
			/*
			$filename = "cps_allocated_list_".$now_time.".xml";
			if($fp = @fopen($this->get_config()->value_of("cps_allocated_list_path").$filename,'w'))
			{
				@fwrite($fp,$result);
				@fclose($fp);
			}
			*/
			if($result)
			{
				$xml = simplexml_load_string($result);
				if($xml)
				{
					$now_time = date("YmdHis",strtotime((string)$xml->utccacheddate));
					$now_date = date("Ymd", strtotime((string)$xml->utccacheddate));

					if($exist_obj = $this->get_dao()->get_list(array("date"=>$now_date), array("limit"=>"-1")))
					{
						foreach($exist_obj AS $old_obj)
						{
							$this->get_dao()->delete($old_obj);
						}
					}
					$vo = $this->get_dao()->get();
					foreach($xml->order AS $order)
					{

						if($obj = $this->get_dao()->get(array("so_no"=>$order->retailer_order_reference, "date"=>$now_date)))
						{
							$action = "update";
							$obj->set_score($order->score);
							$obj->set_status(1);
						}
						else
						{
							$action = "insert";
							$obj = clone $vo;
							$obj->set_date($now_date);
							$obj->set_so_no($order->retailer_order_reference);
							$obj->set_score($order->score);
							$obj->set_status(1);
						}
						if(!$this->get_dao()->$action($obj))
						{
							mail('itsupport@eservicesgroup.net', "[VB]"."CPS allocation list insert error", $obj->get_so_no()."\nDB error:".$this->get_dao()->db->_error_message());
						}
						$i++;
					}
					$timestamp = date("Y-m-d H:i:s");
					if(date("H") < 1 || date("H") >= 23 || date("H") == "00")
					{
						#sbf #4613 - send email to fulfillment team for second allocation
						mail('ken@eservicesgroup.com, kenneth@eservicesgroup.com, mike.lau@eservicesgroup.com', "[VB] CPS allocation done", "Generated @ $timestamp. {$i} records update.");
						mail('itsupport@eservicesgroup.net,gonzalo@eservicesgroup.com', "[VB] CPS allocation done", "Generated @ $timestamp. {$i} records update.");
					}
				}
				else
				{
					mail('itsupport@eservicesgroup.net', "[VB]"."CPS Allocation Plan - Error in format", $this->get_config()->value_of("cps_allocated_list_path").$filename);
				}
			}
			else
			{
				mail('itsupport@eservicesgroup.net', "[VB]"."Can't retrieve CPS Allocation Plan", 'http://cps.eservicesgroup.net/xml.autoallocate.php?id=6&name=VB');
			}
		}
		echo "{$i} record update.";
	}

	public function inactive_all_record()
	{
		$list = $this->get_dao()->get_list(array("status"=>1), array("limit"=>"-1"));
		foreach($list AS $obj)
		{
			$obj->set_status(0);
			if(!$this->get_dao()->update($obj))
			{
				mail('itsupport@eservicesgroup.net', "[VB]"."Inactive CPS allocation list error", $obj->get_so_no());
				return FALSE;
			}
		}
		return TRUE;
	}
}

/* End of file cps_allocated_so_service.php */
/* Location: ./app/libraries/service/Cps_allocated_so_service.php */