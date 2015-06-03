<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_voucher.php";

class Pmgw_global_collect_service extends Pmgw_voucher
{

	protected $merchant_id = "5932";
	protected $server_ip = "94.236.11.182";
	protected $pmgw_url = "https://ps.gcsip.com/wdl/wdl";
	protected $xml;
	protected $write_to_log = TRUE;
	protected $lang = 'en';
	protected $card_type;
	protected $connect_ts;
	protected $notification_email = "compliance-alerts@eservicesgroup.net, itsupport@eservicesgroup.net";
	protected $require_cc = FALSE;
	protected $pmgw_id = "global_collect";
	protected $checkout_controller_name = 'checkout';
	protected $checkout_pmgw_name = 'global_collect';

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$CI->load->helper(array('url', 'string'));
		$this->input=$CI->input;
	}

	public function init($vars)
	{
		$pbv_srv = $this->get_pbv_srv();
		$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$vars["platform_id"]));
		$vars["currency_id"] = $platform_obj->get_platform_currency_id();

		if ($vars["payment_methods"])
		{
			$this->card_type = $vars["payment_methods"];
		}

		$so_srv = $this->get_so_srv();

		$so_obj = $so_srv->cart_to_so($vars);

		if ($so_obj === FALSE)
		{
			return FALSE;
		}
		$this->so = $so_obj;
		$this->store_af_info();
		$this->sops = $vars["sops"];
		$this->promo = $vars["promo"];
		$this->so_item_list = $vars["so_item_list"];

		$this->client = $this->get_client_srv()->get(array("id"=>$this->so->get_client_id()));
		if ($this->client === FALSE)
		{
			return FALSE;
		}
	}

	public function checkout($debug=0)
	{
		$this->check_debug($debug);
		$this->gen_payment_xml();
		if ($result = $this->connect())
		{
			$this->xml_response($result);
		}
		else
		{
			$this->redirect_fail();
		}
	}

	public function response($vars, $debug)
	{
		$this->check_debug($debug);

		$so_srv = $this->get_so_srv();
		if ($this->so = $so_srv->get_dao()->get_so_w_pmgw(array("so.txn_id"=>$this->input->get("REF"), "sops.mac_token"=>$this->input->get("RETURNMAC")), array("limit"=>1)))
		{
			$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $_SERVER['QUERY_STRING']));
			$this->gen_get_order_status_xml();
			if ($result = $this->connect())
			{
				$this->xml_response($result);
				$this->check_result();
			}
			else
			{
				$this->redirect_fail($this->so->get_so_no());
			}
		}
		else
		{
			$this->redirect_fail();
		}
	}

	public function xml_response($vars, $pending_action = "")
	{
		$so_srv = $this->get_so_srv();
		$sops_dao = $so_srv->get_sops_dao();

		if (!$this->doing_pending)
		{
			$this->sops = $sops_dao->get(array("so_no"=>$this->so->get_so_no()));
		}

		$xml = simplexml_load_string($vars);
		$resp_result = (string)$xml->REQUEST->RESPONSE->RESULT;

		switch ((string)$xml->REQUEST->ACTION)
		{
			case "INSERT_ORDERWITHPAYMENT":
				if ($resp_result == 'OK')
				{
					$this->so->set_txn_id((string)$xml->REQUEST->RESPONSE->ROW->REF);
					$this->sops->set_payment_status('P');
					$this->sops->set_mac_token((string)$xml->REQUEST->RESPONSE->ROW->RETURNMAC);
					$sops_dao->update($this->sops);
					$so_srv->update($this->so);
					$this->redirect((string)$xml->REQUEST->RESPONSE->ROW->FORMACTION);
				}
				else
				{
					$this->sops->set_payment_status('F');
					$sops_dao->update($this->sops);
					$this->redirect_fail();
				}
				break;

			case "GET_ORDERSTATUS":
				if ($resp_result == 'OK')
				{
					$resp_avsresult = (string)$xml->REQUEST->RESPONSE->STATUS->AVSRESULT;
					$resp_fraudresult = (string)$xml->REQUEST->RESPONSE->STATUS->FRAUDRESULT;
					$resp_statusid = (int)$xml->REQUEST->RESPONSE->STATUS->STATUSID;
					$resp_ccno = (string)$xml->REQUEST->RESPONSE->STATUS->CREDITCARDNUMBER;

					if ($resp_avsresult != "")
					{
						$this->sops->set_risk_ref1($resp_avsresult);
					}

					if ($resp_fraudresult != "")
					{
						$this->sops->set_risk_ref2($resp_fraudresult);
					}

					if ($resp_ccno != "")
					{
						$this->socc_add(array("card_last4"=>ltrim($resp_ccno, "*")));
					}

					if (!$this->doing_pending && ($resp_statusid == 20 || $resp_statusid == 25))
					{
						echo "<script>top.frames['psform'].myLytebox.end();</script>";
						exit;
					}

					if ($resp_statusid >= 800)
					{
						$this->sops->set_payment_status('S');
						if ($this->so->get_bill_country_id() == 'GB' && ($rr_obj = $so_srv->get_rr_dao()->get(array("payment_gateway_id"=>$this->pmgw_id, "risk_ref"=>$resp_avsresult))))
						{
							switch($rr_obj->get_action())
							{
								case 'S':
								$this->sops->set_payment_status('S');
								break;

								case 'CC':
								$this->sops->set_payment_status('S');
								$this->require_cc = TRUE;
								$this->note = "Credit Check: AVSRESULT - {$resp_avsresult} = {$rr_obj->get_risk_ref_desc()}";
								break;

								case 'F':
									$this->sops->set_payment_status('P');
									$this->sops->set_pending_action('C');
									$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status: AVSRESULT failed"));
								break;
							}
						}
					}
					elseif ($resp_statusid == 525)
					{
						$this->sops->set_payment_status('P');
						$this->sops->set_pending_action('CC');
						$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status: PAYMENT CHALLENGED"));
					}
					elseif (
							!($resp_statusid == 50 || $resp_statusid == 650 || $resp_statusid == 20 || $resp_statusid == 25) ||
							 ($this->doing_pending && ($this->connect_ts - strtotime($this->so->get_order_create_date())) > 7200)
							)
					{
						$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status:failed"));
						$this->sops->set_payment_status('F');
					}
				}
				else
				{
					$this->sops->set_payment_status('F');
				}
				return $this->result();
				break;

			case "CANCEL_PAYMENT":
				if($resp_result == "NOK")
				{
					$this->sops->set_payment_status('CF');

					switch ($pending_action)
					{
						case "C":
							//order cancel failed
							$this->so->set_status(2);
							$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status:cancel failed"));

							//send notification email to notice Ryan and Ethan for the cancellation failure
							$this->send_notification_email($this->so->get_so_no(), $pending_action);
							break;
						case "R":
							$this->so->set_status(0);
							$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status:reject failed"));
							break;
					}
				}
				else
				{
					//order cancel successful
					$this->sops->set_payment_status('C');
					$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status:cancelled"));
					$this->so->set_status(0);
				}

				$sops_dao->update($this->sops);
				$so_srv->get_dao()->update($this->so);
				return TRUE;
				break;

			case "PROCESS_CHALLENGED":
				if($resp_result == "NOK")
				{
					$this->sops->set_payment_status('F');
					$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status:PROCESS_CHALLENGED failed"));
					$this->send_notification_email($this->so->get_so_no(), $pending_action);
					$this->so->set_status(0);
				}
				else
				{
					$this->sops->set_payment_status('S');
					$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status:processed"));
//					$this->so->set_expect_delivery_date($this->get_del_srv()->get_edd($this->so->get_courier_id(), $this->so->get_delivery_country_id()));
					$this->so->set_status(2);
				}

				$sops_dao->update($this->sops);
				$so_srv->get_dao()->update($this->so);
				return TRUE;
				break;
		}
		exit;
	}

	public function result()
	{
		$so_srv = $this->get_so_srv();
		$sops_dao = $so_srv->get_sops_dao();

		if ($this->sops->get_payment_status() == "F" || $this->sops->get_pending_action()  == "C")
		{
			$sops_dao->update($this->sops);
			$this->so->set_status(0);
			$so_srv->get_dao()->update($this->so);
			if ($this->doing_pending)
			{
				if ($soext_obj = $this->get_so_srv()->get_soext_dao()->get(array("so_no"=>$this->so->get_so_no())))
				{
					if ($soext_obj->get_acked())
					{
						$this->fire_fail_event();
					}
				}
			}
			return FALSE;
		}
		elseif ($this->sops->get_payment_status() == "S")
		{
			$this->sops->set_pay_date(date("Y-m-d H:i:s"));
			$this->sops->set_remark(trim($this->sops->get_remark()."\n"."status:processed"));
			$sops_dao->update($this->sops);
			$pbv_srv = $this->get_pbv_srv();
			$this->so->set_expect_delivery_date($this->get_del_srv()->get_edd($this->so->get_courier_id(), $this->so->get_delivery_country_id()));
			$this->so->set_status($this->require_credit_check()?2:3);
			$so_srv->get_dao()->update($this->so);
			$so_srv->update_website_display_qty($this->so);
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
		else
		{
			if (!$this->doing_pending)
			{
				// Comment out in order to not send acknowledgement email
				// $this->fire_success_event(1);
			}
			$sops_dao->update($this->sops);
			return TRUE;
		}
	}

	public function check_result()
	{
		if ($this->sops->get_payment_status() == "N" || $this->sops->get_payment_status() == "F" || $this->so->get_status() == 0)
		{
			$this->redirect_fail($this->so->get_so_no());
		}
		else
		{
			$this->unset_variable();
			$this->redirect_success();
		}
	}

	public function update_pending_list($debug=0)
	{
		$this->check_debug($debug);
		$this->doing_pending = TRUE;

		$so_srv = $this->get_so_srv();
		$sops_dao = $so_srv->get_sops_dao();

		if ($so_list = $so_srv->get_dao()->get_so_w_pmgw(array("sops.payment_gateway_id"=>$this->pmgw_id, "sops.payment_status"=>'P', "(sops.pending_action IS NULL OR sops.pending_action <> 'CC')"=>null)))
		{
			foreach ($so_list as $this->so)
			{
				$this->sops = $sops_dao->get(array("so_no"=>$this->so->get_so_no()));
				$cur_pending_action = $this->sops->get_pending_action();

				if($cur_pending_action)
				{
					switch ($cur_pending_action)
					{
						case "P":
							$this->gen_process_challenged_xml();
							break;
						default:
							$this->gen_cancel_xml();
					}
					if($result = $this->connect())
					{
						$this->xml_response($result, $cur_pending_action);
					}
				}
				else
				{
					$this->gen_get_order_status_xml();
					$this->connect_ts = mktime();
					if ($result = $this->connect())
					{
						$this->xml_response($result);
					}
				}
			}
		}
	}

	public function check_debug($debug=0)
	{
		if ($debug && !$this->get_config()->value_of("payment_debug_allow"))
		{
			$debug = 0;
		}

		if ($debug)
		{
			$this->server_ip = "218.189.104.250";
			$this->pmgw_url = "https://ps.gcsip.nl/wdl/wdl";
			$this->debug = 1;
			$this->notification_email = "tommy@eservicesgroup.net";
		}
	}

	public function gen_payment_xml()
	{
		if (empty($this->so) || empty($this->client) || empty($this->so_item_list))
		{
			$this->redirect_fail();
		}

		$bill_street = $this->so->get_bill_address();
		$del_street = $this->so->get_delivery_address();
		$bill_add_address = $del_add_address = "";

		$this->check_split_address($bill_street, $bill_add_address);
		$this->check_split_address($del_street, $del_add_address);

		$bill_street = str_replace("|", " ", $bill_street);
		$del_street = str_replace("|", " ", $del_street);

		$bill_firstname = $this->so->get_bill_name();
		$del_firstname = $this->so->get_delivery_name();
		$bill_surname = $bill_prefixsurname = $del_surname = $del_prefixsurname = "";

		$this->check_split_name($bill_firstname, $bill_prefixsurname, $bill_surname);
		$this->check_split_name($del_firstname, $del_prefixsurname, $del_surname);

		switch (get_lang_id())
		{
			case "pt-br":
				$gc_lang = "pt";
				break;
			case "zh-tw":
				$gc_lang = "tc";
				break;
			case "zh-cn":
				$gc_lang = "sc";
				break;
			default:
				$gc_lang = get_lang_id();
		}

$this->xml  =
"<XML>
	<REQUEST>
		<ACTION>INSERT_ORDERWITHPAYMENT</ACTION>
		<META>
			<IPADDRESS>{$this->server_ip}</IPADDRESS>
			<MERCHANTID>{$this->merchant_id}</MERCHANTID>
			<VERSION>1.0</VERSION>
		</META>
		<PARAMS>
			<ORDER>
				<ORDERID>".(str_replace("SO", "", $this->so->get_so_no())*1)."</ORDERID>
				<AMOUNT>".($this->so->get_amount()*100)."</AMOUNT>
				<CURRENCYCODE>{$this->so->get_currency_id()}</CURRENCYCODE>
				<CUSTOMERID>{$this->so->get_client_id()}</CUSTOMERID>
				<IPADDRESSCUSTOMER>{$_SERVER["REMOTE_ADDR"]}</IPADDRESSCUSTOMER>
				<FIRSTNAME>".xmlspecialchars($bill_firstname)."</FIRSTNAME>
				<PREFIXSURNAME>".xmlspecialchars($bill_prefixsurname)."</PREFIXSURNAME>
				<SURNAME>".xmlspecialchars($bill_surname)."</SURNAME>
				<STREET>".xmlspecialchars($bill_street)."</STREET>
				<ADDITIONALADDRESSINFO>".xmlspecialchars($bill_add_address)."</ADDITIONALADDRESSINFO>
				<ZIP>".xmlspecialchars($this->so->get_bill_postcode())."</ZIP>
				<CITY>".xmlspecialchars($this->so->get_bill_city())."</CITY>
				<STATE>".xmlspecialchars($this->so->get_bill_state())."</STATE>
				<COUNTRYCODE>{$this->so->get_bill_country_id()}</COUNTRYCODE>
				<EMAIL>".xmlspecialchars($this->client->get_email())."</EMAIL>
				<PHONENUMBER>".xmlspecialchars(trim($this->client->get_tel_1()." ".$this->client->get_tel_2()." ".$this->client->get_tel_3()))."</PHONENUMBER>
				<SHIPPINGFIRSTNAME>".xmlspecialchars($del_firstname)."</SHIPPINGFIRSTNAME>
				<SHIPPINGPREFIXSURNAME>".xmlspecialchars($del_prefixsurname)."</SHIPPINGPREFIXSURNAME>
				<SHIPPINGSURNAME>".xmlspecialchars($del_surname)."</SHIPPINGSURNAME>
				<SHIPPINGSTREET>".xmlspecialchars($del_street)."</SHIPPINGSTREET>
				<SHIPPINGADDITIONALADDRESSINFO>".xmlspecialchars($del_add_address)."</SHIPPINGADDITIONALADDRESSINFO>
				<SHIPPINGZIP>".xmlspecialchars($this->so->get_delivery_postcode())."</SHIPPINGZIP>
				<SHIPPINGCITY>".xmlspecialchars($this->so->get_delivery_city())."</SHIPPINGCITY>
				<SHIPPINGSTATE>".xmlspecialchars($this->so->get_delivery_state())."</SHIPPINGSTATE>
				<SHIPPINGCOUNTRYCODE>{$this->so->get_delivery_country_id()}</SHIPPINGCOUNTRYCODE>
				<COMPANYNAME>".xmlspecialchars($this->so->get_bill_company())."</COMPANYNAME>
				<LANGUAGECODE>{$gc_lang}</LANGUAGECODE>
				<MERCHANTREFERENCE>{$this->so->get_client_id()}-{$this->so->get_so_no()}</MERCHANTREFERENCE>
			</ORDER>
			<ORDERLINES>
";

		foreach ($this->so_item_list as $soi)
		{
$this->xml .=
"				<ORDERLINE>
					<LINENUMBER>{$soi->get_line_no()}</LINENUMBER>
					<LINEAMOUNT>".($soi->get_amount()*100)."</LINEAMOUNT>
				</ORDERLINE>
";
		}

$this->xml .=
"			</ORDERLINES>
			<PAYMENT>
				<CVVINDICATOR>1</CVVINDICATOR>
				<RETURNURL>".($this->debug?base_url():str_replace('http://', 'https://', base_url())). "{$this->checkout_controller_name}/response/{$this->checkout_pmgw_name}".($this->debug?"/1":"")."?so_no=".$this->so->get_so_no()."</RETURNURL>
				<PAYMENTPRODUCTID>{$this->card_type}</PAYMENTPRODUCTID>
				<AMOUNT>".($this->so->get_amount()*100)."</AMOUNT>
				<CURRENCYCODE>{$this->so->get_currency_id()}</CURRENCYCODE>
				<COUNTRYCODE>{$this->so->get_bill_country_id()}</COUNTRYCODE>
				<LANGUAGECODE>{$gc_lang}</LANGUAGECODE>
				<HOSTEDINDICATOR>1</HOSTEDINDICATOR>
			</PAYMENT>
		</PARAMS>
	</REQUEST>
</XML>";
	}

	public function gen_get_order_status_xml()
	{
		if (empty($this->so))
		{
			$this->redirect_fail();
		}
$this->xml  =
"<XML>
	<REQUEST>
		<ACTION>GET_ORDERSTATUS</ACTION>
		<META>
			<MERCHANTID>{$this->merchant_id}</MERCHANTID>
			<IPADDRESS>{$this->server_ip}</IPADDRESS>
			<VERSION>2.0</VERSION>
		</META>
		<PARAMS>
			<ORDER>
				<ORDERID>".(str_replace("SO", "", $this->so->get_so_no())*1)."</ORDERID>
			</ORDER>
		</PARAMS>
	</REQUEST>
</XML>";
	}

	public function gen_cancel_xml()
	{
		$this->xml =
"<XML>
	<REQUEST>
		<ACTION>CANCEL_PAYMENT</ACTION>
		<META>
			<MERCHANTID>{$this->merchant_id}</MERCHANTID>
			<IPADDRESS>{$this->server_ip}</IPADDRESS>
			<VERSION>1.0</VERSION>
		</META>
		<PARAMS>
			<PAYMENT>
				<ORDERID>".(str_replace("SO", "", $this->so->get_so_no())*1)."</ORDERID>
				<EFFORTID>1</EFFORTID>
				<ATTEMPTID>1</ATTEMPTID>
			</PAYMENT>
		</PARAMS>
	</REQUEST>
</XML>";
	}

	public function gen_process_challenged_xml()
	{
		$this->xml =
"<XML>
	<REQUEST>
		<ACTION>PROCESS_CHALLENGED</ACTION>
		<META>
			<MERCHANTID>{$this->merchant_id}</MERCHANTID>
			<IPADDRESS>{$this->server_ip}</IPADDRESS>
			<VERSION>1.0</VERSION>
		</META>
		<PARAMS>
			<PAYMENT>
				<ORDERID>".(str_replace("SO", "", $this->so->get_so_no())*1)."</ORDERID>
				<EFFORTID>1</EFFORTID>
			</PAYMENT>
		</PARAMS>
	</REQUEST>
</XML>";
	}

	public function check_split_address(&$address, &$add_address)
	{
		if (strlen($address) > 50)
		{
			$ar_address = @explode("|", $address);
			$ar_len[0] = strlen($ar_address[0]);
			$ar_len[1] = strlen($ar_address[1]);
			$ar_len[2] = strlen($ar_address[2]);
			if (($ar_len[0]+$ar_len[1])<50)
			{
				$address = $ar_address[0]." ".$ar_address[1];
				if ($ar_len[2]<51)
				{
					$add_address = $ar_address[2];
				}
			}
			elseif ($ar_len[0] < 51)
			{
				$address = $ar_address[0];
				if (($ar_len[1]+$ar_len[2])<50)
				{
					$add_address = $ar_address[1]." ".$ar_address[2];
				}
				elseif($ar_len[1] <51)
				{
					$add_address = $ar_address[1];
				}
			}
			else
			{
				$address = substr($address, 0, 50);
			}
		}
	}

	public function check_split_name(&$firstname, &$prefixsurname, &$surname)
	{
		$ar_firstname = explode(" ", $firstname);

		if (($name_count = count($ar_firstname)) > 1)
		{
			switch ($name_count)
			{
				case 3:
					$firstname = $ar_firstname[0];
					$prefixsurname = substr($ar_firstname[1], 0, 15);
					$surname = substr($ar_firstname[2], 0, 35);
					break;
				case 2:
					$firstname = $ar_firstname[0];
					$surname = substr($ar_firstname[1], 0, 35);
					break;
				default:
					$firstname = $ar_firstname[0];
					$prefixsurname = substr($ar_firstname[1], 0, 15);
					array_shift($ar_firstname);
					array_shift($ar_firstname);
					$surname = substr(@implode(" ", $ar_firstname), 0, 35);
					break;

			}
		}

		if (strlen($firstname) > 15)
		{
			$firstname = substr($firstname, 0, 15);
		}
	}

	public function require_credit_check()
	{
		$require_cc = FALSE;

		$product_id = $this->sops->get_card_id();
		if (!(($product_id > 819 && $product_id < 830) ||
			$product_id == 831 ||
			$product_id == 836 ||
			$product_id == 856 ||
			$product_id == 802 ||
			$product_id == 803 ||
			$product_id == 805 ||
			$product_id == 809))
		{
			if(!$this->low_risk_country_rules($this->so->get_amount()*$this->so->get_ref_1(), $this->so->get_bill_country_id(), $this->so->get_delivery_country_id()))
			{
				if ($this->require_cc)
				{
					$require_cc = TRUE;
				}
				elseif ($this->so->get_amount()*$this->so->get_rate() > 200)
				{
					$this->note = "Credit Check: > USD 200 equivalent";
					$require_cc = TRUE;
				}
				elseif($this->so->get_bill_country_id() != $this->so->get_delivery_country_id())
				{
					$this->note = "Credit Check: Billing Country different from Shipping Country";
					$require_cc = TRUE;
				}
				elseif($this->sops->get_risk_ref2() == 'N')
				{
					$this->note = "Credit Check: FRAUDRESULT - N = No fraud requested";
					$require_cc = TRUE;
				}
				elseif($this->sops->get_risk_ref2() == 'E')
				{
					$this->note = "Credit Check: FRAUDRESULT - E = Error while checking";
					$require_cc = TRUE;
				}
				else
				{
					$this->note = "Credit Check: Auto approved";
				}
			}
			else
			{
				$this->note = "Credit Check(Low Risk Country): Auto approved";
			}
		}

		if ($this->note)
		{
			$this->add_note();
		}

		return $require_cc;
	}

	protected function connect()
	{
		$http = $this->get_http();
		$http->set_remote_site($this->pmgw_url);
		$http->set_postfields($this->xml);
		$http->set_httpheader(array("Content-Type: text/xml"));

		$this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", $this->xml);

		if ($rs = $http->get_content())
		{
			$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", $rs);
			return $rs;
		}
		else
		{
			$this->redirect_fail();
		}
	}

	public function send_notification_email($so, $pending_action)
	{
		switch ($pending_action)
		{
			case "C":
				$message = "Please note that Order Number {$so} has failed AVS check - AVS Result description but cancellation of the order has been unsuccessful.\n\nOrder is in credit check page now pending further checks.";
				$title = "[CV] Global Collect order cancellation failure notice";
				break;
			case "P":
				$message = "Please note that Order Number {$so} failed sending PROCESS_CHALLENGED to Global Collect. Order was marked as Unsuccessful in our system.";
				$title = "[CV] Global Collect PROCESS_CHALLENGED failure notice";
				break;
		}

		mail($this->notification_email, $title, $message);
	}
	public function redirect($url)
	{
		redirect($url);
	}

	public function redirect_success()
	{
		echo "<script>top.document.location.href='".base_url()."checkout/payment_result/1/{$this->so->get_so_no()}';</script>";
	}

	public function redirect_fail($so_no="")
	{
		echo "<script>top.document.location.href='".base_url()."checkout/payment_result/0/{$so_no}';</script>";
	}
}

/* End of file pmgw_global_collect_service.php */
/* Location: ./system/application/libraries/service/Pmgw_global_collect_service.php */