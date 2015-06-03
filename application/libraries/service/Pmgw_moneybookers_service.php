<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_voucher.php";

class Pmgw_moneybookers_service extends Pmgw_voucher
{
// Testing account information:
// Email: tommy@eservicesgroup.net
// Password: SE888MBtest

	const MONEYBOOKERS_SERVER = "https://www.moneybookers.com/app/payment.pl";
	const RECIPIENT_DESC = "Valuebasket";
	const MQI_API_PASSWORD = "1f4b7b73039a8637895ab9f73141da20";
	private $pay_to_email = "";
	private $merchant_id = "";
	private $status2_email = "sqcpayments@valuebasket.com";
	private $payment_methods;
	private $sw_md5 = "";
	private $query_password = "";
	private $payment_status;
	private $diff_amount_email = "compliance-alerts@eservicesgroup.net, itsupport@eservicesgroup.net";
	private $_sitedown_email = "oswald-alert@eservicesgroup.com, jesslyn@eservicesgroup.com, compliance-alerts@eservicesgroup.net";
//	private $_sitedown_email = "oswald-alert@eservicesgroup.com";
	private $skip_cc_card = array("NPY", "SFT", "EBT", "SO2", "IDL");
	private $pmgw_id = "moneybookers";

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$CI->load->helper('url');
		$this->input=$CI->input;
	}

	public function init($vars)
	{
		$pbv_srv = $this->get_pbv_srv();
		$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$vars["platform_id"]));
		$vars["currency_id"] = $platform_obj->get_platform_currency_id();
		if ($vars["payment_methods"])
		{
			$this->payment_methods = $vars["payment_methods"];
		}
		$so_srv = $this->get_so_srv();
//Production LINE
		$so_obj = $so_srv->cart_to_so($vars);
//Testing LINE
//		$so_obj = $so_srv->get(array("so_no"=>"SO000001"));
//END
		if ($so_obj === FALSE)
		{
			return FALSE;
		}
		$this->so = $so_obj;
		$this->store_af_info();

//		$this->logheader["message"] = var_export($this->so, true);
//		$this->logger->write_log($this->logheader);

		$this->client = $this->get_client_srv()->get(array("id"=>$this->so->get_client_id()));

		if ($this->client === FALSE)
		{
			return FALSE;
		}

