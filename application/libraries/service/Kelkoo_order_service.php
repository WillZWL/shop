<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";
class Kelkoo_order_service extends Base_service
{
	protected $id = "Kelkoo Product Feed";
	private $so_dao;
	private $config_srv;
	private $now_time;
	private $title;
	private $filename;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH . 'libraries/dao/So_dao.php');
		$this->set_so_dao(New So_dao());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config_srv(new Context_config_service());
	}

	/* root sbf #3954 */
	public function gen_data($country_id = "all", $day_diff=0)
	{
		$csv = "";
		$this->now_time = time();
		// define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

		$data = $this->get_data($country_id, $day_diff);
		if($data)
		{
			$csv = $this->gen_csv($data);
			$message = "Attached file for Kelkoo $country_id orders generated at ". date("Y-m-d H:i:s", $this->now_time);
		}
		else
		{
			$message = "No data found for Kelkoo $country_id orders generated at ". date("Y-m-d H:i:s", $this->now_time);
		}

		$country_id = strtoupper($country_id);

		$this->title = "[$country_id] Kelkoo Orders Report" ;
		$this->filename = 'kelkoo_orders_' . "$country_id" . '_' . date('Ymdhis', $this->now_time) . '.csv';

		if(strpos($_SERVER['HTTP_HOST'], 'dev') === false)
			$this->email_report($country_id, $csv, $message);

		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header("Content-Disposition: attachment; filename=\"$this->filename\"");
		echo $csv;
	}

	private function email_report($country_id="", $csv="", $message="")
	{
		include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
		$phpmail = new PHPMailer();
		$phpmail->IsSMTP();
		$phpmail->From = "Admin <admin@valuebasket.net>";

		// $phpmail->AddAddress("itsupport@eservicesgroup.net");
		$phpmail->AddAddress("frteam@eservicesgroup.net");
		$phpmail->AddAddress("gonzalo@eservicesgroup.com");
		$phpmail->AddAddress("davide@eservicesgroup.com");
		$phpmail->AddAddress("edward@eservicesgroup.com");
		$phpmail->AddAddress("rod@eservicesgroup.com");

		$phpmail->Subject = $this->title;
		$phpmail->IsHTML(false);
		$phpmail->Body = $message;
		$phpmail->AddStringAttachment($csv, $this->filename, 'base64', 'text/csv');

		// $phpmail->SMTPDebug  = 1;
		$result = $phpmail->Send();
	}

	private function gen_csv($data=array())
	{
		$csv = "";
		if(!empty($data))
		{
			foreach ($data as $key => $value)
			{
				if($key==0)
				{
					# form the header from the keys of array so don't have to manually add on
					# name your keys at the query stage in get_paid_affiliate_orders() in so_dao.php
					foreach ($value as $label => $v)
					{
						$csv .= "$label,";
					}

					$csv .= "\n";
				}

				$csv .= implode(",", $value);
				$csv .= "\n";
			}
		}
		return $csv;
	}

	private function process_data_row($arr = array())
	{
		$new_arr = array();
		if(!empty($arr))
		{
			$so_status 		= array(
									0=>"inactive",
									1=>"new",
									2=>"paid",
									3=>"credit_checked",
									4=>"partial_allocated",
									5=>"full_allocated",
									6=>"shipped"
								);
			$refund_status = array(
									0=>"no",
									1=>"requested",
									2=>"logistic_approved",
									3=>"cs_approved",
									4=>"refunded"
								);
			$allocate_status = array(
									1=>"allocated",
									2=>"packed",
									3=>"shipped"
								);

			# replace all status numeric values with names for user
			foreach ($arr as $key => $value)
			{
				if($value["so_status"] !== NULL)
				{
					$value["so_status"] = $so_status[$value["so_status"]]; 	# e.g. $so_status[2] = "paid"
				}

				if($value["refund_status"] !== NULL)
				{
					$value["refund_status"] = $refund_status[$value["refund_status"]];
				}

				if($value["allocate_status"] !== NULL)
				{
					$value["allocate_status"] = $allocate_status[$value["allocate_status"]];
				}

				$new_arr[] = $value;
			}
		}

		return $new_arr;
	}

	private function get_data($country_id = "all", $day_diff=0)
	{
		$data = $arr = array();
		if(strtolower($country_id) == "all")
			$country_id = "";

		$end_dt = date('Y-m-d H:i:s', $this->now_time);		# time when cron job runs
		if($day_diff == 0 )
		{
			if(strpos($_SERVER['HTTP_HOST'], 'dev') === false)
				$start_dt = date('Y-m-d', (strtotime($end_dt) - 24*60*60))." 00:00:00"; 	# go back 1 day
			else
				$start_dt = date('Y-m-d', (strtotime($end_dt) - 100*24*60*60))." 00:00:00"; 	# for dev if insufficient data
		}
		else
		{
			# how many days to go back
			$start_dt = date('Y-m-d', (strtotime($end_dt) - $day_diff*24*60*60))." 00:00:00"; 	# go back 1 day
		}

		if($country_id)
		{
			$where["soex.conv_site_id"] = $this->get_affiliate_id_prefix() . $country_id;
		}
		else
		{
			// AND soex.conv_site_id LIKE 'KO%'
			$where["soex.conv_site_id LIKE"] = $this->get_affiliate_id_prefix() . "%";
		}

		if($arr = $this->get_so_dao()->get_paid_affiliate_orders($where, $start_dt, $end_dt))
		{
			$data = $this->process_data_row($arr);
		}

		return $data;

	}

	protected function get_affiliate_id_prefix()
	{
		return "KO";
	}

	public function get_so_dao()
	{
		return $this->so_dao;
	}

	public function set_so_dao(Base_dao $dao)
	{
		$this->so_dao = $dao;
	}

	public function get_config_srv()
	{
		return $this->config_srv;
	}

	public function set_config_srv(Base_service $srv)
	{
		$this->config_srv = $srv;
	}
}

/* End of file kelkoo_order_service.php */
/* Location: ./system/application/libraries/service/Kelkoo_order_service.php */