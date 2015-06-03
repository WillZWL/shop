<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_voucher.php";

class Pmgw_bibit_service extends Pmgw_voucher
{
	private $payment_info;
	private $reply_info;
	private $shopper_info;
	private $risk_threshold_uk = 100;
	private $error_type = 0;
	private $cancel_order;
	private $remark;

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$CI->load->helper('url');
	}

	public function init($vars)
	{
		$pbv_srv = $this->get_pbv_srv();
		$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$vars["platform_id"]));
		$vars["currency_id"] = $platform_obj->get_platform_currency_id();
		$so_srv = $this->get_so_srv();
		$so_obj = $so_srv->cart_to_so($vars);
		if ($so_obj === FALSE)
		{
			return FALSE;
		}
		$this->so = $so_obj;
		$vars["so_no"] = $this->so->get_so_no();
		$vars["amount"] = $this->so->get_amount();
		$this->payment_info = array(
								"cardtype" => $vars["cardtype"],
								"totalamount" => $vars["amount"],
								"curr" => $vars["currency_id"],
								"ordernumber" => $vars["so_no"],
								"holdername" => $vars["holdername"],
								"cardnum" => $vars["cardnum"],
								"start_month" => $vars["start_month"],
								"start_year" => $vars["start_year"],
								"exp_month" => $vars["exp_month"],
								"exp_year" => $vars["exp_year"],
								"issuenum" => $vars["inum"],
								"cvc" => $vars["cvc"],
								"pares" => $vars["pares"],
								"echodata" => $vars["echodata"],
								"ordercontent" => $vars["ordercontent"],
								"review" => $vars["review"]
							);
		$this->reply_info = array(
								"currentTag" => "",
								"cc_result" => "",
								"refuse_code" => "",
								"refuse_desc" => "",
								"error_code" => "",
								"error_desc" => "",
								"cvs_desc" => "",
								"avs_desc" => "",
								"riskscore" => "",
								"check_3d" => "",
								"parequest" => "",
								"issuerurl" => "",
								"echodata" => "",
								"order_cancelled" => ""
							);
