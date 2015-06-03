<?php
DEFINE ("MINOR_DELAY_DAY", 7);
DEFINE ("MAJOR_DELAY_DAY", 14);
DEFINE ("INITIAL_STATUS", 0);
DEFINE ("MINOR_DELAY_SENT_STATUS", 1);
DEFINE ("MAJOR_DELAY_SENT_STATUS", 2);

class Cron_delayed_order_email extends MY_Controller
{
	private $app_id = 'CRN0017';

	function __construct()
	{
		parent::__construct();
		$this->load->library('service/delayed_order_service');
		$this->load->library('service/so_service');

		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/event_service');
		$this->load->library('service/context_config_service');
		$this->load->library('service/region_service');
		$this->load->library('service/client_service');
		$this->load->library('service/event_service');
		include_once(APPPATH."hooks/country_selection.php");
	}

	public function get_all_minor_delay_order()
	{				//var_dump($this->db->last_query());
		$where = $option = array();
		$option["limit"] = -1;
		$where["so.hold_status"] = 0;
		$where["so.status >=3"] = null;
		$where["so.platform_id in ('WEBAU', 'WEBHK')"] = NULL;
		$where["so.refund_status"] = 0;
		$where["so.create_on >'2013-09-18'"] = NULL;
		$where["so.dispatch_date is null"] = null;
		$where["sops.payment_status"] = 'S';
		$where["sops.pay_to_account in ('paypal.value@valuebasket.com', 'paypal.au@valuebasket.com')"] = NULL;
		$where ['DATEDIFF(NOW(), so.order_create_date) > ' . MINOR_DELAY_DAY] = null;
		if($minor_delay_order_list = $this->delayed_order_service->get_all_minor_delay_order($where, $option))
		{
			foreach((array)$minor_delay_order_list as $obj)
			{
				$where_2 = $optoin_2 = array();
				$where_2["sohr.so_no"] = $obj->so_no;
				$where_2["sohr.reason"] =  "oos";
				if(!$this->delayed_order_service->has_oos_status($where_2, $option_2))
				{
					$delayed_order = $this->delayed_order_service->get();
					$delayed_order->set_so_no($obj->so_no);
					$delayed_order->set_status(INITIAL_STATUS);
					$this->delayed_order_service->insert($delayed_order);
				}
			}
		}
	}


	public function send_delayed_order_email()
	{
		$this->so_service->include_dto("Event_email_dto");
		//send minor email first
		$event_id = "minor_order_delay_email";
		$template_id  = "minor_order_delay_email";
		$where = $option = array();
		$where['deor.status'] = 0;
		$where["so.status >=3"] = null;
		$where["so.hold_status"] = 0;
		$where["so.refund_status"] = 0;
		$optoin['limit'] = -1;

		if($minor_delay_order = $this->delayed_order_service->get_delay_order($where, $option))
		{
			foreach($minor_delay_order as $obj)
			{
				$so_no = $obj->so_no;
				$platform_id = $obj->platform_id;
				$country_id =  $obj->country_id;
				$client_id = $obj->client_id;
				$lang_id = $obj->lang_id;
				$this->prepare_email_and_sent($event_id, $template_id, $so_no, $lang_id, $country_id, $client_id);
				$update_obj = $this->delayed_order_service->get(array("so_no" => $so_no));
				$update_obj->set_status(MINOR_DELAY_SENT_STATUS);
				$this->delayed_order_service->update($update_obj);
			}
		}

		//send major email
		$event_id = "major_order_delay_email";
		$template_id  = "major_order_delay_email";
		$where_2 = $option_2 = array();
		$where_2['deor.status'] = 1;
		$where_2["so.status >=3"] = null;
		$where_2["so.hold_status"] = 0;
		$where_2["so.refund_status"] = 0;
		//to prevent same user from receiving to minor and major both email in short interval of time
		$where_2['DATEDIFF(NOW(), deor.modify_on) > 7'] = null;
		$optoin_2['limit'] = -1;

		if($major_delay_order = $this->delayed_order_service->get_delay_order($where_2, $optoin_2))
		{
			foreach($major_delay_order as $obj)
			{
				$so_no = $obj->so_no;
				$platform_id = $obj->platform_id;
				$country_id =  $obj->country_id;
				$client_id = $obj->client_id;
				$lang_id = $obj->lang_id;
				$this->prepare_email_and_sent($event_id, $template_id, $so_no, $lang_id, $country_id, $client_id);
				$update_obj = $this->delayed_order_service->get(array("so_no" => $so_no));
				$update_obj->set_status(MAJOR_DELAY_SENT_STATUS);
				$this->delayed_order_service->update($update_obj);
			}
		}
	}



	function prepare_email_and_sent($event_id = "", $template_id = "", $so_no = "", $lang_id = "en", $country_id = "HK", $client_id = "", $extra_info = array() )
	{
		$email_dto = new Event_email_dto();
		$email_dto->set_event_id($event_id);
		$email_dto->set_tpl_id($template_id);
		$email_dto->set_lang_id($lang_id);
		$so_obj = $this->so_service->get(array("so_no" => $so_no));
		$client = $this->client_service->get(array("id" => $client_id));
		$replace = array();
		$replace["forename"] = $client->get_forename();
		$replace["order_no"] = $so_no;

		$replace["site_url"] = Country_selection::rewrite_domain_by_country("www.valuebaset.com", $country_id);
		$replace["site_name"] = Country_selection::rewrite_site_name($replace["site_url"]);


		$replace["image_url"] = $this->context_config_service->value_of("default_url");
		$replace["logo_file_name"] = $this->context_config_service->value_of("logo_file_name");

		if (file_exists(APPPATH . "language/template_service/" . $lang_id . "/".$template_id."/.ini"))
		{
			$data_arr = parse_ini_file(APPPATH . "language/template_service/" . $lang_id . "/".$template_id."/.ini");
		}


		if (!is_null($data_arr))
		{
			$replace = array_merge($replace, $data_arr);
		}


		if(isset($extra_info))
		{
			$replace = array_merge($replace, $extra_info);
		}

		$email_dto->set_replace($replace);
		$email_dto->set_mail_from("jenny.leung@valuebasket.com");
		$email_dto->set_mail_to($client->get_email());
		$this->event_service->fire_event($email_dto);
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}
}

/* End of file Cron_delayed_order_email.php */
/* Location: ./app/controllers/cron_delayed_order_email.php */
?>