//		$this->logheader["message"] = var_export($this->client, true);
//		$this->logger->write_log($this->logheader);

	}

	protected function get_base_url()
	{
// double check the url with lang_id_countryID pair
// this is to prevent redirection
		$check_url = base_url();
		if ((substr($check_url, -6, 2) != get_lang_id())
			|| (strtoupper(substr($check_url, -3, 2)) != PLATFORMCOUNTRYID))
			$check_url = base_url() . get_lang_id() . "_" . PLATFORMCOUNTRYID . "/";

		return $check_url;
	}

	public function checkout($debug=0)
	{
		if ($debug && !$this->get_config()->value_of("payment_debug_allow"))
		{
			$debug = 0;
		}

		$this->switch_merchant($this->so->get_currency_id(), $debug);

		include_once(APPPATH."libraries/service/Country_service.php");
		$country_srv = new Country_service();
		$country_obj = $country_srv->get(array("id"=>$this->client->get_country_id()));

		$postal_code = $this->client->get_postcode();
		if (($this->client->get_country_id() == 'HK') || ($this->client->get_country_id() == 'IE'))
		{
			if ($this->client->get_postcode() == '')
			{
				$postal_code = 'NA';
			}
		}
		$base_url_lang_country_pair = $this->get_base_url();

		$data = array();
		$data["pay_to_email"] = $this->pay_to_email;
		$data["recipient_description"] = self::RECIPIENT_DESC;
		$data["transaction_id"] = $this->so->get_client_id(). "-" . $this->so->get_so_no();
		$data["return_url"] = str_replace('http://', 'https://', $base_url_lang_country_pair) . "checkout_onepage/response/moneybookers/" . $debug . "?so_no=" . $this->so->get_so_no();
		$data["return_url_text"] = "Proceed to Order Confirmation";
		$data["cancel_url"] = str_replace('http://', 'https://', $base_url_lang_country_pair) . "checkout_onepage" . (($debug) ? "?debug=1" : "");
		$data["status_url"] = str_replace('http://', 'https://', $base_url_lang_country_pair) . "checkout_onepage/response/moneybookers/" . $debug;
		$data["status_url2"] = "mailto: " . $this->status2_email;
		$data["hide_login"] = 1;
		$data["prepare_only"] = 1;
		$data["new_window_redirect"] = 1;
		$data["language"] = get_lang_id();
//		$data["logo_url"] = str_replace('http://', 'https://', base_url()) . "images/" . $this->get_config()->value_of("logo_file_name");
		$data["merchant_fields"] = "";
		$data["confirmation_note"] = "Please proceed to order confirmation to complete your order";
		$data["pay_from_email"] = $this->client->get_email();
		$data["title"] = $this->client->get_title();
		$data["firstname"] = $this->client->get_forename();
		$data["lastname"] = $this->client->get_surname();
		$data["address"] = $this->client->get_address_1();
		$data["address2"] = $this->client->get_address_2();
		$data["phone_number"] = trim($this->client->get_tel_1() . $this->client->get_tel_2() . $this->client->get_tel_3());
		$data["postal_code"] = $postal_code;
		$data["city"] = $this->client->get_city();
		$data["state"] = $this->client->get_state();
		$data["country"] = @call_user_func(array($country_obj, "get_id_3_digit"));
		$data["amount"] = $this->so->get_amount();
		$data["currency"] = $this->so->get_currency_id();
		$data["amount2_description"] = "Delivery Cost";
		$data["detail1_description"] = "Order Number:";
		$data["detail1_text"] = $this->so->get_client_id() . "-" . $this->so->get_so_no();
		$data["payment_methods"] = $this->payment_methods;
		$post_fields = @http_build_query($data);
		$this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", str_replace("&", "\n&", urldecode($post_fields)));
		$result = $this->submitForm($post_fields);
		$session = $result["result"];

		if (($result["error"] != "") || (strlen($session) > 100) || ($session == ""))
		{
			$error = $result["error"] . ",info:" . @http_build_query($result["info"]);
			$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", urldecode($error)));
			$down_message = "Session: " . $session . "Please contact MB, IT please consider to switch payment gateway." . "O:" . $post_fields . ", I:" . $error;
			mail($this->_sitedown_email, "MB payment issue", $down_message, 'From: website@valuebasket.com');
			print "ERROR::" . base_url() . "checkout_onepage/payment_result/0/{$this->so->get_so_no()}?type=sitedown";
		}
		else
		{
			$redirect_url = self::MONEYBOOKERS_SERVER . "?sid=" . $session;
			$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", $redirect_url);
			print $redirect_url;
		}
	}

	public function response($vars, $debug)
	{
		if ($debug && !$this->get_config()->value_of("payment_debug_allow"))
		{
			$debug = 0;
		}

		$so_srv = $this->get_so_srv();
		if ($vars)
		{
//			$this->logheader["message"] = var_export($vars, true);
//			$this->logger->write_log($this->logheader);

			$this->switch_merchant($vars["mb_currency"], $debug);

			include_once(APPPATH.'data/payment_result.php');

			if ($vars["md5sig"] == strtoupper(md5($this->merchant_id.$vars["transaction_id"].$this->sw_md5.$vars["mb_amount"].$vars["mb_currency"].$vars["status"])))
			{
				switch ($vars["status"])
				{
					case "-2":
						$this->error_type = 1;
						$this->payment_status = "F";
						$this->remark = "status:failed";
						if ($vars["failed_reason_code"])
						{
							$this->remark .= "\nfailed_reason_code:".$vars["failed_reason_code"];
							$this->remark .= "\nfailed_reason:".$this->get_reason($vars["failed_reason_code"]);
						}
						$this->display_message = $result["display"]["ps"]["ERROR"];
						break;
					case "-1":
						$this->error_type = 1;
						$this->payment_status = "C";
						$this->remark = "status:cancelled";
						$this->display_message = $result["display"]["ps"]["CANCELLED"];
						break;
					case "0":
						$this->error_type = 0;
						$this->payment_status = "P";
						$this->remark = "status:pending";
						$this->display_message = $result["display"]["ps"]["AUTHORISED"];
						break;
					case "2":
						$this->error_type = 0;
						$this->payment_status = "S";
						$this->remark = "status:processed";
						$this->display_message = $result["display"]["ps"]["AUTHORISED"];
						break;
					default:
						$this->error_type = 1;
						$this->payment_status = "F";
						$this->remark = "status:unknow";
						$this->display_message = $result["display"]["ps"]["ERROR"];
						break;
				}
			}
			else
			{
				$this->error_type = 1;
				$this->payment_status = "F";
				$this->remark = "md5sig mismatch";
				$this->display_message = $result["display"]["ps"]["ERROR"];
			}

			list($client_id, $so_no) = explode("-", $vars["transaction_id"]);
			if (!isset($vars["result_from_query"]))
				$this->get_sopl_srv()->add_log($so_no, "I", str_replace("&", "\n&", urldecode(@http_build_query($vars))));

			if ($this->so = $so_srv->get(array("so_no"=>$so_no)))
			{
				$this->so->set_txn_id($vars["mb_transaction_id"]);
				$this->get_so_srv()->update($this->so);

				if ($socc_obj = $so_srv->get_socc_dao()->get(array("so_no"=>$this->so->get_so_no())))
				{
					if ($vars["cc_bin"] || $vars["cc_last_4digits"])
					{
						$socc_obj->set_card_last4($vars["cc_bin"]);
						$socc_obj->set_card_bin($vars["cc_last_4digits"]);
						$so_srv->get_socc_dao()->update($socc_obj);
					}
				}
				else
				{
					//Add card info to so_credit_chk
					$socc_vo = $so_srv->get_socc_dao()->get();
					$socc_vo->set_so_no($this->so->get_so_no());
					$socc_vo->set_card_last4($vars["cc_bin"]);
					$socc_vo->set_card_bin($vars["cc_last_4digits"]);
					$so_srv->get_socc_dao()->insert($socc_vo);
				}

				// Update the card type. Because client can select other card type during the payment process, it is better to update our record again
				$sops_dao = $so_srv->get_sops_dao();
				$this->sops = $sops_dao->get(array("so_no"=>$this->so->get_so_no()));
				$this->sops->set_card_id($vars["payment_type"]);
				$sops_dao->update($this->sops);

				if (!((
						($failed_currency = $vars["currency"]) == $this->so->get_currency_id() &&
						($failed_amount = $vars["amount"]*1) == $this->so->get_amount()*1
					) && (
						($failed_currency = $vars["mb_currency"]) == $this->so->get_currency_id() &&
						($failed_amount = $vars["mb_amount"]*1) == $this->so->get_amount()*1
					)) && !$debug)
				{
					$this->error_type = 1;
					$this->payment_status = "F";
					$this->remark = "amount mismatch ({$failed_currency}{$failed_amount})";
					$this->display_message = $result["display"]["ps"]["ERROR"];
					$da_email_subject = "[VB] Different Amount Returned from MoneyBookers - ".$so_no;
					$da_email_message = "
										Order #: {$so_no}
										Order Amount: {$this->so->get_currency_id()}{$this->so->get_amount()}
										MB Returned Amount: {$failed_currency}{$failed_amount}
										";
					mail($this->diff_amount_email, $da_email_subject, $da_email_message);
				}
				$this->result();
				if (isset($vars["result_from_query"]))
				{
					$this->check_result($so_no);
				}
			}
			else
			{
				$_SESSION["pmgw_message"] = "Order Not Found";
				$this->redirect_fail();
			}
		}
		else
		{
			$so_no = $this->input->get("so_no");
			$transactionId =  $this->input->get("transaction_id");

			if ($so_no && $transactionId)
			{
				$this->queryOrderStatus($so_no, $transactionId, $debug);
			}
			else
				$this->check_result($so_no);

//meaningless to verify the msid
/*
			if ($msid = $this->input->get("msid"))
			{
				$this->get_sopl_srv()->add_log($so_no, "I", str_replace("&", "\n&", $_SERVER['QUERY_STRING']));
				$transaction_id = $this->input->get("transaction_id");

				$vars["so_no"] = $so_no;
				$vars["msid"] = $msid;
				$vars["transaction_id"] = $transaction_id;
				$this->switch_merchant($this->so->get_currency_id());
				$currency =
//				$this->logheader["message"] = var_export($vars, true);
//				$this->logger->write_log($this->logheader);
$email_content = $vars["so_no"] . ", msid:" . $msid . ", transaction_id:"  . $transaction_id . ", merchant_id:" . $this->merchant_id . ", sw_md5:" . $this->sw_md5;
$email_content .= ", md5:" . strtoupper(md5($this->merchant_id.$transaction_id.$this->sw_md5));
mail("oswald-alert@eservicesgroup.com", "MB status", $email_content);
				if ($msid == strtoupper(md5($this->merchant_id.$transaction_id.$this->sw_md5)))
				{
						$this->error_type = 0;
						$this->payment_status = "P";
						$this->remark = "status:pending";
						$this->display_message = $result["display"]["ps"]["AUTHORISED"];
				}
				else
				{
					$this->error_type = 1;
					$this->payment_status = "F";
					$this->remark = "msid mismatch";
					$this->display_message = $result["display"]["ps"]["ERROR"];
				}

				list($client_id, $so_no) = explode("-", $transaction_id);
				if ($this->so = $so_srv->get(array("so_no"=>$so_no)))
				{
					$this->result();
				}
				else
				{
					$_SESSION["pmgw_message"] = "Order Not Found";
					$this->redirect_fail();
				}

			}
			else
			{
				$this->check_result($so_no);
			}
*/
		}
	}

	public function queryOrderStatus($so_no, $transactionId, $debug)
	{
		$so_srv = $this->get_so_srv();

		if (!($this->so = $so_srv->get(array("so_no" => $so_no))))
		{
			$_SESSION["pmgw_message"] = "Order Not Found";
			$this->redirect_fail($so_no);
		}

		$this->switch_merchant($this->so->get_currency_id(), $debug);
		$result = $this->query_order_api($transactionId);
		if ($result)
		{
			$result["result_from_query"] = true;
			$this->response($result, $debug);
		}
	}

	private function query_order_api($transactionId)
	{
		$this->pmgw_url = "https://www.moneybookers.com/app/query.pl?action=status_trn&email=" . $this->pay_to_email . "&password=" . $this->query_password . "&trn_id=" . $transactionId;
		if ($result = $this->connect())
		{
			$rs = array();
			$ar_result = @explode("&", $result);
			foreach ($ar_result as $data)
			{
				$ar_data = @explode("=", $data);
				$rs[trim($ar_data[0])] = trim($ar_data[1]);
			}
			return $rs;
		}
	}

	public function result()
	{
		$is_fraud = false;
		$so_srv = $this->get_so_srv();
		$sops_dao = $so_srv->get_sops_dao();
		$this->sops = $sops_dao->get(array("so_no"=>$this->so->get_so_no()));

		$old_ps_status = $this->sops->get_payment_status();

		if ($this->payment_status == "S" && !$this->doing_pending && $this->require_park())
		{
			$this->sops->set_pay_date(date("Y-m-d H:i:s"));
			$this->sops->set_payment_status('P');
			$this->remark = $this->remark.", parking";
			$this->sops->set_remark(trim($this->sops->get_remark()."\n".$this->remark));
			$this->sops->set_pending_action('CC');
			$sops_dao->update($this->sops);

			if($is_fraud = $so_srv->is_fraud_order($this->so))
			{
				$so_srv->process_fraud_order($this->so);
			}
			// Comment out in order to not send acknowledgement email
			//$this->fire_success_event(1);
		}
		elseif ($old_ps_status != $this->payment_status)
		{
			$this->sops->set_pending_action(null);

			$this->sops->set_payment_status($this->payment_status);

			$this->sops->set_remark(trim($this->sops->get_remark()."\n".$this->remark));

			$this->sops->set_pay_to_account($this->pay_to_email);

			if ($this->payment_status == "S" && $this->sops->get_pay_date() == "")
			{
				$this->sops->set_pay_date(date("Y-m-d H:i:s"));
			}

			if ($this->payment_status == "S")
			{
				if($is_fraud = $so_srv->is_fraud_order($this->so))
				{
					$so_srv->process_fraud_order($this->so);
				}
				else
				{
					$pbv_srv = $this->get_pbv_srv();
//					$this->so->set_expect_delivery_date($this->get_del_srv()->get_edd($this->so->get_delivery_type_id(), $this->so->get_delivery_country_id()));
					$this->so->set_status($this->require_credit_check()?2:3);
					if ($this->require_decision_manager($is_fraud))
					{
						$sor_data = array("risk_requested" => 0);
						$this->sor_add($sor_data);
					}
					$so_srv->get_dao()->update($this->so);
				}
			}
			elseif ($this->payment_status == "C" || ($this->payment_status == "F" && $old_ps_status != "S"))
			{
				$this->so->set_status(0);
				$so_srv->get_dao()->update($this->so);


			}

			$sops_dao->update($this->sops);

			if ($this->payment_status == "S")
			{
				if(!$is_fraud)
				{
					$so_srv->update_website_display_qty($this->so);
				}
//				$so_srv->set_profit_info($this->so);
	/*
				// Tracking
				$origin_website = isset($_COOKIE['originw'])?$_COOKIE['originw']:($_COOKIE["LS_siteID"] != ''?13:null);
				$soext_vo = $so_srv->get_soext_dao()->get();
				$soext_vo->set_so_no($this->so->get_so_no());
				$soext_vo->set_conv_site_id($origin_website);
				$soext_vo->set_conv_status(0);

				if($_COOKIE["LS_siteID"] != '' && $_COOKIE["LS_siteID"] !='siteID')
				{
					$soext_vo->set_conv_site_ref($_COOKIE["LS_siteID"]);
					$soext_vo->set_ls_time_entered($_COOKIE["LS_timeEntered"]);
					// Insert ls_transaction
					$this->add_ls_transaction($soext_vo);
				}

				$so_srv->get_soext_dao()->insert($soext_vo);
	*/

				if ($promo_code = $this->so->get_promotion_code())
				{
					$this->update_promo($promo_code);
				}

				if ($this->note)
				{
					$this->add_note();
				}

				// Fire Event START
				$this->fire_success_event();
				// Fire Event END
			}
			elseif ($this->payment_status == "P")
			{
				// Fire Event START
				// Comment out in order to not send acknowledgement email
				//$this->fire_success_event(1);
				// Fire Event END
			}
		}
	}

	public function check_result($so_no)
	{
		$so_srv = $this->get_so_srv();

		if (!($this->so = $so_srv->get(array("so_no"=>$so_no))))
		{
			$_SESSION["pmgw_message"] = "Order Not Found";
			$this->redirect_fail($so_no);
		}

		$sops_dao = $so_srv->get_sops_dao();
		$this->sops = $sops_dao->get(array("so_no"=>$this->so->get_so_no()));

		if ($this->sops->get_payment_status() == "N" || $this->sops->get_payment_status() == "F" || $this->so->get_status() == 0)
		{
			$this->redirect_fail($so_no);
		}
		else
		{
			$this->unset_variable();
//			$_SESSION["pmgw_message"] = $this->display_message;
			$this->redirect_success();
		}
	}

	public function get_reason($code)
	{
		$reason = array(
					"01"=>"Referred",
					"02"=>"Invalid Merchant Number",
					"03"=>"Pick-up card",
					"04"=>"Authorisation Declined",
					"05"=>"Other Error",
					"06"=>"CVV is mandatory, but not set or invalid",
					"07"=>"Approved authorisation, honour with identification",
					"08"=>"Delayed Processing",
					"09"=>"Invalid Transaction",
					"10"=>"Invalid Currency",
					"11"=>"Invalid Amount/Available Limit Exceeded/Amount too high",
					"12"=>"Invalid credit card or bank account",
					"13"=>"Invalid Card Issuer",
					"14"=>"Annulation by client",
					"15"=>"Duplicate transaction",
					"16"=>"Acquirer Error",
					"17"=>"Reversal not processed, matching authorisation not found",
					"18"=>"File Transfer not available/unsuccessful",
					"19"=>"Reference number error",
					"20"=>"Access Denied",
					"21"=>"File Transfer failed",
					"22"=>"Format Error",
					"23"=>"Unknown Acquirer",
					"24"=>"Card expired",
					"25"=>"Fraud Suspicion",
					"26"=>"Security code expired",
					"27"=>"Requested function not available",
					"28"=>"Lost/Stolen card",
					"29"=>"Stolen card, Pick up",
					"30"=>"Duplicate Authorisation",
					"31"=>"Limit Exceeded",
					"32"=>"Invalid Security Code",
					"33"=>"Unknown or Invalid Card/Bank account",
					"34"=>"Illegal Transaction",
					"35"=>"Transaction Not Permitted",
					"36"=>"Card blocked in local blacklist",
					"37"=>"Restricted card/bank account",
					"38"=>"Security Rules Violation",
					"39"=>"The transaction amount of the referencing transaction is higher than the transaction amount of the original transaction",
					"40"=>"Transaction frequency limit exceeded, override is possible",
					"41"=>"Incorrect usage count in the Authorisation System exceeded",
					"42"=>"Card blocked",
					"43"=>"Rejected by Credit Card Issuer",
					"44"=>"Card Issuing Bank or Network is not available",
					"45"=>"The card type is not processed by the authorisation centre / Authorisation System has determined incorrect Routing",
					"47"=>"Processing temporarily not possible",
					"48"=>"Security Breach",
					"49"=>"Date / time not plausible, trace-no. not increasing",
					"50"=>"Error in PAC encryption detected",
					"51"=>"System Error",
					"52"=>"MB Denied - potential fraud",
					"53"=>"Mobile verification failed",
					"54"=>"Failed due to internal security restrictions",
					"55"=>"Communication or verification problem",
					"56"=>"3D verification failed",
					"57"=>"AVS check failed",
					"58"=>"Invalid bank code",
					"59"=>"Invalid account code",
					"60"=>"Card not authorised",
					"61"=>"No credit worthiness",
					"62"=>"Communication error",
					"63"=>"Transaction not allowed for cardholder",
					"64"=>"Invalid Data in Request",
					"65"=>"Blocked bank code",
					"66"=>"CVV2/CVC2 Failure",
					"99"=>"General error",
				);
		return $reason[$code];
	}

	public function switch_merchant($currency, $debug = null)
	{
		if ($debug)
		{
			$this->pay_to_email = "tommy@eservicesgroup.net";
			$this->status2_email = "tommy@eservicesgroup.net";
			$this->diff_amount_email = "tommy@eservicesgroup.net";
			$this->merchant_id = "17926574";
			$this->query_password = self::MQI_API_PASSWORD;
			$this->sw_md5 = "B7164387B538C925AE41B4638E1ADC48";
		}
		else
		{
			$this->sw_md5 = "3EEEFA9B85FD17DB0FE0EA5195A2496A";
	//we set the same password for every acct
			$this->query_password = self::MQI_API_PASSWORD;

			switch ($currency)
			{
				case "EUR":
					$this->pay_to_email = "eur-sqc@valuebasket.com";
					$this->merchant_id = "29747547";
					break;
				case "GBP":
					$this->pay_to_email = "gbp-sqc@valuebasket.com";
					$this->merchant_id = "29747627";
					break;
				case "USD":
					$this->pay_to_email = "usd-sqc@valuebasket.com";
					$this->merchant_id = "29747574";
					break;
				case "AUD":
					$this->pay_to_email = "aud-sqc@valuebasket.com";
					$this->merchant_id = "29747458";
					break;
				case "HKD":
					$this->pay_to_email = "hkd-sqc@valuebasket.com";
					$this->merchant_id = "29747681";
					break;
				case "PLN":
					$this->pay_to_email = "pln-sqc@valuebasket.com";
					$this->merchant_id = "46362232";
					$this->sw_md5 = "0E7B70ED336FC35F4F64342D3509FC53";
					break;
			}
		}
	}

	public function require_credit_check($is_fraud = FALSE)
	{
		return parent::require_credit_check($is_fraud);
/*
		if (($this->so->get_bill_address() == $this->so->get_delivery_address()) &&
		    ($this->so->get_bill_postcode() == $this->so->get_delivery_postcode()) &&
		    ($this->so->get_bill_city() == $this->so->get_delivery_city()) &&
		    ($this->so->get_bill_state() == $this->so->get_delivery_state()) &&
		    ($this->so->get_bill_country_id() == $this->so->get_delivery_country_id()))
		{
			$check_country = array('GB' => array('GBP', 200),
								   'IE' => array('EUR', 150),
								   'FR' => array('EUR', 250),
								   'ES' => array('EUR', 180),
								   'FI' => array('EUR', 500),
								   'US' => array('USD', 200),
								   'AU' => array('AUD', 250),
								   'HK' => array('HKD', 4000));
		}
		else
		{
			$check_country = array('GB' => array('GBP', 200),
								   'IE' => array('EUR', 150),
									'FR' => array('EUR', 150),
									'ES' => array('EUR', 100),
									'FI' => array('EUR', 200),
									'US' => array('USD', 150),
									'AU' => array('AUD', 150),
									'HK' => array('HKD', 2500));
		}

		$order_country = strtoupper($this->so->get_delivery_country_id());
		if (array_key_exists($order_country, $check_country))
		{
			$currency = $check_country[$order_country][0];
			$amount = $check_country[$order_country][1];
			if ((strtoupper($this->so->get_currency_id()) == $currency) && ($this->so->get_amount() <= $amount))
			{
				return false;
			}
			return true;
		}
		return true;
*/
	}

	private function require_park()
	{
		//return $this->so->get_amount()*$this->so->get_rate() > 200;
		return false;
	}

	public function update_pending_list()
	{
		$this->doing_pending = TRUE;

		$so_srv = $this->get_so_srv();
		$sops_dao = $so_srv->get_sops_dao();

		if ($so_list = $so_srv->get_dao()->get_so_w_pmgw(array("sops.payment_gateway_id"=>$this->pmgw_id, "sops.payment_status"=>'P', "sops.pending_action"=>'CC', "sops.pay_date IS NOT NULL"=>null, "UNIX_TIMESTAMP() - UNIX_TIMESTAMP(sops.pay_date) >= "=>43200)))
		{
			foreach ($so_list as $this->so)
			{
				$this->switch_merchant($this->so->get_currency_id());
				$this->error_type = 0;
				$this->payment_status = "S";
				$this->remark = "status:processed";
				$this->result();
			}
		}
	}

	private function email_check_api()
	{
		$this->client = $this->get_client_srv()->get(array("id"=>$this->so->get_client_id()));
		$this->pmgw_url = "https://www.moneybookers.com/app/email_check.pl?cust_id={$this->merchant_id}&password=".strtolower($this->sw_md5)."&email={$this->client->get_email()}";
		if ($result = $this->connect())
		{
			$rs = array();
			$ar_result = @explode(",", $result);
			foreach ($ar_result as $data)
			{
				$ar_data = @explode("=", $data);
				$rs[trim($ar_data[0])] = trim($ar_data[1]);
			}
			return $rs;
		}
	}

	private function connect($redirect_false = 0)
	{
		$http = $this->get_http();
		$http->set_remote_site($this->pmgw_url);

		$this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", $this->pmgw_url);

		if ($rs = $http->get_content())
		{
			$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", $rs);
			return $rs;
		}
		elseif($redirect_false);
		{
			$this->redirect_fail();
		}
	}

	public function redirect_success()
	{
		redirect(base_url()."checkout_onepage/payment_result/1/{$this->so->get_so_no()}");
	}

	public function redirect_fail($so_no="")
	{
		redirect(base_url()."checkout_onepage/payment_result/0/{$so_no}");
	}

    public function submitForm($postdata)
	{
	 	$cpt = curl_init();

		curl_setopt($cpt, CURLOPT_URL, self::MONEYBOOKERS_SERVER);
		curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($cpt, CURLOPT_USERAGENT, "php moneybooker post");
		curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 40);
		curl_setopt($cpt, CURLOPT_TIMEOUT, 45);

		curl_setopt($cpt, CURLOPT_POST, 1);
		curl_setopt($cpt, CURLOPT_POSTFIELDS, $postdata);

		$formResult = curl_exec($cpt);
		$error = curl_error($cpt);
		$info = curl_getinfo($cpt);

		curl_close($cpt);
		return array("result" => $formResult, "error" => $error, "info" => $info);
	}
}

/* End of file pmgw_moneybookers_service.php */
/* Location: ./system/application/libraries/service/Pmgw_moneybookers_service.php */