//		$this->logheader["message"] = var_export($this->payment_info, true);
//		$this->logger->write_log($this->logheader);

		$client = $this->get_client_srv()->get(array("id"=>$this->so->get_client_id()));

		$tel = "";
		if ($client->get_tel_1())
		{
			$tel .= $client->get_tel_1();
		}

		if ($client->get_tel_2())
		{
			$tel .= "-".$client->get_tel_2();
		}

		if ($client->get_tel_3())
		{
			$tel .= "-".$client->get_tel_3();
		}


		$this->shopper_info = array(
							"ip" => $_SERVER['REMOTE_ADDR'],
							"sid" => session_id(),
							"acceptheader" => $_SERVER['HTTP_ACCEPT'],
							"useragentheader" => $_SERVER['HTTP_USER_AGENT'],
							"email" => $client->get_email(),
							"firstname" => $client->get_forename(),
							"lastname" => $client->get_surname(),
							"street" => $client->get_address_1()." ".$client->get_address_2()." ".$client->get_address_3(),
							"postcode" => $client->get_postcode(),
							"city" => $client->get_city(),
							"telephone" => $tel,
							"countrycode" => $client->get_country_id()
						);
	}

	public function checkout($debug=0)
	{
		if ($debug && !$this->get_config()->value_of("payment_debug_allow"))
		{
			$debug = 0;
		}

		include_once(APPPATH.'libraries/service/Bibit/direct_bibit.lib.php');
		include_once(APPPATH.'libraries/service/Bibit/direct_bibit.func.php');

		include_once(BASEPATH."libraries/Encrypt.php");
		$encrypt = new CI_Encrypt();
//		$http = $this->get_http();
		$fi_name = $debug?"BIBIT_PG_TEST":"BIBIT_PG";
		$http_obj = $this->get_hi_dao()->get(array("name"=>$fi_name));
//		$http->set_remote_site("https://".$http_obj->get_username().":".$encrypt->decode($http_obj->get_password())."@".$http_obj->get_server()."/jsp/merchant/xml/paymentService.jsp");
//		$http->set_cookie("/tmp/bibitcookie/".$this->payment_info['ordernumber']);

		$_bibit = new Bibit();
		$_bibit->merchantCode = $http_obj->get_username();
		$_bibit->merchantPassword = $encrypt->decode($http_obj->get_password());

		$_bibit->cookie = "/tmp/bibitcookie/".$this->payment_info['ordernumber'];
		$_bibit->url = "https://".$http_obj->get_username().":".$encrypt->decode($http_obj->get_password())."@".$http_obj->get_server()."/jsp/merchant/xml/paymentService.jsp";

//		$_bibit->Bibitstart($debug);
		$_bibit->orderId = $_SESSION["client"]["id"]."-".$this->payment_info['ordernumber'];
		$_bibit->totalammount = 100*$this->payment_info['totalamount'];
		$_bibit->currcode = $this->payment_info['curr'];
		$_bibit->description = $this->payment_info['ordernumber']." - ".$this->shopper_info["email"];
		$_bibit->StartXML();
		$_bibit->FillPaymentFormXML($this->payment_info["ordercontent"]);
		$_bibit->FillExtPaymentXML($this->payment_info);
		$_bibit->FillPaymentXML($this->payment_info, $this->shopper_info);
		$_bibit->FillShopperXML($this->shopper_info);
		$_bibit->EndXML($this->payment_info);
//		$this->logheader["message"] = var_export($this->shopper_info, true);
//		$this->logger->write_log($this->logheader);
		$_bibit->xml = utf8_encode($_bibit->xml);
//		$this->logheader["message"] = var_export($_bibit->xml, true);
//		$this->logger->write_log($this->logheader);
//		$http->get_hcs()->set_postfields($_bibit->xml);
//		$bibitResult = $this->http->get_content();

		$bibitResult = $_bibit->CreateConnection();
//		$this->logheader["message"] = var_export($bibitResult, true);
//		$this->logger->write_log($this->logheader);
		/*
		THERE IS AN XML ERROR REPLY
		1 : internal error, could be everything
		2 : parse error, invalid xml
		3 : invalid number of transactions in batch
		4 : security error
		5 : invalid request
		6 : invalid content, occurs when xml is valid but content of xml not
		7 : payment details in the order element are incorrect
		*/
		$this->reply_info = ParseXML_new($bibitResult, $this->reply_info);
//		$this->logheader["message"] = var_export($this->reply_info, true);
//		$this->logger->write_log($this->logheader);

		if($this->reply_info['check_3d']==true)
		{
			$this->payment_info['echodata']=$this->reply_info['echodata'];
<html>
<head>
<title>Redirecting to 3D-secure verification page</title>
</head>
<body OnLoad="redirect23d()">
Redirecting to the 3D-Secure verification site of your card issuer ...
<form name='form_3d' method="POST" action="<?=$this->reply_info['issuerurl'];?>">
<input type='hidden' name="PaReq" value="<?=$this->reply_info['parequest'];?>" />
<input type='hidden' name="TermUrl" value="<?=str_replace('http://', 'https://', base_url())?>checkout/response/bibit/<?=$debug?>" />
<input type='hidden' name="MD" value="<?=base64_encode(serialize($this->payment_info));?>" />
<input type='submit' name="Proceed 3D verification" value='Continue'/>
</form>
<script language="javascript">
<!--
function redirect23d()
{
	document.form_3d.submit();
}
// -->
</script>
</body>
</html>
<?php
			exit;
		}
		$this->replyHandler_uk();


		$bibit_status = $this->error_type=='0'?"S":"F";
		$paid_order = $paid_order = strpos($this->remark, "Order has already been paid")!==FALSE;
//		$this->logheader["message"] = var_export($this->error_type, true);
//		$this->logger->write_log($this->logheader);
		$so_srv = $this->get_so_srv();
		$sops_dao = $so_srv->get_sops_dao();
		$ps_obj = $sops_dao->get(array("so_no"=>$this->payment_info["ordernumber"]));
//		$this->logheader["message"] = var_export($this->payment_info, true);
//		$this->logger->write_log($this->logheader);
		if (!$paid_order)
		{
			$ps_obj->set_payment_status($bibit_status);
		}
		$ps_obj->set_remark($ps_obj->get_remark().$this->remark);
		if ($bibit_status == "S")
		{
			$ps_obj->set_pay_date(date("Y-m-d H:i:s"));
		}

		$sops_dao->update($ps_obj);

		if ($this->cancel_order == true)
		{
			if ($this->reply_info['cc_result']== 'CANCELLED')
			{
				$this->remark = "[ByBibit] ".$this->remark;
			}
			else
			{
				$_bibit->CancelOrderXML();
				$_bibit->xml = utf8_encode($_bibit->xml);
				$bibitResult = $_bibit->CreateConnection();
				$this->reply_info = ParseXML_new($bibitResult, $this->reply_info);
				if ($reply_info['order_cancelled'] == $this->payment_info["ordernumber"])
				{
					$this->remark = "[TOBECANCELLED] ".$this->remark;
				}
				else
				{
					$this->remark = "[FAILTOCANCEL] ".$this->remark;
					//fire_event();
				}
			}
			$ps_obj->set_remark($this->remark);
			$sops_dao->update($ps_obj);
		}

		if ($bibit_status == "S")
		{
			$pbv_srv = $this->get_pbv_srv();
			$platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id"=>$this->so->get_platform_id()));
			$this->so->set_expect_delivery_date(date("Y-m-d H:i:s", time()+$platform_obj->get_latency_in_stock()*86400));
			$this->so->set_status(2);
		}
		else
		{
			$this->so->set_status(0);
		}

		if (!$paid_order)
		{
			$so_srv->get_dao()->update($this->so);
		}

		if ($bibit_status == "S")
		{
			//Add card info to so_credit_chk
			include_once(BASEPATH."libraries/Encrypt.php");
			$encrypt = new CI_Encrypt();
			$socc_vo = $so_srv->get_socc_dao()->get();
			$socc_vo->set_so_no($this->payment_info["ordernumber"]);
			$socc_vo->set_card_holder($this->payment_info["holdername"]);
			$socc_vo->set_card_type($this->payment_info["cardtype"]);
			$socc_vo->set_card_no($encrypt->encode($this->payment_info["cardnum"]));
			$socc_vo->set_card_bin(substr($this->payment_info["cardnum"], 0, 6));
			$socc_vo->set_card_last4(substr($this->payment_info["cardnum"], -4));
			$socc_vo->set_card_exp_month($this->payment_info["exp_month"]);
			$socc_vo->set_card_exp_year($this->payment_info["exp_year"]);
			$socc_vo->set_card_start_month($this->payment_info["start_month"]);
			$socc_vo->set_card_start_year($this->payment_info["start_year"]);
			$socc_vo->set_card_issue_no($this->payment_info["issuenum"]);
			$so_srv->get_socc_dao()->insert($socc_vo);

			// Tracking
			$origin_website = isset($_COOKIE['originw'])?$_COOKIE['originw']:($_COOKIE["LS_siteID"] != ''?13:11);
			$soext_vo = $so_srv->get_soext_dao()->get();
			$soext_vo->set_so_no($this->payment_info["ordernumber"]);
			$soext_vo->set_conv_site_id($origin_website);
			$soext_vo->set_conv_status(1);

			if($_COOKIE["LS_siteID"] != '' && $_COOKIE["LS_siteID"] !='siteID')
			{
				$soext_vo->set_conv_site_ref($_COOKIE["LS_siteID"]);
				$soext_vo->set_ls_time_entered($_COOKIE["LS_timeEntered"]);
				// Insert ls_transaction
				$this->add_ls_transaction($soext_vo);
			}

			$so_srv->get_soext_dao()->insert($soext_vo);

			if ($promo_code = $this->so->get_promotion_code())
			{
				$this->update_promo($promo_code);
			}

			// Fire Event START
			$this->fire_success_event();
			// Fire Event END
			unset($_SESSION["cart"]);
			unset($_SESSION["cart_from_url"]);
			unset($_SESSION["promotion_code"]);
			$_SESSION["pmgw_message"] = $this->display_message;
			redirect(base_url()."checkout/payment_result/1/{$this->payment_info["ordernumber"]}?review=".$this->payment_info["review"]);
		}
		else
		{
			$_SESSION["pmgw_message"] = $this->display_message;
			redirect(base_url()."checkout/payment_result/0");
		}

	}

	public function response($vars, $debug)
	{
		$this->payment_info = unserialize(base64_decode($vars['MD']));

//		$this->logheader["message"] = var_export($this->payment_info, true);
//		$this->logger->write_log($this->logheader);

		$so_srv = $this->get_so_srv();
		if ($this->so = $so_srv->get(array("so_no"=>$this->payment_info['ordernumber'])))
		{
			$this->payment_info['pares'] = $vars['PaRes'];

//			$this->logheader["message"] = var_export($this->payment_info, true);
//			$this->logger->write_log($this->logheader);

			$client = $this->get_client_srv()->get(array("id"=>$this->so->get_client_id()));

			$tel = "";
			if ($client->get_tel_1())
			{
				$tel .= $client->get_tel_1();
			}

			if ($client->get_tel_2())
			{
				$tel .= "-".$client->get_tel_2();
			}

			if ($client->get_tel_3())
			{
				$tel .= "-".$client->get_tel_3();
			}

			$this->shopper_info = array(
								"ip" => $_SERVER['REMOTE_ADDR'],
								"sid" => session_id(),
								"acceptheader" => $_SERVER['HTTP_ACCEPT'],
								"useragentheader" => $_SERVER['HTTP_USER_AGENT'],
								"email" => $client->get_email(),
								"firstname" => $client->get_forename(),
								"lastname" => $client->get_surname(),
								"street" => $client->get_address_1()." ".$client->get_address_2()." ".$client->get_address_3(),
								"postcode" => $client->get_postcode(),
								"city" => $client->get_city(),
								"telephone" => $tel,
								"countrycode" => $client->get_country_id()
							);
//			$this->logheader["message"] = var_export($this->shopper_info, true);
//			$this->logger->write_log($this->logheader);

			$this->checkout($debug);

		}

		else
		{
			$_SESSION["NOTICE"] = "Error!";
			redirect(base_url()."checkout/index/".$debug);
		}
	}

	public function replyHandler_uk()
	{
		include_once(APPPATH.'data/payment_result.php');
		switch ($this->reply_info['cc_result'])
		{
			case "REFUSED":
				$this->remark .= $this->reply_info['cc_result'].":[CODE".$this->reply_info['refuse_code']."]".$this->reply_info['refuse_desc'];
				$this->error_type = '1';
				$this->display_message = $result["display"]["ps"]["REFUSED"];
				break;
			case "ERROR":
				$this->remark .= $this->reply_info['cc_result'].":[CODE".$this->reply_info['error_code']."]".$this->reply_info['error_desc'];
				$this->error_type = '1';
				$this->display_message = $result["display"]["ps"]["ERROR"];
				break;
			case 'CANCELLED':
				$this->remark .= $this->reply_info['cc_result'].". CVC:".$this->reply_info['cvc_desc']." AVS:".$this->reply_info['avs_desc']." Riskscore:".$this->reply_info['riskscore']."[".$this->risk_threshold_uk."]";
				$this->error_type = '1';
				$this->display_message = $result["display"]["ps"]["CANCELLED"];
				if($this->reply_info['avs_desc']=='FAILED' || $this->reply_info['avs_desc']=='PARTIAL APPROVED') $this->error_type = '2';
				$this->cancel_order = true;
				break;
			case 'AUTHORISED':
				$this->remark .= $this->reply_info['cc_result'].". CVC:".$this->reply_info['cvc_desc']." AVS:".$this->reply_info['avs_desc']." Riskscore:".$this->reply_info['riskscore']."[".$this->risk_threshold_uk."]";
				if($this->reply_info['cvc_desc']!='APPROVED')
				{
					$this->error_type = '1';
					$this->display_message = $result["display"]["ps"]["CANCELLED"];
					$this->cancel_order = true;
				}
				elseif($this->reply_info['avs_desc']!='APPROVED' && $this->reply_info['avs_desc']!='PARTIAL APPROVED')
				{
					$this->error_type = '2';
					$this->display_message = $result["display"]["ps"]["CANCELLED"];
					$this->cancel_order = true;
				}
				elseif($this->reply_info['riskscore']>=$this->risk_threshold_uk)
				{
					$this->error_type = '1';
					$this->display_message = $result["display"]["ps"]["CANCELLED"];
					$this->cancel_order = true;
				}
				else
				{
					$this->cancel_order = false;
					$this->display_message = $result["display"]["ps"]["AUTHORISED"];
				}
				break;
			default:
				$this->remark .= "Unknow reply from bibit:".$this->reply_info['cc_result'];
				$this->error_type = '1';
				$this->display_message = $result["display"]["ps"]["DEFAULT"];
				break;
		}
	}
}

/* End of file pmgw_bibit_service.php */
/* Location: ./system/application/libraries/service/Pmgw_bibit_service.php */