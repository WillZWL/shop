<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Feedback_email_service extends Base_service
{
	private $so_srv;

	function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_srv(new So_service());
		include_once(APPPATH."libraries/service/Country_local_warehouse_service.php");
		$this->set_clw_srv(new Country_local_warehouse_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config(new Context_config_service());
		include_once(APPPATH."libraries/service/Event_service.php");
		$this->set_event(new Event_service());
		include_once(APPPATH."libraries/service/Ebay_service.php");
		$this->set_ebay_srv(new Ebay_service());
		include_once(APPPATH."libraries/service/Customer_service_info_service.php");
		$this->set_cs_info_srv(new Customer_service_info_service());
	}

	public function fire_feedback_email(Base_dto $dto)
	{
		include_once APPPATH."libraries/dto/event_email_dto.php";
		$this->include_dto("Event_email_dto");
		$email_dto = new Event_email_dto();

		$replace["image_url"] = $this->get_config()->value_of("default_url");
		$replace["logo_file_name"] = $this->get_config()->value_of("logo_file_name");
		$replace["so_no"] = $dto->get_so_no();
		$replace["forename"] = $dto->get_forename();
		$country_id = $dto->get_delivery_country_id();

		$review_agent = $this->get_review_agent_info($dto);
		$agent_id = $review_agent["agent_id"];
		$lang_id = $review_agent["lang_id"];
		$cs_contact_number = $this->_get_cs_contact_number($dto->get_delivery_country_id());

		$email_sender = "no-reply@valuebasket.com";

		if(trim($cs_contact_number) != "")
		{
			switch($lang_id)
			{
				case "fr":
					$replace["local_phone_number"] = " ou nous téléphoner sur " . $cs_contact_number;
					break;
				case "en":
				default:
					$replace["local_phone_number"] = " or give us a call on " . $cs_contact_number;
			}
		}
		else
		{
			$replace["local_phone_number"] = "";
		}

		switch($agent_id)
		{
			case "trustpilot":
				$email_sender = $replace["sender_email"] = "angel.liu@valuebasket.com";
			case "trustpilot_fr":
				$template_id = "trustpilot_customer_review";
				break;
			case "resellerratings":
				$template_id = "resellerratings_customer_review";
				break;
			case "reviewcentre":
				//if ($dto->get_delivery_country_id() == "GB")
				//{
				//	$email_sender = $replace["sender_email"] = "jenny.leung@valuebasket.com";
				//	$template_id = "reviewcentre_customer_review_gb";
				//}
				//else
				//{
					$template_id = "reviewcentre_customer_review";
				//}
				break;
			case "getprice":
				$template_id = "getprice_customer_review";
				break;
			case "productreview":
				$template_id = "productreview_customer_review";
				break;
			case "ebay":
				$this->get_ebay_srv()->send_feedback_email($dto);
				return true;
			case "kelkoo_fr_be":
				$template_id = "kelkoo_customer_review";
				$replace["FR_BE_site_name"] = "ValueBasket.fr";
				if ($country_id == "FR")
				{
					$email_sender = $replace["sender_email"] = "no-reply@valuebasket.fr";
				}
				elseif($country_id == "BE")
				{
					$email_sender = $replace["sender_email"] = "no-reply@valuebasket.com";
				}
				break;
			default:
				// no review agent available
				return false;
		}
		include_once(APPPATH."hooks/country_selection.php");
		$country_id = $dto->get_delivery_country_id();
		$replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));

		$email_dto->set_event_id($template_id);
		$email_dto->set_mail_from($email_sender);
		$email_dto->set_mail_to($dto->get_email());
		$email_dto->set_tpl_id($template_id);
		$email_dto->set_lang_id($lang_id);
		$email_dto->set_replace($replace);
		$this->get_event()->fire_event($email_dto);
	}

	public function required_feedback_email(Base_dto $dto)
	{
		// eBay orders are send regardless of condition
		if($dto->get_biz_type() == 'EBAY')
		{
			return true;
		}

		// skip Hong Kong post orders (unless shipped to HK location)
		if($dto->get_delivery_country_id() == 'HK')
		{
			$courier_id = trim($dto->get_courier_id());
			if($courier_id != 'HK_POST')
			{
				return false;
			}
		}

		if(  $this->_is_valid_country($dto->get_delivery_country_id()) &&
			!$this->_is_order_held($dto->get_so_no()) &&
			($this->_is_fulfilled_by_local_fulfillment_centre($dto->get_delivery_country_id(), $dto->get_warehouse_id())
			    || $this->_is_fulfilled_by_allowed_courier($dto->get_delivery_country_id(), $dto->get_courier_id()))
		  )
		{
			return true;
		}

		return false;
	}

	private function _get_cs_contact_number($country_id)
	{
		if($cs_info = $this->get_cs_info_srv()->get(array("platform_id LIKE '%".$country_id."%'"=>null, "short_text_status"=>1)))
		{
			return $cs_info->get_short_text();
		}
		return false;
	}

	private function _is_valid_country($country_id)
	{
		$invalid_country_list = array('AR','BR','CL','CO','HR','EE','GE','IN','ID','IL','IT','LT','MX','MA','OM','PA','PE','PH','QA','RU','SA','RS','SK','SI','ZA','ES','CH','SY','TH','TN','TR','UA','AE','VE','VN');
		if(in_array($country_id, $invalid_country_list))
		{
			return false;
		}

		return true;
	}

	private function _is_fulfilled_by_local_fulfillment_centre($country_id, $warehouse_id)
	{
		if($num_rows = $this->get_clw_srv()->get_num_rows(array("country_id"=>$country_id, "warehouse_id"=>$warehouse_id)))
		{
			return true;
		}

		return false;
	}

	private function _is_fulfilled_by_allowed_courier($country_id, $courier_id)
	{
		// check whether the courier is allowed for email
		$courier_id = strtoupper(trim($courier_id));
		$allowed_courier_list = array("DHL", "DHLBBX", "TOLL", "ARAMEX", "CITYLINK", "UPS", "FEDEX", "SPECIAL DELIVERY");
		if(in_array($courier_id, $allowed_courier_list))
		{
			return true;
		}
		return false;
	}

	private function _is_order_held($so_no)
	{
		if($num_rows = $this->get_so_srv()->get_sohr_dao()->get_num_rows(array("so_no"=>$so_no, "reason IN ('change_of_address', 'cscc', 'csvv')"=>null)))
		{
			return true;
		}
		return false;
	}

	public function get_review_agent_info(Base_dto $dto)
	{
		if($dto)
		{
			switch($dto->get_biz_type())
			{
				case "EBAY":
					$agent_info = array("lang_id"=>"en", "agent_id"=>"ebay");
					break;
				default:
					$agent_info = $this->_get_review_agent_by_country($dto->get_delivery_country_id());
			}

			return $agent_info;
		}
	}

	private function _get_review_agent_by_country($country_id)
	{
		switch($country_id)
		{
			case "AR":
			case "AT":
			case "BG":
			case "CY":
			case "CZ":
			case "DK":
			case "FI":
			case "DE":
			case "GB":
			case "GR":
			case "HK":
			case "HU":
			case "IE":
			case "LV":
			case "NL":
			case "NO":
			case "PL":
			case "PT":
			case "RO":
			case "LU":
				return array("lang_id"=>"en", "agent_id"=>"reviewcentre");
				break;
			case "IT":
				return array("lang_id"=>"it", "agent_id"=>"reviewcentre");
				break;
			case "CA":
			case "SG":
			case "MY":
			case "TW":
			case "US":
				return array("lang_id"=>"en", "agent_id"=>"resellerratings");
				break;
			case "AU":
			case "NZ":
				// #4233
				/*$agent_list = array("productreview", "getprice");
				$rnd = rand(0,count($agent_list) - 1);
				$agent_id = $agent_list[$rnd];
				return array("lang_id"=>"en", "agent_id"=>$agent_id);
				*/
				return array("lang_id"=>"en", "agent_id"=>"productreview");
				break;
			case "BE":
			case "FR":
				return array("lang_id"=>"fr", "agent_id"=>"kelkoo_fr_be");
				break;
			default:
		}
	}

	public function get_automated_feedback_email_content($where = array(), $option = array())
	{
		return $this->get_so_srv()->get_automated_feedback_email_content($where, $option);
	}

	public function get_so_srv()
	{
		return $this->so_srv;
	}

	public function set_so_srv(Base_service $srv)
	{
		$this->so_srv = $srv;
	}

	public function get_clw_srv()
	{
		return $this->clw_srv;
	}

	public function set_clw_srv(Base_service $srv)
	{
		$this->clw_srv = $srv;
	}

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

	public function get_event()
	{
		return $this->event;
	}

	public function set_event($value)
	{
		$this->event = $value;
	}

	public function get_ebay_srv()
	{
		return $this->ebay_srv;
	}

	public function set_ebay_srv($value)
	{
		$this->ebay_srv = $value;
	}

	public function get_cs_info_srv()
	{
		return $this->cs_info_srv;
	}

	public function set_cs_info_srv($value)
	{
		$this->cs_info_srv = $value;
	}

	public function get_rma_customer_email_address($past_day)
	{
		// SBF#1895 DAO will perform sanitization
		return $this->get_so_srv()->get_rma_customer_email_address($past_day);
	}

}


/* End of file feedback_email_service.php */
/* Location: ./system/application/libraries/service/Feedback_email_service.php */
