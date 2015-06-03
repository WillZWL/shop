<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_voucher.php";

DEFINE ('PLATFORM_TYPE', 'EBAY');

class Ebay_service extends Pmgw_voucher
{
	private $price_srv;
	/*
	private $api_url = "https://api.ebay.com/ws/api.dll";
	//Production
	private $dev_key = "fbd613e2-df0b-40a0-b88c-c16752110fec";
	private $app_key = "se618c204-5ad9-4ed1-9bbb-4d5bf1854fe";
	private $cert_key = "e7ea5c07-adf7-49c6-af2f-e720686a6184";
	private $token = "AgAAAA**AQAAAA**aAAAAA**gtUjTQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AEkoGgAZiLqA+dj6x9nY+seQ**EwoBAA**AAMAAA**cjAkDYgiBTT5/dX3Qn3CuJYw6wcNrxZD833GRUDS/aBEl9eO5HLaglYxhItnhxgrkYtNzDKE7UvwzABdbXOnozFOhRxnI6iexjvnm4ZwyZrlGdjA1rtlJLqVjKQ2I933b8EqdD7QNutD6WER9IgOQ2hPwn1qHR+FKytd/tiRDumC4sV9GAlSq9IYHgmwS+p3oZgN8eaqREXgd28iH3fqsrQ0LqhDYQq1pJHa85uG78W6fxwyo+w0rcPfdTAUapYNYM5F9Qnbna+oyrcqV17L0BU2Dc2pDMX7MMI+yicGi9l8oCDIEMbv29+KgfcfkMPEqH4OaWmD06Zt2Ny/PqlbsE24+U1tX1CL50yzl6z1F4PCe0Ushh7MLvLl8cKf5vkLEqsBym5PI1u2ljFZnHLR3jIqHUHl/iiJNrS33yR86KWjwpUQMlpYghMIK31Rnk6j/xifgKr8QAaCckWlxBwnaF7o7qpAbnw8yodlyd6Lu3WlMvaL7w6s6uP4FYTIw1hPFEj+XQxyFIOY6Zv1m6mh098l+WRIWFhddZmGnv/JgiudQgrlwqBd145cvz4BZmMIgKEElp2S/okc5Aj9myG8kcG2eyri6NX0lH4uUWrlcLPs28aCfhNhQRVpVltUD1Z55/gN1v3GyCYhNAAQCZDMUBQKVoeMnL99Ek99GdJRHEl4TAdPmZpWH9eE+L3LWlLiwiqXcM/g8Mv0SuHFxo1ixsGPVQKBIvf/1sO0AHidmeLCaDPrCcllTylu53EgGJhi";
	*/
	private $notification_email = "steven@eservicesgroup.net";

	private $ebay_paypal_account = 'paypal.ebay@valuebasket.com';
	private $ebay_account;
	private $ebay_site;
	private $add_item_list = array();
	private $update_item_list = array();
	private $revise_item;
	private $end_item;
	private $http_obj;
	private $log_config;
	public $debug = 0;
	public $enable_log = 0;

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$CI->load->helper('url', 'string');
		$this->input=$CI->input;
		include_once(APPPATH."libraries/service/Http_connector.php");
		$this->set_http(new Http_connector());
		include_once(APPPATH."libraries/service/Schedule_job_service.php");
		$this->set_sj_srv(new Schedule_job_service());
		include_once(APPPATH."libraries/service/Batch_service.php");
		$this->set_batch_srv(new Batch_service());
		include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
		$this->set_pbv_srv(new Platform_biz_var_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config(new Context_config_service());
		include_once(APPPATH."libraries/service/Pmgw_paypal_service.php");
		$this->set_paypal_srv(new Pmgw_paypal_service());
		include_once(APPPATH."libraries/service/Bundle_service.php");
		$this->set_bundle_srv(new Bundle_service());
		include_once(APPPATH."libraries/service/Validation_service.php");
		$this->set_valid(new Validation_service());
		include_once(APPPATH."libraries/service/Http_info_service.php");
		$this->set_http_info(new Http_info_service());
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_srv(new So_service());
		include_once(APPPATH."libraries/dao/Transmission_log_dao.php");
		$this->set_tlog_dao(new Transmission_log_dao());
		include_once (APPPATH."libraries/dao/Interface_so_dao.php");
		$this->set_iso_dao(new Interface_so_dao());
		include_once (APPPATH."libraries/dao/Interface_so_payment_status_dao.php");
		$this->set_isops_dao(new Interface_so_payment_status_dao());
		include_once (APPPATH."libraries/dao/Interface_client_dao.php");
		$this->set_ic_dao(new Interface_client_dao());
		include_once (APPPATH."libraries/dao/Interface_so_item_dao.php");
		$this->set_isoi_dao(new Interface_so_item_dao());
		include_once (APPPATH."libraries/dao/Interface_so_item_detail_dao.php");
		$this->set_isoid_dao(new Interface_so_item_detail_dao());
		include_once (APPPATH."libraries/dao/Platform_mapping_dao.php");
		$this->set_pm_dao(new Platform_mapping_dao());
		include_once (APPPATH."libraries/dao/Exchange_rate_dao.php");
		$this->set_xrate_dao(new Exchange_rate_dao());
		include_once (APPPATH."libraries/dao/So_dao.php");
		$this->set_so_dao(new So_dao());
		include_once (APPPATH."libraries/dao/So_payment_status_dao.php");
		$this->set_sops_dao(new So_payment_status_dao());
		include_once (APPPATH."libraries/dao/Client_dao.php");
		$this->set_client_dao(new Client_dao());
		include_once (APPPATH."libraries/dao/So_item_dao.php");
		$this->set_soi_dao(new So_item_dao());
		include_once (APPPATH."libraries/dao/So_item_detail_dao.php");
		$this->set_soid_dao(new So_item_detail_dao());
		include_once (APPPATH."libraries/service/Product_service.php");
		$this->set_product_srv(new Product_service());
		include_once APPPATH."libraries/service/Price_ebay_service.php";
		$this->set_price_srv(new Price_ebay_service());
		include_once(APPPATH."libraries/service/Event_service.php");
		$this->set_event_srv(new Event_service());
		include_once APPPATH."libraries/service/Complementary_acc_service.php" ;
		$this->set_ca_service(new Complementary_acc_service());
		include_once(APPPATH."libraries/service/Supplier_service.php");
		$this->set_sup_srv(new Supplier_service());
	}

	public function get_http()
	{
		return $this->http;
	}

	public function set_http($value)
	{
		$this->http = $value;
	}

	public function get_sj_srv()
	{
		return $this->sj_srv;
	}

	public function set_sj_srv($value)
	{
		$this->sj_srv = $value;
	}

	public function get_batch_srv()
	{
		return $this->batch_srv;
	}

	public function set_batch_srv($value)
	{
		$this->batch_srv = $value;
	}

	public function get_pbv_srv()
	{
		return $this->pbv_srv;
	}

	public function set_pbv_srv($value)
	{
		$this->pbv_srv = $value;
	}

	public function get_event_srv()
	{
		return $this->event_srv;
	}

	public function set_event_srv($value)
	{
		$this->event_srv = $value;
	}

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

	public function get_paypal_srv()
	{
		return $this->paypal_srv;
	}

	public function set_paypal_srv($value)
	{
		$this->paypal_srv = $value;
	}

	public function get_price_srv()
	{
		return $this->price_srv;
	}

	public function set_price_srv($value)
	{
		$this->price_srv = $value;
	}

	public function get_bundle_srv()
	{
		return $this->bundle_srv;
	}

	public function set_bundle_srv($value)
	{
		$this->bundle_srv = $value;
	}

	public function get_valid()
	{
		return $this->valid;
	}

	public function set_valid($value)
	{
		$this->valid = $value;
	}

	public function get_http_info()
	{
		return $this->http_info;
	}

	public function set_http_info($value)
	{
		$this->http_info = $value;
	}

	public function get_so_srv()
	{
		return $this->so_srv;
	}

	public function set_so_srv($value)
	{
		$this->so_srv = $value;
	}

	public function get_tlog_dao()
	{
		return $this->tlog_dao;
	}

	public function set_tlog_dao(Base_dao $dao)
	{
		$this->tlog_dao = $dao;
	}

	public function get_iso_dao()
	{
		return $this->iso_dao;
	}

	public function set_iso_dao(Base_dao $value)
	{
		$this->iso_dao = $value;
	}

	public function get_isops_dao()
	{
		return $this->isops_dao;
	}

	public function set_isops_dao(Base_dao $value)
	{
		$this->isops_dao = $value;
	}

	public function get_ic_dao()
	{
		return $this->ic_dao;
	}

	public function set_ic_dao(Base_dao $dao)
	{
		$this->ic_dao = $dao;
	}

	public function get_isoi_dao()
	{
		return $this->isoi_dao;
	}

	public function set_isoi_dao(Base_dao $dao)
	{
		$this->isoi_dao = $dao;
	}

	public function get_isoid_dao()
	{
		return $this->isoid_dao;
	}

	public function set_isoid_dao(Base_dao $dao)
	{
		$this->isoid_dao = $dao;
	}

	public function get_pm_dao()
	{
		return $this->pm_dao;
	}

	public function set_pm_dao(Base_dao $dao)
	{
		$this->pm_dao = $dao;
	}

	public function get_xrate_dao()
	{
		return $this->xrate_dao;
	}

	public function set_xrate_dao(Base_dao $dao)
	{
		$this->xrate_dao = $dao;
	}

	public function get_so_dao()
	{
		return $this->so_dao;
	}

	public function set_so_dao(Base_dao $value)
	{
		$this->so_dao = $value;
	}

	public function get_sops_dao()
	{
		return $this->sops_dao;
	}

	public function set_sops_dao(Base_dao $value)
	{
		$this->sops_dao = $value;
	}

	public function get_client_dao()
	{
		return $this->client_dao;
	}

	public function set_client_dao(Base_dao $dao)
	{
		$this->client_dao = $dao;
	}

	public function get_soi_dao()
	{
		return $this->soi_dao;
	}

	public function set_soi_dao(Base_dao $dao)
	{
		$this->soi_dao = $dao;
	}

	public function get_soid_dao()
	{
		return $this->soid_dao;
	}

	public function set_soid_dao(Base_dao $dao)
	{
		$this->soid_dao = $dao;
	}

	public function get_product_srv()
	{
		return $this->product_srv;
	}

	public function set_product_srv($value)
	{
		$this->product_srv = $value;
	}

	public function get_ca_service()
	{
		return $this->ca_service;
	}

	public function set_ca_service($value)
	{
		$this->ca_service = $value;
	}

	public function get_sup_srv()
	{
		return $this->sup_srv;
	}

	public function set_sup_srv($value)
	{
		$this->sup_srv = $value;
	}

	public function get_ebay_order($ebay_account, $specified_file="")
	{
		$http_name = "ebay_".$ebay_account;
		$http_obj = $this->get_http_info()->get(array("name"=>$http_name, "type"=>"P"));
		if(!$http_obj)
		{
			$this->send_notification_email("HE");
		}
		else
		{
			include_once(BASEPATH."libraries/Encrypt.php");
			$encrypt = new CI_Encrypt();
			$api_url = $http_obj->get_server();
			$dev_key = $http_obj->get_username();
			$app_key = $http_obj->get_application_id();
			$cert_key = $encrypt->decode($http_obj->get_signature());
			$token = $http_obj->get_token();
			$last_time = $this->get_sj_srv()->get_last_process_time($http_name);
			if(!($last_time))
			{
				$this->send_notification_email("GT");
			}
			else
			{
				$now_time = date("YmdHis", mktime());
				if(strtotime($now_time) - strtotime($last_time) > 2592000)//exceed 30 days
				{
					$this->send_notification_email("TR");
				}
				else
				{
					$now_ebay_time = date("Y-m-d\TH:i:s.000\Z", strtotime($now_time));
					$last_ebay_time = date("Y-m-d\TH:i:s.000\Z", strtotime($last_time));
//$now_ebay_time = date("2012-07-24\TH:i:s.000\Z", strtotime($now_time));
//$last_ebay_time = date("2012-07-22\TH:i:s.000\Z", strtotime($now_time));
					$this->xml =
						'
<?xml version="1.0" encoding="utf-8"<GetSellerTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
	<RequesterCredentials>
		<eBayAuthToken>'.$token.'</eBayAuthToken>
	</RequesterCredentials>
	<ModTimeFrom>'.$last_ebay_time.'</ModTimeFrom>
	<ModTimeTo>'.$now_ebay_time.'</ModTimeTo>
	<IncludeFinalValueFee>true</IncludeFinalValueFee>
	<IncludeContainingOrder>true</IncludeContainingOrder>
	<Platform>eBay</Platform>
	<InventoryTrackingMethod>ItemID</InventoryTrackingMethod>
	<DetailLevel>ReturnAll</DetailLevel>
	<ErrorLanguage>en_GB</ErrorLanguage>
	<MessageID>get_ebay_order</MessageID>
	<Version>697</Version>
	<WarningLevel>Low</WarningLevel>
</GetSellerTransactionsRequest>
						';
mail("steven@eservicesgroup.net", "Ebay Retrieve Orders", "ebay_site: " . $ebay_site . "\nebay_account: " . $ebay_account . "\npostfields: ".$this->xml . "\n");

					$http = $this->get_http();
					$http->set_remote_site($api_url);
					$http->set_postfields($this->xml);
					$http->set_httpheader(array(
					"Content-Type: text/xml",
					"X-EBAY-API-COMPATIBILITY-LEVEL:697",
					"X-EBAY-API-DEV-NAME:".$dev_key,
					"X-EBAY-API-APP-NAME:".$app_key,
					"X-EBAY-API-CERT-NAME:".$cert_key,
					"X-EBAY-API-SITEID:0",
					"X-EBAY-API-CALL-NAME:GetSellerTransactions",
					"X-EBAY-API-REQUEST-ENCODING:XML"
					));
					if ($result = $http->get_content())
					{
						$filename = "ebay_".$ebay_account."_".$now_time.".xml";

						$amended_file = false;

						if(trim($specified_file))
						{
							$load_amended_file = true;
							$file_location = "/var/data/valuebasket.com/orders/ebay_orders/EBAY_FAILED_ORDER/".$specified_file;
						}
						else
						{
							$file_location = $this->get_config()->value_of("ebay_valuebasket_path").$ebay_account."/".$filename;
						}

						//if($fp = @fopen($this->get_config()->value_of("ebay_valuebasket_path").$ebay_account."/".$filename,'w'))
						if($load_amended_file)
						{
							//used the amended file
							$result = @file_get_contents($file_location);
						}
						elseif($fp = @fopen($file_location, 'w'))
						{
							@fwrite($fp,$result);
							@fclose($fp);
						}

						$xml = simplexml_load_string($result);
//						$xml = simplexml_load_file("/var/data/valuebasket.com/orders/ebay_orders/valuebasket_c/ebay_valuebasket_c_20130422231502.xml");
						/*
						header("Content-Type: text/xml");
						echo $result;
						exit;
						*/
						$error_msg = "";
						switch($xml->Ack)
						{
							case "Failure":
								foreach($xml->Errors AS $error)
								{
									$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
								}
								$this->send_notification_email("SE", $error_msg);
								break;
							case "Warning":
								foreach($xml->Errors AS $error)
								{
									$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
								}
								$this->send_notification_email("SW", $error_msg);
								break;
							case "Success":
								//echo $result;
								if($xml->ReturnedTransactionCountActual == 0)
								{
									$this->get_sj_srv()->update_last_process_time($http_name, $now_time);
								}
								else
								{
									$this->retrieve_eBay_order($ebay_account, $xml, $now_time);
								}
								break;
						}
					}
					else
					{
						$this->send_notification_email("CE");
					}
				}
			}
		}
	}

	public function retrieve_eBay_order($ebay_account, $xml, $time)
	{
		$batch_remark = "eBay_".$ebay_account."_".$time;
		$batch = $this->get_batch_srv()->get(array("remark"=>$batch_remark));
		if(empty($batch))
		{
			$batch_obj = $this->get_batch_srv()->get();
			$batch_obj->set_func_name("ebay_".$ebay_account);
			$batch_obj->set_status("N");
			$batch_obj->set_listed("1");
			$batch_obj->set_remark($batch_remark);
			$this->get_batch_srv()->insert($batch_obj);
		}
		else
		{
			$error_msg = "Batch_id: ".$batch->get_id()."\nRemark: ".$batch->get_remark();
			$this->send_notification_email("BE", $error_msg);
		}
		if($batch_obj)
		{
			$batch_id = $batch_obj->get_id();
			if($xml->TransactionArray)
			{
				$xcnt = 0;

				foreach($xml->TransactionArray->Transaction AS $transaction)
				{
					$batch_err = 0;
					if(isset($transaction->ExternalTransaction->ExternalTransactionID) && ((string)$transaction->Status->CompleteStatus =='Complete') && ((string)$transaction->Status->eBayPaymentStatus =='NoPaymentFailure') && ((string)$transaction->Status->CheckoutStatus =='CheckoutComplete'))
					{
						$pay_to_account = (string)$xml->Seller->UserID;
						$paypal_txn_id = (string)$transaction->ExternalTransaction->ExternalTransactionID;
						if (sizeof($transaction->ExternalTransaction) > 1)
						{
							$transactionPrice = (float) $transaction->TransactionPrice;
							$externalTransactions = $transaction->ExternalTransaction;

							foreach($externalTransactions AS $externalTransaction)
							{
								$paymentOrRefundAmount = (float) $externalTransaction->PaymentOrRefundAmount;
								if ($transactionPrice == $paymentOrRefundAmount)
									$paypal_txn_id = (string) $externalTransaction->ExternalTransactionID;
							}
							mail("oswald-alert@eservicesgroup.com", "[VB] eBay ExternalTransaction > 1", "\nebay_account: " . $ebay_account . "\nexternalTransactionID:" . $paypal_txn_id . "\nxml: " . $xml->asXML() . "\n");
						}
						$pay_to_email = (string)$transaction->PayPalEmailAddress;
						$pay_date = date("Y-m-d H:i:s",strtotime((string)$transaction->ExternalTransaction->ExternalTransactionTime));
						$ebay_txn_id = (string)$transaction->TransactionID;
						if(isset($transaction->ContainingOrder->OrderID))
						{
							$ebay_order_id = (string)$transaction->ContainingOrder->OrderID;
						}
						else
						{
							$ebay_order_id = '0';
						}
						$platform_order_id = $ebay_txn_id."-".$paypal_txn_id."-".$ebay_order_id;
						$name = (string)$transaction->Buyer->BuyerInfo->ShippingAddress->Name;
						$address_1 = (string)$transaction->Buyer->BuyerInfo->ShippingAddress->Street1;
						$address_2 = (string)$transaction->Buyer->BuyerInfo->ShippingAddress->Street2;
						$city = (string)$transaction->Buyer->BuyerInfo->ShippingAddress->CityName;
						$state = (string)$transaction->Buyer->BuyerInfo->ShippingAddress->StateOrProvince;
						$postcode = (string)$transaction->Buyer->BuyerInfo->ShippingAddress->PostalCode;
						$country_id = (string)$transaction->Buyer->BuyerInfo->ShippingAddress->Country;
						$amount = (float)$transaction->AmountPaid;
						$create_order_time = date("Y-m-d H:i:s",strtotime((string)$transaction->Status->LastTimeModified));
						$prod_sku = (string)$transaction->Item->SKU;
						$prod_name = (string)$transaction->Item->Title;
						$ext_item_cd = (string)$transaction->Item->ItemID;
						$qty = (int)$transaction->QuantityPurchased;
						$unit_price = (float)$transaction->Item->SellingStatus->CurrentPrice;
						$amount = $unit_price * $qty;
						$total_amount_paid = (float)$transaction->AmountPaid;
						$email = (string)$transaction->Buyer->Email;
						$ext_client_id = (string)$transaction->Buyer->UserID;
						$delivery_charge = (float)$transaction->ShippingDetails->ShippingServiceSelected->ShippingServiceCost;
						/*
						if(!$this->get_valid()->valid_email($email))
						{
							$batch_err_msg = "Invalid Email Address Received.";
							$batch_err = 1;
							break;
						}
						*/

						if($pm_obj = $this->get_pm_dao()->get(array("ext_system"=>"EBAY", "ext_mapping_key"=>(string)$transaction->Item->Site)))
						{
							$platform_id = $pm_obj->get_selling_platform();
							if($pbv_obj = $this->get_pbv_srv()->get(array("selling_platform_id"=>$platform_id)))
							{
								$platform_currency_id = $pbv_obj->get_platform_currency_id();
								$lang_id = $pbv_obj->get_language_id();
								$currency = (string)$transaction->Item->Currency;
								$courier = $pbv_obj->get_delivery_type();
								$vat_percent = $pbv_obj->get_vat_percent();
								if($platform_currency_id <> $currency)
								{
									$batch_err_msg = "Platform Currency NOT Match.\neBay Currency : ".$currency."\nPlatform Currency : ".$platform_currency_id."\n";
									$batch_err = 1;
									break;
								}
								$base_currency = $this->get_config()->value_of("func_curr_id");
								$rate_obj = $this->get_xrate_dao()->get(array("from_currency_id"=>$platform_currency_id, "to_currency_id"=>$base_currency));
								$rate = $rate_obj->get_rate();
								$ref_1_obj = $this->get_xrate_dao()->get(array("from_currency_id"=>$platform_currency_id, "to_currency_id"=>"EUR"));
								$ref_1 = $ref_1_obj->get_rate();
								$vat_total = number_format($vat_percent / 100 * $amount, 2, '.', '');
							}
						}
						else
						{
							continue;
							$batch_err_msg = "Platform Matching Error.\nReturn Item Site: ".(string)$transaction->Item->Site."\n";
							$batch_err = 1;
							break;
						}
						$iso_obj = $this->get_iso_dao()->get(array("batch_id"=>$batch_id, "txn_id"=>$paypal_txn_id));
						if(empty($iso_obj))
						{
							include_once(BASEPATH."libraries/Encrypt.php");
							$encrypt = new CI_Encrypt();

							//interface_client
							$ic_obj = $this->get_ic_dao()->get();

							$ic_obj->set_batch_id($batch_id);
							$ic_obj->set_ext_client_id($ext_client_id);
							$ic_obj->set_email($email);
							$ic_obj->set_password($encrypt->encode(time()+$xcnt++));

							$narr = explode(" ",trim($name));
							$narr2 = array();
							if($narr)
							{
								foreach($narr as $key=>$val)
								{
									if(!empty($val))
									{
										$narr2[] = $val;
									}
								}
							}
							$surname = $narr2[count($narr2) - 1];
							$forename = substr(trim($name),0,-strlen($surname));
							if($forename == "")
							{
								$forename = $surname;
								$surname = " ";
							}
							$ic_obj->set_forename($forename);
							$ic_obj->set_surname($surname);
							$ic_obj->set_address_1($address_1);
							$ic_obj->set_address_2($address_2);
							$ic_obj->set_postcode($postcode);
							$ic_obj->set_city($city);
							$ic_obj->set_state($state);
							$ic_obj->set_country_id($country_id);
							$ic_obj->set_del_name($name);
							$ic_obj->set_del_address_1($address_1);
							$ic_obj->set_del_address_2($address_2);
							$ic_obj->set_del_postcode($postcode);
							$ic_obj->set_del_city($city);
							$ic_obj->set_del_state($state);
							$ic_obj->set_del_country_id($country_id);
							$ic_obj->set_tel_3((string)$transaction->Buyer->BuyerInfo->ShippingAddress->Phone);
							$ic_obj->set_batch_status('N');
							$ic_ret = $this->get_ic_dao()->insert($ic_obj);
							if($ic_ret === FALSE)
							{
								$batch_err_msg = "Error Table: Interface_client\nError Msg: ".$this->get_ic_dao()->db->_error_message()."\nError SQL:".$this->get_ic_dao()->db->_error_message()."\n".$this->get_ic_dao()->db->last_query();
								$batch_err = 1;
								break;
							}
							$client_trans_id = $ic_ret->get_trans_id();

							//interface_so
							$iso_obj = $this->get_iso_dao()->get();
							$iso_obj->set_batch_id($batch_id);
							$iso_obj->set_platform_order_id($platform_order_id);
							$iso_obj->set_platform_id(strtoupper($platform_id));
							$iso_obj->set_txn_id($paypal_txn_id);
							$iso_obj->set_client_trans_id($client_trans_id);
							$iso_obj->set_biz_type('EBAY');
							$iso_obj->set_amount($total_amount_paid);
							$iso_obj->set_cost('0');
							$iso_obj->set_vat_percent($vat_percent);
							$iso_obj->set_rate($rate);
							$iso_obj->set_ref_1($ref_1);
							$iso_obj->set_delivery_charge($delivery_charge);
							$iso_obj->set_delivery_type_id($courier);
							$iso_obj->set_weight(1);
							$iso_obj->set_currency_id($currency);
							$iso_obj->set_lang_id($lang_id);
							$iso_obj->set_bill_name($name);
							$iso_obj->set_bill_address($address_1."|".$address_2);
							$iso_obj->set_bill_postcode($postcode);
							$iso_obj->set_bill_city($city);
							$iso_obj->set_bill_state($state);
							$iso_obj->set_bill_country_id($country_id);
							$iso_obj->set_delivery_name($name);
							$iso_obj->set_delivery_address($address_1."|".$address_2);
							$iso_obj->set_delivery_postcode($postcode);
							$iso_obj->set_delivery_city($city);
							$iso_obj->set_delivery_state($state);
							$iso_obj->set_delivery_country_id($country_id);
							$iso_obj->set_order_create_date($create_order_time);
							$iso_obj->set_status('1');
							$iso_obj->set_batch_status('N');
							$iso_ret = $this->get_iso_dao()->insert($iso_obj);
							if($iso_ret === FALSE)
							{
								$batch_err_msg = "Error Table: Interface_so\nError Msg: ".$this->get_iso_dao()->db->_error_message()."\nError SQL:".$this->get_iso_dao()->db->_error_message()."\n";
								$batch_err = 1;
								break;
							}

							$so_trans_id = $iso_ret->get_trans_id();
							$last_line_no = 1;
						}
						else
						{
							$so_trans_id = $iso_obj->get_trans_id();
							if($old_isoi_obj = $this->get_isoi_dao()->get_list(array("so_trans_id"=>$so_trans_id), array("limit"=>1, "orderby"=>"line_no DESC")))
							{
								$last_line_no = (int)$old_isoi_obj->get_line_no();
								$last_line_no++;
							}
						}
						//interface_so_item
						$isoi_obj = $this->get_isoi_dao()->get();
						$isoi_obj->set_batch_id($batch_id);
						$isoi_obj->set_so_trans_id($so_trans_id);
						$isoi_obj->set_line_no($last_line_no);
						$isoi_obj->set_prod_sku($prod_sku);
						$isoi_obj->set_prod_name($prod_name);
						$isoi_obj->set_ext_item_cd($ext_item_cd);
						$isoi_obj->set_qty($qty);
						$isoi_obj->set_unit_price($unit_price);
						$isoi_obj->set_vat_total($vat_total);
						$isoi_obj->set_amount($amount);
						$isoi_obj->set_status('0');
						$isoi_obj->set_batch_status('N');
						$isoi_ret = $this->get_isoi_dao()->insert($isoi_obj);
						if($isoi_ret === FALSE)
						{
							$batch_err_msg = "Error Table: Interface_so_item\nError Msg: ".$this->get_isoi_dao()->db->_error_message()."\nError SQL:".$this->get_isoi_dao()->db->last_query()."\n";
							$batch_err = 1;
							break;
						}
						else
						{
							#SBF #4324 - add mapped complementary accessories
							$ca_isoi_ret = $this->add_complementary_acc_isoi($batch_id, $so_trans_id, $last_line_no, $prod_sku, $qty, $country_id);

							if($ca_isoi_ret["status"] === FALSE)
							{
								$batch_err_msg = $ca_isoi_ret["error_msg"];
								$batch_err = 1;
								break;
							}
							else
							{
								$updated_line_no = $result["updated_line_no"];
							}
						}

						$cost = 0;
						$bundle_list = $this->get_bundle_srv()->get_list(array("prod_sku"=>$prod_sku), array("component_order"=>"ORDERBY component_order ASC", "array_list"=>1));
						if($bundle_list)
						{
							$updated_isoid_line_no = "";
							foreach($bundle_list AS $bundle_obj)
							{
								$prod_bundle_sku = $bundle_obj->get_component_sku();
								//interface_so_item_detail

								# if prod has any complementary acc mapped, then update the line_no
								if($updated_isoid_line_no !== "")
									$last_line_no = $updated_isoid_line_no;


								//NOT YET SET price in product
								$isoid_obj = $this->get_isoid_dao()->get();
								$isoid_obj->set_batch_id($batch_id);
								$isoid_obj->set_so_trans_id($so_trans_id);
								$isoid_obj->set_line_no($last_line_no);
								$isoid_obj->set_item_sku($prod_bundle_sku);
								$isoid_obj->set_qty($qty);
								$isoid_obj->set_outstanding_qty($qty);
								$isoid_obj->set_unit_price($unit_price);
								$isoid_obj->set_vat_total($vat_total);
								$isoid_obj->set_discount('0');
								$isoid_obj->set_amount('0');
								$isoid_obj->set_cost('0');
								$isoid_obj->set_profit('0');
								$isoid_obj->set_margin('0');
								$isoid_obj->set_status('0');
								$isoid_obj->set_batch_status('N');

								$this->update_cost_profit($isoid_obj, $iso_obj);
								$cost += $isoid_obj->get_cost();

								$isoid_ret = $this->get_isoid_dao()->insert($isoid_obj);
								if($isoid_ret === FALSE)
								{
									$batch_err_msg = "Error Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->_error_message()."\n";
									$batch_err = 1;
									break;
								}
								else
								{
									# add in complementary accessories
									$ca_isoid_ret = $this->add_complementary_acc_isoid($batch_id, $so_trans_id, $last_line_no, $prod_bundle_sku, $qty, $country_id);
									$updated_line_no = "";
									if($ca_isoid_ret["status"] === FALSE)
									{
										$batch_err_msg = $ca_isoid_ret["error_msg"];
										$batch_err = 1;
										break;
									}
									else
									{
										$updated_isoid_line_no = $result["updated_isoid_line_no"];
									}
								}
							}
						}
						else
						{
							//interface_so_item_detail
							$isoid_obj = $this->get_isoid_dao()->get();
							$isoid_obj->set_batch_id($batch_id);
							$isoid_obj->set_so_trans_id($so_trans_id);
							$isoid_obj->set_line_no($last_line_no);
							$isoid_obj->set_item_sku($prod_sku);
							$isoid_obj->set_qty($qty);
							$isoid_obj->set_outstanding_qty($qty);
							$isoid_obj->set_unit_price($unit_price);
							$isoid_obj->set_vat_total($vat_total);
							$isoid_obj->set_discount('0');
							$isoid_obj->set_amount($amount);
							$isoid_obj->set_status('0');
							$isoid_obj->set_batch_status('N');

							$this->update_cost_profit($isoid_obj, $iso_obj);
							$cost += $isoid_obj->get_cost();

							$isoid_ret = $this->get_isoid_dao()->insert($isoid_obj);
							if($isoid_ret === FALSE)
							{
								$batch_err_msg = "Error Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->_error_message()."\n";
								$batch_err = 1;
								break;
							}
						}

						$iso_obj->set_cost($iso_obj->get_cost()*1 + $cost);

						if ($this->get_iso_dao()->update($iso_obj) === FALSE)
						{
							$batch_err_msg = "Error Table: Interface_so\nError Msg: ".$this->get_iso_dao()->db->_error_message()."\nError SQL:".$this->get_iso_dao()->db->_error_message()."\n";
							$batch_err = 1;
							break;
						}
						else
						{
							# add in complementary accessories
							$ca_isoid_ret = $this->add_complementary_acc_isoid($batch_id, $iso_obj, $so_trans_id, $last_line_no, $prod_sku, $qty, $country_id);

							if($ca_isoid_ret["status"] === FALSE)
							{
								$batch_err_msg = $ca_isoid_ret["error_msg"];
								$batch_err = 1;
								break;
							}
						}

						if($updated_line_no)
							$last_line_no = $updated_line_no;

						$last_line_no++;
					}
					else
					{
						/*
						$batch_err_msg .= "Email: " . $transaction->Buyer->Email . "\n";
						if(!isset($transaction->ExternalTransaction->ExternalTransactionID))
						{
							$batch_err_msg .= "Missing ExternalTransactionID\n";
						}
						if((string)$transaction->Status->CompleteStatus =='Complete')
						{
							$batch_err_msg .= "Invalid CompleteStatus" . $transaction->Status->CompleteStatus . "\n";
						}
						if(((string)$transaction->Status->eBayPaymentStatus =='NoPaymentFailure'))
						{
							$batch_err_msg .= "Invalid eBayPaymentStatus" . $transaction->Status->eBayPaymentStatus . "\n";
						}
						if(((string)$transaction->Status->CheckoutStatus =='CheckoutComplete'))
						{
							$batch_err_msg .= "Invalid CheckoutStatus" . $transaction->Status->CheckoutStatus . "\n";
						}
						$batch_err = 1;
						*/
					}
				}
				if($batch_err)
				{
					//$batch_obj->set_status("BE");
					//$this->get_batch_srv()->update($batch_obj);
					$this->send_notification_email("BE", $batch_err_msg);
				}
				$this->retrieve_paypal_status($ebay_account, $batch_id, $pay_to_account, $pay_to_email);
				$this->proceed_ebay_batch($batch_id);
				// conver time back to GMT
				$lasthour  = mktime(date("H")-1, date("i"), date("s"), date("m"),   date("d"),   date("Y"));
				$gmttime = date("Y-m-d H:i:s", $lasthour);
				$this->get_sj_srv()->update_last_process_time("ebay_".$ebay_account, $gmttime);
			}
		}
	}

	private function add_complementary_acc_isoi($batch_id, $so_trans_id, $line_no, $prod_sku, $qty, $country_id)
	{
		#SBF #4324 - include mapped complementary accessories
		$last_line_no = $line_no + 1;
		$result = array();
		$result["updated_line_no"] = "";
		$result["status"] = TRUE;
		$result["error_msg"] = "ebay_service; so_trans_id <$so_trans_id>. Cannot add CA in interface_so_item for main SKU <$prod_sku> qty<$qty>.\n";

		$where["dest_country_id"] = $country_id;
		$where["mainprod_sku"] = $prod_sku;
		$mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true);

		if( ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true)) === FALSE)
		{
			$result["error_msg"] .= "Line ".__LINE__."Error Table: product_complementary_acc\nError Msg: ".$this->get_ca_service()->get_complementary_acc_dao()->db->_error_message()."\nError SQL:".$this->get_ca_service()->get_complementary_acc_dao()->db->last_query()."\n";
			$result["status"] = FALSE;
		}
		else
		{
			if($mapped_ca_list !== NULL)
			{
				foreach ($mapped_ca_list as $ca_obj)
				{
					//interface_so_item
					$result["updated_line_no"] = $last_line_no;
					$isoi_obj = $this->get_isoi_dao()->get();
					$isoi_obj->set_batch_id($batch_id);
					$isoi_obj->set_so_trans_id($so_trans_id);
					$isoi_obj->set_line_no($last_line_no);
					$isoi_obj->set_prod_sku($ca_obj->get_accessory_sku());
					$isoi_obj->set_prod_name($ca_obj->get_name());
					$isoi_obj->set_ext_item_cd("");
					$isoi_obj->set_qty($qty);
					$isoi_obj->set_unit_price(0);
					$isoi_obj->set_vat_total(0);
					$isoi_obj->set_amount(0);
					$isoi_obj->set_status('0');
					$isoi_obj->set_batch_status('N');
					$isoi_ret = $this->get_isoi_dao()->insert($isoi_obj);
					if($isoi_ret === FALSE)
					{
						$batch_err_msg .= "LINE ".__LINE__." CA_sku<{$ca_obj->get_accessory_sku()}>\nError Table: Interface_so_item\nError Msg: ".$this->get_isoi_dao()->db->_error_message()."\nError SQL:".$this->get_isoi_dao()->db->last_query()."\n";
						$result["status"] = FALSE;
					}

					$last_line_no++;
				}
			}
		}

		return $result;

	}

	private function add_complementary_acc_isoid($batch_id, $iso_obj, $so_trans_id, $line_no, $prod_sku, $qty, $country_id)
	{
		#SBF #4324 - include mapped complementary accessories
		$last_line_no = $line_no + 1;
		$result = array();
		$result["updated_isoid_line_no"] = "";
		$result["status"] = TRUE;
		$result["error_msg"] = "ebay_service; so_trans_id <$so_trans_id>. Cannot add CA in interface_so_item_detail for main SKU <$prod_sku> qty<$qty>.\n";

		$where["dest_country_id"] = $country_id;
		$where["mainprod_sku"] = $prod_sku;
		$mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true);

		if( ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true)) === FALSE)
		{
			$result["error_msg"] .= "Line ".__LINE__."Error Table: product_complementary_acc\nError Msg: ".$this->get_ca_service()->get_complementary_acc_dao()->db->_error_message()."\nError SQL:".$this->get_ca_service()->get_complementary_acc_dao()->db->last_query()."\n";
			$result["status"] = FALSE;
		}
		else
		{
			if($mapped_ca_list !== NULL)
			{
				foreach ($mapped_ca_list as $ca_obj)
				{

					$isoid_obj = $this->get_isoid_dao()->get();
					$isoid_obj->set_batch_id($batch_id);
					$isoid_obj->set_so_trans_id($so_trans_id);
					$isoid_obj->set_line_no($last_line_no);
					$isoid_obj->set_item_sku($ca_obj->get_accessory_sku());
					$isoid_obj->set_qty($qty);
					$isoid_obj->set_outstanding_qty($qty);
					$isoid_obj->set_unit_price(0);
					$isoid_obj->set_vat_total(0);
					$isoid_obj->set_discount('0');
					$isoid_obj->set_amount('0');
					$isoid_obj->set_cost('0');
					$isoid_obj->set_profit('0');
					$isoid_obj->set_margin('0');
					$isoid_obj->set_status('0');
					$isoid_obj->set_batch_status('N');

					# update cost as supplier cost
					$this->update_cost_profit($isoid_obj, $iso_obj, true);

					$isoid_ret = $this->get_isoid_dao()->insert($isoid_obj);
					if($isoid_ret === FALSE)
					{
						$result["error_msg"] .= "Line ".__LINE__." CA_sku<{$ca_obj->get_accessory_sku()}>\nError Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->_error_message()."\n";
					}

					$last_line_no++;
				}

				$result["updated_isoid_line_no"] = $last_line_no;
			}
		}

		return $result;

	}

	public function retrieve_paypal_status($ebay_account,$batch_id, $pay_to_account, $pay_to_email = "")
	{
		$paypal_name = "paypal_".$ebay_account;
		$paypal_http_info_obj = $this->get_http_info()->get(array("name"=>$paypal_name, "type"=>"P"));
		$pp_srv = $this->get_paypal_srv();
		$pp_srv->add_log = FALSE;
		$pp_srv->return_false = TRUE;
		$iso_list = $this->get_iso_dao()->get_list(array("batch_id"=>$batch_id));
		if($iso_list)
		{
			foreach($iso_list AS $iso_obj)
			{
				$txn_id = $iso_obj->get_txn_id();
				$so_trans_id = $iso_obj->get_trans_id();
				$rs = $pp_srv->get_transaction_details_api($txn_id, $paypal_http_info_obj);

				if(!$rs || $rs["ACK"] == "Failure")
				{
					// backup checking
					$paypal_http_info_obj2 = $this->get_http_info()->get(array("name"=>"paypal_valuebasket", "type"=>"P"));
					$rs = $pp_srv->get_transaction_details_api($txn_id, $paypal_http_info_obj2);
				}

				if(!$rs || $rs["ACK"] == "Failure")
				{
					$this->send_notification_email("PC");
				}
				else
				{
					//interface_so_payment_status
					$isops_obj = $this->get_isops_dao()->get();
					$isops_obj->set_batch_id($batch_id);
					$isops_obj->set_so_trans_id($so_trans_id);
					$isops_obj->set_payment_gateway_id('paypal');
					$isops_obj->set_remark("status:processed");
					$isops_obj->set_payment_status("S");
					$isops_obj->set_pay_to_account($pay_to_email);
					/*
					$pay_success = 0;
					if($rs["PAYMENTSTATUS"] == 'Completed')
					{
						$isops_obj->set_payment_status("S");
						$pay_success = 1;
					}
					else
					{
						$isops_obj->set_payment_status("F");
					}
					*/
					$isops_obj->set_pay_date(date("Y-m-d H:i:s", strtotime($rs["ORDERTIME"])));
					$isops_obj->set_payer_email($rs["EMAIL"]);
					$isops_obj->set_payer_ref($rs["PAYERID"]);
					$isops_obj->set_risk_ref1($rs["PROTECTIONELIGIBILITY"]);
					$isops_obj->set_risk_ref2($rs["PROTECTIONELIGIBILITYTYPE"]);
					$isops_obj->set_risk_ref3(isset($rs["ADDRESSSTATUS"])?$rs["ADDRESSSTATUS"]:$rs["PAYMENTREQUEST_0_ADDRESSSTATUS"]);
					$isops_obj->set_risk_ref4($rs["PAYERSTATUS"]);
					$isops_obj->set_batch_status('N');
					$isops_ret = $this->get_isops_dao()->insert($isops_obj);
					//credit check
					$need_cc = $isops_obj->get_risk_ref1() != "Eligible" && $isops_obj->get_risk_ref2() != "Eligible" && $iso_obj->get_amount()*$iso_obj->get_rate() > 200 && $isops_obj->get_risk_ref3() != "Confirmed";
					//$iso_obj->set_status($pay_success?($need_cc?2:3):1);
					$iso_obj->set_status($need_cc?2:3);
					$this->get_iso_dao()->update($iso_obj);
					if($isops_ret === FALSE)
					{
						$batch_err_msg = "Error Table: Interface_so_payment_status\nError Msg: ".$this->get_isops_dao()->db->_error_message()."\nError SQL:".$this->get_isops_dao()->db->_error_message()."\n";
						$this->send_notification_email("BP", $batch_err_msg);
					}
				}
			}
		}
	}

	public function proceed_ebay_batch($batch_id)
	{
		$so_dao = $this->get_so_dao();
		$c_dao = $this->get_client_dao();
		$soi_dao = $this->get_soi_dao();
		$soid_dao = $this->get_soid_dao();
		$sops_dao = $this->get_sops_dao();
		$soext_dao = $this->get_so_srv()->get_soext_dao();
		$iso_dao =$this->get_iso_dao();
		$ic_dao = $this->get_ic_dao();
		$isoi_dao = $this->get_isoi_dao();
		$isoid_dao = $this->get_isoid_dao();
		$isops_dao = $this->get_isops_dao();

		$iso_list = $iso_dao->get_list(array("batch_id"=>$batch_id), array());

		if($iso_list)
		{
			$batch_status = TRUE;

			$c_vo = $c_dao->get();
			$so_vo = $so_dao->get();
			$soi_vo = $soi_dao->get();
			$soid_vo = $soid_dao->get();
			$sops_vo = $sops_dao->get();
			$soext_vo = $soext_dao->get();

			foreach($iso_list AS $iso_obj)
			{
				$so_trans_id = $iso_obj->get_trans_id();
				$isoi_list = $isoi_dao->get_list(array("so_trans_id"=>$so_trans_id), array());

				$iso_batch_status = TRUE;
				$failed_reason = "";
				foreach($isoi_list AS $isoi_obj)
				{
					$sku = $isoi_obj->get_prod_sku();
					if(!$this->get_product_srv()->get(array("sku"=>$sku)))
					{
						$iso_batch_status = FALSE;
						$failed_reason = __LINE__. "Invalid SKU - ".$sku;
						break;
					}
				}

				//check if so record existed already
				$so_num = $this->get_so_dao()->get_num_rows(array("txn_id"=>$iso_obj->get_txn_id(), "platform_id"=>$iso_obj->get_platform_id()));
				if($so_num != 0)
				{
					$iso_batch_status = FALSE;
					$failed_reason = __LINE__. "Order already Existed.";
				}

				if ($iso_batch_status)
				{
					$ic_obj = $ic_dao->get(array("batch_id"=>$batch_id, "trans_id"=>$iso_obj->get_client_trans_id()));
					if(!$this->get_valid()->valid_email($ic_obj->get_email()))
					{
						$iso_batch_status = FALSE;
						$failed_reason = __LINE__. "Invalid Email";
						$errorMessage = "txn_id:" . $iso_obj->get_txn_id() . ", platform_id:" . $iso_obj->get_platform_id() . ", reason:" . $failed_reason;
						mail("bd.platformteam@eservicesgroup.net", "Ebay import error platform_id:" . $iso_obj->get_platform_id(), $errorMessage, "From: Admin <admin@valuebasket.com>\r\n");
					}
					else
					{
						//start_transaction
						$c_dao->trans_start();

						//client
						$c_obj = $c_dao->get(array("email"=>$ic_obj->get_email()));
						if($c_obj)
						{
							set_value($c_obj, $ic_obj);
							$c_ret = $c_dao->update($c_obj);
						}
						else
						{
							$c_obj = clone $c_vo;
							set_value($c_obj, $ic_obj);
							$c_ret = $c_dao->insert($c_obj);
						}
						if($c_ret !== FALSE)
						{
							$client_id = $c_obj->get_id();

							//update interface_client
							$ic_obj->set_id($client_id);
							if($ic_dao->update($ic_obj))
							{
								//so
								$so_obj = clone $so_vo;
								$seq = $so_dao->seq_next_val();
								$so_no = $seq;
								$so_dao->update_seq($seq);
								$iso_obj->set_so_no($so_no);
								$iso_obj->set_client_id($client_id);
								set_value($so_obj, $iso_obj);

								$so_obj_arr[] = array("so" => $so_obj, "result" => false);

								if($so_dao->insert($so_obj))
								{
									//so_payment_status
									$isops_obj = $isops_dao->get(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id));
									$isops_obj->set_so_no($so_no);
									$sops_obj = clone $sops_vo;
									set_value($sops_obj, $isops_obj);
									if($sops_dao->insert($sops_obj))
									{
										$isops_obj->set_batch_status('S');
										if($isops_dao->update($isops_obj))
										{
											//so_item
											if ($isoi_list = $isoi_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
											{
												foreach($isoi_list AS $isoi_obj)
												{
													$isoi_obj->set_so_no($so_no);
													$soi_obj = clone $soi_vo;
													set_value($soi_obj, $isoi_obj);
//set warranty and website status
													$prod_obj = $this->get_so_srv()->get_prod_srv()->get(array("sku" => $soi_obj->get_prod_sku()));
													$soi_obj->set_warranty_in_month($prod_obj->get_warranty_in_month());
													$soi_obj->set_website_status($prod_obj->get_website_status());

													if($soi_dao->insert($soi_obj))
													{
														$isoi_obj->set_batch_status('S');
														if (!$isoi_dao->update($isoi_obj))
														{
															$iso_batch_status = FALSE;
															$failed_reason = __LINE__. "Interface_so_item: ".$isoi_dao->db->_error_message();
															break;
														}
													}
													else
													{
														$iso_batch_status = FALSE;
														$failed_reason = __LINE__. "so_item: ".$soi_dao->db->_error_message();
														break;
													}
												}
											}
											if($iso_batch_status)
											{
												//so_item_detail
												if ($isoid_list = $isoid_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
												{
													foreach($isoid_list AS $isoid_obj)
													{
														$isoid_obj->set_so_no($so_no);
														$soid_obj = clone $soid_vo;
														set_value($soid_obj, $isoid_obj);
														$soid_obj->set_item_unit_cost($this->get_sup_srv()->get_item_cost_in_hkd($isoid_obj->get_item_sku()));
														if($soid_dao->insert($soid_obj))
														{
															$isoid_obj->set_batch_status('S');
															if ($isoid_dao->update($isoid_obj))
															{
																//update website quantity
																$this->get_so_srv()->update_website_display_qty($so_obj);
															}
															else
															{
																$iso_batch_status = FALSE;
																$failed_reason = __LINE__. "Interface_so_item_detail: ".$isoid_dao->db->_error_message();
																break;
															}
														}
														else
														{
															$iso_batch_status = FALSE;
															$failed_reason = __LINE__. "so_item_detail: ".$soid_dao->db->_error_message();
															break;
														}
													}
												}
											}
											if($iso_batch_status)
											{
												$soext_obj = clone $soext_vo;
												$soext_obj->set_so_no($so_no);
												$soext_obj->set_acked("N");
												$soext_obj->set_fulfilled("N");
												$entity_id = $this->get_so_srv()->get_entity_srv()->get_entity_id($so_obj->get_amount(), $so_obj->get_currency_id());
												$soext_obj->set_entity_id($entity_id);

												if(!$soext_dao->insert($soext_obj))
												{
													$iso_batch_status = FALSE;
													$failed_reason = __LINE__. "so_extend: ".$soext_dao->db->_error_message();
													break;
												}
											}
										}
										else
										{
											$iso_batch_status = FALSE;
											$failed_reason = __LINE__. "Interface_so_payment_status: ".$isops_dao->db->_error_message();
										}
									}
									else
									{
										$iso_batch_status = FALSE;
										$failed_reason = __LINE__. "so_payment_status: ".$isops_dao->db->_error_message();
									}
								}
								else
								{
									$iso_batch_status = FALSE;
									$failed_reason = __LINE__. "so: " . $so_dao->db->_error_message() . ", " . $so_dao->db->last_query();
								}
							}
							else
							{
								$iso_batch_status = FALSE;
								$failed_reason = __LINE__. "Interface_client: ".$ic_dao->db->_error_message();
							}
						}
						else
						{
							$iso_batch_status = FALSE;
							$failed_reason = __LINE__. "client: ".$c_dao->db->_error_message();
						}
						if($iso_batch_status == FALSE)
						{
							$c_dao->trans_rollback();
						}
						else
						{
//update interface_so
							$iso_obj->set_batch_status('S');
							$iso_dao->update($iso_obj);
							if (isset($so_obj_arr))
							{
								$stored_so_obj = $so_obj_arr[sizeof($so_obj_arr) - 1];
								if ($iso_obj->get_so_no() == $stored_so_obj["so"]->get_so_no())
								{
									$so_obj_arr[sizeof($so_obj_arr) - 1]["result"] = true;
								}
							}
						}
						$c_dao->trans_complete();
					}
				}

				if($iso_batch_status == FALSE)
				{
					$iso_obj->set_batch_status('F');
					$iso_obj->set_failed_reason($failed_reason);
					$iso_obj->set_so_no(NULL);
					$iso_obj->set_client_id(NULL);
					$iso_dao->update($iso_obj);
					$batch_status = FALSE;
					if (strstr($failed_reason, "Order already") === FALSE)
					{
						$alertEmailList = "oswald-alert@eservicesgroup.com,handy.hon@eservicesgroup.com";
						$email_message = $failed_reason . ", Possible reprocess URL:http://admincentre.valuebasket.com/integration/integration/reprocess_ebay/" . $batch_id . "/" . $so_trans_id;
						if (strstr($failed_reason, "Invalid Email"))
						{
							$alertEmailList .= ",perry.leung@eservicesgroup.com,celine@eservicesgroup.com";
							$email_message .= ", order transaction_id=" . $iso_obj->get_txn_id() . ", bill name:" . $iso_obj->get_bill_name();
						}

						mail($alertEmailList, "[VB] Ebay order import Error", $email_message, "From: admin@valuebasket.com");
					}
				}
			}

			$batch_obj = $this->get_batch_srv()->get(array("id"=>$batch_id));
			if($batch_status)
			{
				$batch_obj->set_status("C");
			}
			else
			{
				$batch_obj->set_status("CE");
			}
			foreach($so_obj_arr as $so_obj)
			{
				if ($so_obj["result"])
				{
					$this->so = $so_obj["so"];
					$this->fire_success_event();
				}
			}
			$batch_obj->set_end_time(date("Y-m-d H:i:s"));

			if (!$this->get_batch_srv()->update($batch_obj))
			{
				$content = "Batch update error\nBatch_id:".$batch_id."\nError Message:".$this->get_batch_srv()->get_dao()->db->_error_message();
				$this->send_notification_email("BP", $content);
			}
		}
	}

	public function reprocess_ebay($batch_id, $so_trans_id)
	{
		$so_dao = $this->get_so_dao();
		$c_dao = $this->get_client_dao();
		$soi_dao = $this->get_soi_dao();
		$soid_dao = $this->get_soid_dao();
		$sops_dao = $this->get_sops_dao();
		$soext_dao = $this->get_so_srv()->get_soext_dao();
		$iso_dao =$this->get_iso_dao();
		$ic_dao = $this->get_ic_dao();
		$isoi_dao = $this->get_isoi_dao();
		$isoid_dao = $this->get_isoid_dao();
		$isops_dao = $this->get_isops_dao();

		$iso_obj = $iso_dao->get(array("batch_id"=>$batch_id, "trans_id"=>$so_trans_id, "batch_status"=>"F"));

		if($iso_obj)
		{
			$batch_status = TRUE;

			$c_vo = $c_dao->get();
			$so_vo = $so_dao->get();
			$soi_vo = $soi_dao->get();
			$soid_vo = $soid_dao->get();
			$sops_vo = $sops_dao->get();
			$soext_vo = $soext_dao->get();

			$isoi_list = $isoi_dao->get_list(array("so_trans_id"=>$so_trans_id), array());

			$iso_batch_status = TRUE;
			$failed_reason = "";
			foreach($isoi_list AS $isoi_obj)
			{
				$sku = $isoi_obj->get_prod_sku();
				if(!$this->get_product_srv()->get(array("sku"=>$sku)))
				{
					$iso_batch_status = FALSE;
					$failed_reason = __LINE__. "Invalid SKU - ".$sku;
					break;
				}
			}

			//check if so record existed already
			$so_num = $this->get_so_dao()->get_num_rows(array("platform_order_id"=>$iso_obj->get_platform_order_id(), "platform_id"=>$iso_obj->get_platform_id()));

			if($so_num != 0)
			{
				$iso_batch_status = FALSE;
				$failed_reason = __LINE__. "Order already Existed.";
				var_dump($failed_reason);
			}

			if ($iso_batch_status)
			{
				$ic_obj = $ic_dao->get(array("batch_id"=>$batch_id, "trans_id"=>$iso_obj->get_client_trans_id()));
				if(!$this->get_valid()->valid_email($ic_obj->get_email()))
				{
					$iso_batch_status = FALSE;
					$failed_reason = __LINE__. "Invalid Email";
					var_dump($failed_reason);
				}
				else
				{
					//start_transaction
					$c_dao->trans_start();

					//client
					$c_obj = $c_dao->get(array("email"=>$ic_obj->get_email()));
					if($c_obj)
					{
						set_value($c_obj, $ic_obj);
						$c_ret = $c_dao->update($c_obj);
					}
					else
					{
						$c_obj = clone $c_vo;
						set_value($c_obj, $ic_obj);
						$c_ret = $c_dao->insert($c_obj);
					}
					if($c_ret !== FALSE)
					{
						$client_id = $c_obj->get_id();

						//update interface_client
						$ic_obj->set_id($client_id);
						if($ic_dao->update($ic_obj))
						{
							//so
							$so_obj = clone $so_vo;
							$seq = $so_dao->seq_next_val();
							$so_no = sprintf("%06d",$seq);
							$so_dao->update_seq($seq);
							$iso_obj->set_so_no($so_no);
							$iso_obj->set_client_id($client_id);
							set_value($so_obj, $iso_obj);

							$so_obj_arr[] = $so_obj;

							if($so_dao->insert($so_obj))
							{
								//so_payment_status
								$isops_obj = $isops_dao->get(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id));
								$isops_obj->set_so_no($so_no);
								$sops_obj = clone $sops_vo;
								set_value($sops_obj, $isops_obj);
								if($sops_dao->insert($sops_obj))
								{
									$isops_obj->set_batch_status('S');
									if($isops_dao->update($isops_obj))
									{
										//so_item
										if ($isoi_list = $isoi_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
										{
											foreach($isoi_list AS $isoi_obj)
											{
												$isoi_obj->set_so_no($so_no);
												$soi_obj = clone $soi_vo;
												set_value($soi_obj, $isoi_obj);
//set warranty and website status
												$prod_obj = $this->get_so_srv()->get_prod_srv()->get(array("sku" => $soi_obj->get_prod_sku()));
												$soi_obj->set_warranty_in_month($prod_obj->get_warranty_in_month());
												$soi_obj->set_website_status($prod_obj->get_website_status());

												if($soi_dao->insert($soi_obj))
												{
													$isoi_obj->set_batch_status('I');
													if (!$isoi_dao->update($isoi_obj))
													{
														$iso_batch_status = FALSE;
														$failed_reason = __LINE__. "Interface_so_item: ".$isoi_dao->db->_error_message();
														var_dump($failed_reason);
														break;
													}
												}
												else
												{
													$iso_batch_status = FALSE;
													$failed_reason = __LINE__. "so_item: ".$soi_dao->db->_error_message();
													var_dump($failed_reason);
													break;
												}
											}
										}
										if($iso_batch_status)
										{
											//so_item_detail
											if ($isoid_list = $isoid_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
											{
												foreach($isoid_list AS $isoid_obj)
												{
													$isoid_obj->set_so_no($so_no);
													$soid_obj = clone $soid_vo;
													set_value($soid_obj, $isoid_obj);
													$soid_obj->set_item_unit_cost($this->get_sup_srv()->get_item_cost_in_hkd($isoid_obj->get_item_sku()));
													if($soid_dao->insert($soid_obj))
													{
														$isoid_obj->set_batch_status('I');
														if ($isoid_dao->update($isoid_obj))
														{
															//update website quantity
															$this->get_so_srv()->update_website_display_qty($so_obj);
														}
														else
														{
															$iso_batch_status = FALSE;
															$failed_reason = __LINE__. "Interface_so_item_detail: ".$isoid_dao->db->_error_message();
															var_dump($failed_reason);
															break;
														}
													}
													else
													{
														$iso_batch_status = FALSE;
														$failed_reason = __LINE__. "so_item_detail: ".$soid_dao->db->_error_message();
														var_dump($failed_reason);
														break;
													}
												}
											}
										}
										if($iso_batch_status)
										{
											$soext_obj = clone $soext_vo;
											$soext_obj->set_so_no($so_no);
											$soext_obj->set_acked("N");
											$soext_obj->set_fulfilled("N");
											$entity_id = $this->get_so_srv()->get_entity_srv()->get_entity_id($so_obj->get_amount(), $so_obj->get_currency_id());
											$soext_obj->set_entity_id($entity_id);
											if(!$soext_dao->insert($soext_obj))
											{
												$iso_batch_status = FALSE;
												$failed_reason = __LINE__. "so_extend: ".$soext_dao->db->_error_message();
												break;
											}
										}
									}
									else
									{
										$iso_batch_status = FALSE;
										$failed_reason = __LINE__. "Interface_so_payment_status: ".$isops_dao->db->_error_message();
										var_dump($failed_reason);
									}
								}
								else
								{
									$iso_batch_status = FALSE;
									$failed_reason = __LINE__. "so_payment_status: ".$isops_dao->db->_error_message();
									var_dump($failed_reason);
								}
							}
							else
							{
								$iso_batch_status = FALSE;
								$failed_reason = __LINE__. "so: ".$so_dao->db->_error_message();
								var_dump($failed_reason);
							}
						}
						else
						{
							$iso_batch_status = FALSE;
							$failed_reason = __LINE__. "Interface_client: ".$ic_dao->db->_error_message();
							var_dump($failed_reason);
						}
					}
					else
					{
						$iso_batch_status = FALSE;
						$failed_reason = __LINE__. "client: ".$c_dao->db->_error_message();
						var_dump($failed_reason);
					}
					if($iso_batch_status == FALSE)
					{
						$c_dao->trans_rollback();
					}
					else
					{
						//update interface_so
						$iso_obj->set_batch_status('I');
						$iso_dao->update($iso_obj);
					}
					$c_dao->trans_complete();
				}
			}

			if($iso_batch_status === FALSE)
			{
				$iso_obj->set_batch_status('F');
				$iso_obj->set_failed_reason($failed_reason);
				$iso_obj->set_so_no(NULL);
				$iso_obj->set_client_id(NULL);
				$iso_dao->update($iso_obj);
				$batch_status = FALSE;
			}
			else
			{
				var_dump("SO created with so_no:".$so_obj->get_so_no());
			}


			$batch_obj = $this->get_batch_srv()->get(array("id"=>$batch_id));
			if($batch_status)
			{
				$batch_obj->set_status("RP");
			}
			else
			{
				$batch_obj->set_status("CE");
			}
			$batch_obj->set_end_time(date("Y-m-d H:i:s"));

			if (!$this->get_batch_srv()->update($batch_obj))
			{
				$content = "Batch update error\nBatch_id:".$batch_id."\nError Message:".$this->get_batch_srv()->get_dao()->db->_error_message();
				//$this->send_notification_email("BP", $content);
			}
		}
		else
		{
			var_dump(__LINE__."::Requirement not match: ".$iso_dao->db->last_query());
		}
	}

	public function send_notification_email($pending_action, $content="")
	{
		switch ($pending_action)
		{
			case "HE":
				$message = "Please note that http_info cannot be retrieved. Please check.";
				$title = "[VB] Retrieve eBay Order problems - HTTP_INFO_ERROR";
				break;
			case "GT":
				$message = "Please note that last_process_on field in cron table has problems. Please check.";
				$title = "[VB] Retrieve eBay Order problems - GET_CRON_TIME";
				break;
			case "CE":
				$message = "Please note that the time range is set to 30 days.";
				$title = "[VB] Retrieve eBay Order problems - CONNECT_ERROR";
				break;
			case "TR":
				$message = "Please note that the time range is set to 30 days.";
				$title = "[VB] Retrieve eBay Order problems - TIME_RANGE";
				break;
			case "SE":
				$message = $content;
				$title = "[VB] Retrieve eBay Order problems - SUCCESS_WITH_ERROR";
				break;
			case "SW":
				$message = $content;
				$title = "[VB] Retrieve eBay Order problems - SUCCESS_WITH_WARNING";
				break;
			case "BP":
				$message = $content;
				$title = "[VB] Retrieve eBay Order problems - BATCH_PROBLEM";
				break;
			case "PC":
				$message = "Please note that paypal_api cannot connect. Please check.";
				$title = "[VB] Retrieve eBay Order problems - PAYPAL_CONNECTION";
				break;
			case "BE":
				$message = $content;
				$title = "[VB] Retrieve eBay Order problems - BATCH_ERROR";
				break;
			case "BP":
				$message = $content;
				$title = "[VB] Retrieve eBay Order problems - BATCH_UPDATE_ERROR";
				break;
		}
		mail($this->notification_email, $title, $message);
		//exit;
	}

	public function update_cost_profit($soid_obj, $so_obj, $is_ca = false)
	{
		$price_srv = $this->get_price_srv();

		if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku"=>$soid_obj->get_item_sku(), "platform_id"=>$so_obj->get_platform_id()), array("limit"=>1, "skip_prod_status_checking" => 1)))
		{
			if($is_ca === false)
			{
				# complementary acc cost already included in pricing tool
				$prod_obj->set_price($soid_obj->get_unit_price());
				$price_srv->calc_logistic_cost($prod_obj);
				$price_srv->calc_cost($prod_obj);
				$soid_obj->set_cost($prod_obj->get_cost() * $soid_obj->get_qty());
				$this->get_so_srv()->set_profit_info($soid_obj);
				$this->get_so_srv()->set_profit_info_raw($soid_obj, $so_obj->get_platform_id());
			}
			else
			{
				# if complementary acc, just set cost = supplier cost
				$soid_obj->set_cost($prod_obj->get_supplier_cost() * $soid_obj->get_qty());
			}
		}
		else
		{
			$soid_obj->set_cost('0');
			$soid_obj->set_profit('0');
			$soid_obj->set_margin('0');
		}
	}

	public function revise_item($platform_id, $sku)
	{
		$this->log_config = array("type"=>"product", "platform_id"=>$platform_id, "call_name"=>"revise_item");
		$this->init_account($platform_id);

		if($this->enable_log)
		{
			echo date("Y-m-d H:i:s") . " - Initialized " . $this->ebay_site . " Account (" . $this->ebay_account . ")<br>";
		}

		if($this->ebay_account)
		{

			$call_name = "ReviseItem";
			$where = array("platform_id"=>$platform_id, "sku"=>$sku, "ext_item_id IS NOT NULL"=>NULL);
			if ($obj = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("limit"=>1)))
			{
				$this->revise_item = $obj;
				if($this->enable_log)
				{
					echo date("Y-m-d H:i:s") . " - Calling ReviseItem API<br>";
				}

				$this->reviseitem_api();
				if ($rs = $this->connect($call_name, $obj->get_platform_country_id()))
				{
					return $this->response_xml($call_name, $rs);
				}
				else
				{
					mail($this->notification_email, "[VB]". ($this->debug ? "[Debug]":"" ) . " Connection Error @".__LINE__, "[VB] Connection Error @".__FILE__ . " : " . __LINE__);
				}
			}
		}

		return false;
	}

	public function end_item($platform_id, $sku)
	{
		$this->log_config = array("type"=>"product", "platform_id"=>$platform_id, "call_name"=>"end_item");
		$this->init_account($platform_id);

		if($this->enable_log)
		{
			echo date("Y-m-d H:i:s") . " - Initialized " . $this->ebay_site . " Account (" . $this->ebay_account . ")<br>";
		}

		if($this->ebay_account)
		{

			$call_name = "EndItem";
			$where = array("platform_id"=>$platform_id, "sku"=>$sku, "ext_item_id IS NOT NULL"=>NULL);
			if ($obj = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("limit"=>1)))
			{
				$this->end_item = $obj;
				if($this->enable_log)
				{
					echo date("Y-m-d H:i:s") . " - Calling EndItem API<br>";
				}

				$this->enditem_api();
				if ($rs = $this->connect($call_name, $obj->get_platform_country_id()))
				{
					return $this->response_xml($call_name, $rs);
				}
				else
				{
					mail($this->notification_email, "[VB]". ($this->debug ? "[Debug]":"" ) . " Connection Error @".__LINE__, "[VB] Connection Error @".__FILE__ . " : " . __LINE__);
				}
			}
		}

		return false;
	}

	public function update_shipment_status($platform_id, $debug = 0, $enable_log = 0)
	{
		$this->debug = $debug;
		$this->enable_log = $enable_log;
		$this->log_config = array("type"=>"orders", "platform_id"=>$platform_id, "call_name"=>"complete_sale");
		$this->init_account($platform_id);

		if($this->enable_log)
		{
			echo date("Y-m-d H:i:s") . " - Initialized " . $this->ebay_site . " Account (" . $this->ebay_account . ")<br>";
		}

		if ($this->ebay_account)
		{
			$call_name = "CompleteSale";

			if($this->enable_log)
			{
				echo date("Y-m-d H:i:s") . " - Calling CompleteSale API<br>";
			}

			if ($obj_list = $this->get_so_srv()->get_ebay_pending_shipment_update_orders(array("so.platform_id"=>$platform_id)))
			{
				foreach($obj_list as $obj)
				{
					$this->complete_sale_api($obj);

					if ($rs = $this->connect($call_name, $obj_list[0]->get_platform_country_id()))
					{
						$result = $this->response_xml($call_name, $rs);
						if($result["response"] === 0)
						{
							$err_msg = $result["message"];
							mail($this->notification_email, "[VB]". ($this->debug ? "[Debug]":"" ) . " Connection Error @".__LINE__, "so_no: ".$obj->get_so_no()."\n".$err_msg);
						}
						else
						{
							$soext_dao = $this->get_so_srv()->get_soext_dao();
							if($soext_obj = $soext_dao->get(array("so_no"=>$obj->get_so_no())))
							{
								$soext_obj->set_fulfilled("Y");
								if(!$ret = $soext_dao->update($soext_obj))
								{
									mail($this->notification_email, "[VB]". ($this->debug ? "[Debug]":"" ) . " Failed to Update so_extend After API Completed Successfully @".__LINE__, "so_no: ".$obj->get_so_no());
								}
							}
						}
					}
					else
					{
						mail($this->notification_email, "[VB]". ($this->debug ? "[Debug]":"" ) . " Connection Error @".__LINE__, "[VB] Connection Error @".__FILE__ . " : " . __LINE__);
					}
				}
			}

			return $err_msg;
		}
	}

	public function add_items($platform_id, $debug = 0, $enable_log = 0)
	{
		$this->debug = $debug;
		$this->enable_log = $enable_log;
		$this->log_config = array("type"=>"product", "platform_id"=>$platform_id, "call_name"=>"add_items");
		$this->init_account($platform_id);

		if($this->enable_log)
		{
			echo date("Y-m-d H:i:s") . " - Initialized " . $this->ebay_site . " Account (" . $this->ebay_account . ")<br>";
		}

		if ($this->ebay_account)
		{
			$call_name = "AddItems";
			$where = array("platform_id"=>$platform_id, "website_quantity > "=>0, "prod_status"=>2, "listing_status"=>'L', "ext_item_id IS NULL"=>NULL, "ext_ref_1 IS NOT NULL"=>NULL);
			if ($total = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("num_rows"=>1)))
			{
				$limit = 5;
				$total_loops = ceil($total / $limit);
				$err_msg = "";

				for ($i = 0; $i<$total_loops; $i++)
				{
					$offset = $i * $limit;

					if ($obj_list = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("limit"=>$limit, "offset"=>$offset, "array_list"=>1)))
					{
						$this->add_item_list = $obj_list;

						if($this->enable_log)
						{
							echo date("Y-m-d H:i:s") . " - Calling AddItems API<br>";
						}

						$this->additems_api();
						if ($rs = $this->connect($call_name, $obj_list[0]->get_platform_country_id()))
						{
							$err_msg .= $this->response_xml($call_name, $rs);
						}
						else
						{
							mail($this->notification_email, "[VB]". ($this->debug ? "[Debug]":"" ) . " Connection Error @".__LINE__, "[VB] Connection Error @".__FILE__ . " : " . __LINE__);
						}
					}
				}
				return $err_msg;
			}
		}
	}

	public function init_account($platform_id)
	{
		if ($pm_obj = $this->get_pm_dao()->get(array("selling_platform"=>$platform_id)))
		{
			$this->ebay_account = $pm_obj->get_account();
			$this->ebay_site = $pm_obj->get_ext_mapping_key();
		}
	}

	public function init_http_obj()
	{
		$this->http_obj = $this->get_http_info()->get(array("name"=>$this->ebay_account, "type"=>$this->debug ? "D" : "P"));
	}

	public function connect($call_name, $country_id = "US")
	{
		$this->add_log("request", $this->xml);
		$this->init_http_obj();

		include_once(BASEPATH."libraries/Encrypt.php");
		$encrypt = new CI_Encrypt();
		$cert_key = $encrypt->decode($this->http_obj->get_signature());

		$site_id = 0;

		if ($country_id != "US")
		{
			$site_id = $this->get_site_id($country_id);
		}

		$http = $this->get_http();
		$http->set_remote_site($this->http_obj->get_server());
		$http->set_postfields($this->xml);
		$http->set_httpheader(array(
		"Content-Type: text/xml;charset=UTF-8",
		"X-EBAY-API-COMPATIBILITY-LEVEL:715",
		"X-EBAY-API-DEV-NAME:".$this->http_obj->get_username(),
		"X-EBAY-API-APP-NAME:".$this->http_obj->get_application_id(),
		"X-EBAY-API-CERT-NAME:".$cert_key,
		"X-EBAY-API-SITEID:".$site_id,
		"X-EBAY-API-CALL-NAME:{$call_name}",
		"X-EBAY-API-REQUEST-ENCODING:XML"
		));
		if ($rs = $http->get_content())
		{
			$this->add_log("response", $rs);
			return $rs;
		}
		else
		{
			mail($this->notification_email, "[VB]". ($this->debug ? "[Debug]":"" ) . " Connection Error @".__LINE__, "[VB] Connection Error @".__FILE__ . " : " . __LINE__);
		}
	}

	public function add_log($connection_type, $content)
	{
		switch ($this->log_config["type"])
		{
			case "product":
			case "orders":
				$data_path = $this->get_config()->value_of("data_path");
				$prod_path = $data_path.$this->log_config["type"];
				$this->check_path($prod_path);

				$platform_path = $prod_path."/".$this->log_config["platform_id"];
				$this->check_path($platform_path);

				$feed_path = $platform_path."/".$this->log_config["call_name"];
				$this->check_path($feed_path);

				$file_path = $feed_path."/".$connection_type;
				$this->check_path($file_path);

				$filename = $file_path."/".date("YmdHis").".xml";

				break;
		}
		file_put_contents($filename, $content);

	}

	public function check_path($path)
	{
		if (!is_dir($path))
		{
			mkdir($path);
		}
	}

	public function item_description($item_obj)
	{
		$replace["title"] = $item_obj->get_title();
		$replace["image_url"] = "http://www.valuebasket.com/images/product/{$item_obj->get_sku()}.{$item_obj->get_image()}";
		$desc_content = $replace["youtube_obj"] = $replace["description"] = $replace["nutshell"] = $replace["features"] = $replace["specifications"] = $replace["requirements"] = $replace["contents"] = "";
		if ($pc_obj = $this->get_product_srv()->get_content($item_obj->get_sku(), $item_obj->get_language_id()))
		{
			//echo $pc_obj = $this->get_product_srv()->get_dao()->db->last_query();
			//$replace["description"] = nl2br($pc_obj->get_short_desc() . $pc_obj->get_detail_desc() . $pc_obj->get_feature() . $pc_obj->get_specification() . $pc_obj->get_requirement());
			$replace["features"] = $this->isHtml($pc_obj->get_feature()) ? $pc_obj->get_feature() : nl2br($pc_obj->get_feature());
			$replace["specifications"] = $this->isHtml($pc_obj->get_specification()) ? $pc_obj->get_specification() : nl2br($pc_obj->get_specification());
			$replace["requirements"] = nl2br($pc_obj->get_requirement());
			$replace["description"] = nl2br($pc_obj->get_detail_desc());
			$replace["nutshell"] = nl2br($pc_obj->get_short_desc());

			//$replace["contents"] = nl2br($pc_obj->get_contents());
			//#2584 automatically add the SHIPPING information if product price < 100USD

			$data_in_the_box = '';

			if($pc_obj->get_contents() != "")
			{
				$str = explode("\n",$pc_obj->get_contents());

				foreach($str as $k=>$v)
				{
					$v = trim($v);
					if(empty($v))
					{
						unset($str[$k]);
					}
					if(preg_match('/Hong Kong Post services.*Tracking Number/i', $v))
					{
						unset($str[$k]);
					}

					if(preg_match('/Once your order.*dispatch date/i', $v))
					{
						unset($str[$k]);
					}
				}

				//check the price
				$currency_id = $item_obj->get_platform_currency_id();
				$price_in_original = $item_obj->get_price();
				if($xrate = $this->get_xrate_dao()->get(array('from_currency_id'=>$currency_id, 'to_currency_id'=>'USD')))
				{
					$price_in_USD = $price_in_original * $xrate->get_rate();
					if($price_in_USD < 100)
					{
						//EN version only
						$str[] = '*Please note ValueBasket offers FREE shipping for this item, which will be shipped using Hong Kong Post services. Do note this type of service usually takes 10-21 days for delivery, with Tracking Number being available.';
						$str[] = 'Once your order has been dispatched, ValueBasket will send you an email with your dispatch date.';
					}
				}
				$data_in_the_box = implode('<br /><br />', $str);
			}

			$replace["contents"] = $data_in_the_box;

			if ($item_obj->get_title() == "")
			{
				$item_obj->set_title($pc_obj->get_prod_name());
			}
		}

		if ($youtube_id = $item_obj->get_youtube_id())
		{
			$replace["youtube_obj"] = '<object width="300" height="200"><param name="movie" value="http://www.youtube.com/v/'.$youtube_id.'?fs=1&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$youtube_id.'?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="200"></embed></object><br /><br />';
		}

		foreach ($replace as $rskey=>$rsvalue)
		{
			$search[] = "[:".$rskey.":]";
			$value[] = $rsvalue;
		}

		if ($item_obj->get_platform_country_id() == "US")
		{
			$search[] = "[:delivery_shipping_ans_text:]";
			if ($item_obj->get_price() >= 100)
			{
				$value[] = "The package will ship by courier service; delivery usually takes between 7 to 10 days with Tracking Number being available. Please note for some countries there may be unexpected delays in clearing customs which are outside of our control. Although we endeavor to supply our customers with products in the quickest possible time, customs delays may cause a longer lead time that we quote.";
			}
			else
			{
				$value[] = "The package will ship by Registered Air Mail. This type of service usually takes 30 days for delivery, with Tracking Number being available. Please note for some countries there may be unexpected delays in clearing customs which are outside of our control. Although we endeavor to supply our customers with products in the quickest possible time, customs delays may cause a longer lead time that we quote.";
			}
		}

		$path = APPPATH."data/template/ebay_description/ebay_description_".strtolower($item_obj->get_platform_country_id()).".html";

		if (is_file($path))
		{
			$template = file_get_contents($path);
			$desc_content = str_replace($search, $value, $template);
		}

		return $desc_content;
	}

	private function _extract_ebay_order_id($obj)
	{
		/* Remark from Ebay:
			- For a single line item order, the OrderID value is identical to the OrderLineItemID value that is generated upon creation of the order line item.
			- For a Combined Payment order or an order that goes through the eBay shopping cart flow, the OrderID value is automatically created by eBay.
		*/
		if($obj)
		{
			$platform_order_id = $obj->get_platform_order_id();
			$arr = explode("-", $platform_order_id);
			$transaction_id = $arr[0];

			if($obj->get_item_count() > 1)
			{
				unset($arr[0]);
				unset($arr[1]);
				$order_id = implode("-", $arr);
				$ebay_order_id = $order_id;
			}
			else
			{
				// Remark from Ebay: A unique identifier for an eBay order line item. This field is created as soon as there is a commitment to buy from the seller, and its value is based upon the concatenation of ItemID and TransactionID, with a hyphen in between these two IDs.
				$ebay_order_id = implode("-", array($obj->get_ext_item_cd(), $transaction_id));
			}
			return $ebay_order_id;
		}

		return false;
	}

	public function complete_sale_api($obj)
	{
		$this->init_http_obj();

		if ($this->http_obj)
		{
			$ebay_order_id = $this->_extract_ebay_order_id($obj);
			$temp = strtotime($obj->get_dispatch_date());
			$dispatch_date = date("Y-m-d\Th:i:s.000\Z", $temp);

			$courier_id = $obj->get_courier_id();
			$courier_id = trim($courier_id);
			switch($courier_id)
			{
				// Reference to supported eBay ShippingCarrierCodeType: http://developer.ebay.com/devzone/xml/docs/reference/ebay/types/ShippingCarrierCodeType.html
				case "DHL":
				case "DHLBBX":
					$carrier_code = "DHL";
					break;
				case "UPS":
					$carrier_code = "UPS";
					break;
				case "USPS":
				case "USPSPM":
					$carrier_code = "USPS";
					break;
				case "TOLL":
					$carrier_code = "TOLL";
					break;
				default:
					$carrier_code = "Other";
			}

			if($this->enable_log)
			{
				echo date("Y-m-d H:i:s") . " - Process on so_no " . $obj->get_so_no() . "<br>";
			}

			$this->xml =
'<?xml version="1.0" encoding="UTF-8"<CompleteSaleRequest xmlns="urn:ebay:apis:eBLBaseComponents">
	<OrderID>'.$ebay_order_id.'</OrderID>
	<Paid>true</Paid>
	<Shipment>
		<ShipmentTrackingDetails>
			<ShippingCarrierUsed>'.$carrier_code.'</ShippingCarrierUsed>
';
			if($obj->get_tracking_no())
			{
				$this->xml .=
'			<ShipmentTrackingNumber>'.$obj->get_tracking_no().'</ShipmentTrackingNumber>';
			}
			$this->xml .= '
		</ShipmentTrackingDetails>
		<ShippedTime>'.$dispatch_date.'</ShippedTime>
	</Shipment>
	<Shipped>true</Shipped>
	<RequesterCredentials>
		<eBayAuthToken>'.$this->http_obj->get_token().'</eBayAuthToken>
	</RequesterCredentials>
	<WarningLevel>High</WarningLevel>
</CompleteSaleRequest>
';
		}
	}

	public function additems_api()
	{
		$this->init_http_obj();

		if ($this->http_obj)
		{

			$this->xml =
'<?xml version="1.0" encoding="UTF-8"<AddItemsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
';

			$i=0;
			foreach ($this->add_item_list as $item)
			{
				if($this->enable_log)
				{
					echo date("Y-m-d H:i:s") . " - Process on " . $item->get_sku() . "<br>";
				}
				$request_xml =
'	<AddItemRequestContainer>
		<MessageID>'.($i+1).'</MessageID>
		<Item>
			<CategoryMappingAllowed>true</CategoryMappingAllowed>
			<ConditionID>1000</ConditionID>';
			if ($item->get_platform_country_id() == "SG")
			{
				if ($item->get_price() > 2000)
				{
					$request_xml .= '<AutoPay>false</AutoPay>';
				}
				else
				{
					$request_xml .= '<AutoPay>true</AutoPay>';
				}
			}
			$request_xml .= '
			<Currency>'.$item->get_platform_currency_id().'</Currency>
			<Description><![CDATA['.strip_invalid_xml(str_replace(array("\n", "\r"), "", $this->item_description($item))).']]></Description>
			<DispatchTimeMax>'.$item->get_handling_time().'</DispatchTimeMax>
			<ListingDuration>GTC</ListingDuration>
			<ListingType>FixedPriceItem</ListingType>
			<PaymentMethods>PayPal</PaymentMethods>
			<PayPalEmailAddress>'.$this->get_ebay_paypal_account($item->get_platform_country_id()).'</PayPalEmailAddress>
			<PictureDetails>
				<Item.PictureDetails.PictureURL>http://www.valuebasket.com/images/product/'.$item->get_sku().".".$item->get_image().'</Item.PictureDetails.PictureURL>
				<PictureURL>http://www.valuebasket.com/images/product/'.$item->get_sku().".".$item->get_image().'</PictureURL>
				<GalleryType>Gallery</GalleryType>
			</PictureDetails>
';

				$cat_id = $item->get_ext_ref_1();
				$request_xml .=
'			<PrimaryCategory>
				<CategoryID>'.$cat_id.'</CategoryID>
			</PrimaryCategory>
			<SecondaryCategory>
				<CategoryID>'.(is_numeric($cat2_id) ? $cat2_id : 0).'</CategoryID>
			</SecondaryCategory>
';

				if (($store_cat_id = $item->get_ext_ref_3()) || ($store_cat2_id = $item->get_ext_ref_4()))
				{
					$request_xml .=
'			<Storefront>
';
					if ($store_cat_id)
					{
						$request_xml .=
'				<StoreCategoryID>'.$store_cat_id.'</StoreCategoryID>
';
					}
					if ($store_cat2_id = $item->get_ext_ref_4())
					{
						$request_xml .=
'				<StoreCategory2ID>'.$store_cat2_id.'</StoreCategory2ID>
';
					}
					$request_xml.=
'			</Storefront>
';
				}

				$request_xml .=
'			<Quantity>'.$item->get_ext_qty().'</Quantity>
			<ReturnPolicy>
				<Description>You can submit cancellation requests by e-mail for your order at any time prior to the dispatch. Where the dispatch has been made, you may request for a refund up to 14 days after the goods are delivered; to submit the request, simply email us to start the process. Where you are eligible for a refund, Valuebasket.com will provide authorization and a returns address if appropriate. Most requests, that are within the 14 day period and that are (i) for new unopened items or (ii) found to be faulty will be eligible for a refund. Goods returned for refund must be in their original condition with all original packing, accessories and included materials. You must take all due reasonable care of the goods and return them in its original and undamaged condition. Where purchases are opened and are of usable software, consumable goods, or items impacted by hygiene, such items will not be eligible for refund. You agree to bear the costs of returning the item(s). The cost incurred in returning the item(s) may be eligible for reimbursement where the item(s) are found to be defective and if proper authorization from ValueBasket.com is sought and provided prior to the return of the item(s).</Description>
				<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
				<RefundOption>MoneyBack</RefundOption>
				<ReturnsWithinOption>Days_14</ReturnsWithinOption>
				<ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
			</ReturnPolicy>';
			if ($item->get_platform_country_id() == "SG")
			{
				$request_xml .= '<BestOfferDetails>
					<BestOfferEnabled>false</BestOfferEnabled>
				</BestOfferDetails>';
			}
			$request_xml .=  '<BuyerRequirementDetails>';
				switch($item->get_platform_country_id())
				{
					case "GB":
						$request_xml .=
				'<LinkedPayPalAccount>true</LinkedPayPalAccount>
';
						break;
					case "AU":
						$request_xml .= '
				<MaximumBuyerPolicyViolations>
					<Count>4</Count>
					<Period>Days_30</Period>
				</MaximumBuyerPolicyViolations>
				<MaximumUnpaidItemStrikesInfo>
					<Count>2</Count>
					<Period>Days_30</Period>
				</MaximumUnpaidItemStrikesInfo>
';
						break;
					case "SG":
						$request_xml .= '
				<MaximumUnpaidItemStrikesInfo>
					<Count>2</Count>
					<Period>Days_30</Period>
				</MaximumUnpaidItemStrikesInfo>
';
					break;
					default:
				}
				$request_xml .=
				'<ShipToRegistrationCountry>true</ShipToRegistrationCountry>
			</BuyerRequirementDetails>
			<ShippingDetails>
				<ShippingType>Flat</ShippingType>
				<ShippingServiceOptions>
					<FreeShipping>true</FreeShipping>
					<ShippingServicePriority>1</ShippingServicePriority>
					<ShippingService>'.$this->get_shipping_service($item->get_platform_country_id()).'</ShippingService>
					<ShippingServiceCost>0</ShippingServiceCost>
					<ShippingServiceAdditionalCost>0</ShippingServiceAdditionalCost>
				</ShippingServiceOptions>';
			if ($item->get_platform_country_id() == "US")
			{
				$request_xml .= '<InternationalShippingServiceOption>
								<FreeShipping>true</FreeShipping>
								<ShippingServicePriority>2</ShippingServicePriority>
								<ShippingService>StandardInternational</ShippingService>
								<ShippingServiceCost>0</ShippingServiceCost>
								<ShippingServiceAdditionalCost>0</ShippingServiceAdditionalCost>
								<ShipToLocation>Worldwide</ShipToLocation>
							</InternationalShippingServiceOption>
							';
			}

				//$request_xml .= $this->get_exclude_country_list($item->get_platform_country_id());

				$request_xml .= '</ShippingDetails>
			<Site>'.$this->ebay_site.'</Site>
			<StartPrice currencyID="'.$item->get_platform_currency_id().'">'.$item->get_price().'</StartPrice>
			<SKU>'.$item->get_sku().'</SKU>
			<Title>'.xmlspecialchars(strip_invalid_xml($item->get_title())).'</Title>
			<Country>HK</Country>
			<Location>HK</Location>
			<ShipToLocations>Worldwide</ShipToLocations>
		</Item>
	</AddItemRequestContainer>
';
				if($this->enable_log)
				{
					$this->_show_debug_xml("Prepare Request XML", $request_xml);
				}
				$this->xml .= $request_xml;
				$i++;
			}
			$this->xml .=
'	<RequesterCredentials>
		<eBayAuthToken>'.$this->http_obj->get_token().'</eBayAuthToken>
	</RequesterCredentials>
	<WarningLevel>High</WarningLevel>
</AddItemsRequest>';
		}
	}

	public function enditem_api()
	{
		$this->init_http_obj();

		if ($this->http_obj)
		{
			$item = $this->end_item;

			if($this->enable_log)
			{
				echo date("Y-m-d H:i:s") . " - Process on " . $item->get_sku() . "<br>";
			}

			$this->xml =
'<?xml version="1.0" encoding="UTF-8"<EndItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
	<WarningLevel>High</WarningLevel>
	<RequesterCredentials>
		<eBayAuthToken>'.$this->http_obj->get_token().'</eBayAuthToken>
	</RequesterCredentials>
	<MessageID>' . $item->get_sku() . '</MessageID>
	<EndingReason>' . $item->get_remark() . '</EndingReason>
	<ItemID>' . $item->get_ext_item_id() . '</ItemID>
</EndItemRequest>';

			if($this->enable_log)
			{
				$this->_show_debug_xml("Prepare Request XML", $this->xml);
			}
		}
	}

	public function reviseitem_api()
	{
		$this->init_http_obj();

		if ($this->http_obj)
		{
			$item = $this->revise_item;

			if($this->enable_log)
			{
				echo date("Y-m-d H:i:s") . " - Process on " . $item->get_sku() . "<br>";
			}

			$this->xml =
'<?xml version="1.0" encoding="UTF-8"<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
	<WarningLevel>High</WarningLevel>
	<RequesterCredentials>
		<eBayAuthToken>'.$this->http_obj->get_token().'</eBayAuthToken>
	</RequesterCredentials>
	<MessageID>' . $item->get_sku() . '</MessageID>
	<Item>
		<ItemID>' . $item->get_ext_item_id() . '</ItemID>
		<Title>'.xmlspecialchars(strip_invalid_xml($item->get_title())).'</Title>';
		if ($item->get_platform_country_id() == "SG")
		{
			if ($item->get_price() > 2000)
			{
				$this->xml .= '<AutoPay>false</AutoPay>';
			}
			else
			{
				$this->xml .= '<AutoPay>true</AutoPay>';
			}
		}
		$this->xml .= '<Description><![CDATA['.strip_invalid_xml(str_replace(array("\n", "\r"), "", $this->item_description($item))).']]></Description>
		<DescriptionReviseMode>Replace</DescriptionReviseMode>
		<DispatchTimeMax>'.$item->get_handling_time().'</DispatchTimeMax>
		<StartPrice currencyID="'.$item->get_platform_currency_id().'">'.$item->get_price().'</StartPrice>';
		if ($item->get_platform_country_id() == "SG")
		{
			$this->xml .= '<BestOfferDetails>
				<BestOfferEnabled>false</BestOfferEnabled>
			</BestOfferDetails>';
		}
		$this->xml .= '<Quantity>'.$item->get_ext_qty().'</Quantity>';

			$cat_id = $item->get_ext_ref_1();
			$this->xml .=
'		<PrimaryCategory>
			<CategoryID>'.$cat_id.'</CategoryID>
		</PrimaryCategory>
		<SecondaryCategory>
			<CategoryID>'.(is_numeric($cat2_id) ? $cat2_id : 0).'</CategoryID>
		</SecondaryCategory>
';

			if (($store_cat_id = $item->get_ext_ref_3()) || ($store_cat2_id = $item->get_ext_ref_4()))
			{
				$this->xml .=
'		<Storefront>
';
				if ($store_cat_id)
				{
					$this->xml .=
'			<StoreCategoryID>'.$store_cat_id.'</StoreCategoryID>
';
				}
				if ($store_cat2_id = $item->get_ext_ref_4())
				{
					$this->xml .=
'			<StoreCategory2ID>'.$store_cat2_id.'</StoreCategory2ID>
';
				}
				$this->xml .=
'		</Storefront>
';

				$this->xml .= '<ShippingDetails>
				<ShippingType>Flat</ShippingType>
				<ShippingServiceOptions>
					<FreeShipping>true</FreeShipping>
					<ShippingServicePriority>1</ShippingServicePriority>
					<ShippingService>'.$this->get_shipping_service($item->get_platform_country_id()).'</ShippingService>
					<ShippingServiceCost>0</ShippingServiceCost>
					<ShippingServiceAdditionalCost>0</ShippingServiceAdditionalCost>
				</ShippingServiceOptions>';
			if ($item->get_platform_country_id() == "US")
			{
				$this->xml .= '<InternationalShippingServiceOption>
								<FreeShipping>true</FreeShipping>
								<ShippingServicePriority>2</ShippingServicePriority>
								<ShippingService>StandardInternational</ShippingService>
								<ShippingServiceCost>0</ShippingServiceCost>
								<ShippingServiceAdditionalCost>0</ShippingServiceAdditionalCost>
								<ShipToLocation>Worldwide</ShipToLocation>
							</InternationalShippingServiceOption>
							';
			}

				//$request_xml .= $this->get_exclude_country_list($item->get_platform_country_id());

				$this->xml .= '</ShippingDetails>';
				$this->xml .=
	'</Item>
</ReviseItemRequest>';
			}

			if($this->enable_log)
			{
				$this->_show_debug_xml("Prepare Request XML", $this->xml);
			}
		}
	}

	public function _show_debug_xml($title, $xml)
	{
		$xml = str_replace(array("'", "\"", "\n", "\r", "&quot;"), array("\"", "\\\"", "", "", ""), $xml);
		print "
			<div href=\"#\">" .  date("Y-m-d H:i:s") . " - "  . $title . "<font color=\"blue\" style=\"font-size:80%;cursor:pointer\"
			onclick='
				var win = window.open(\"\", \"win\");
				win.document.open(\"text/xml\", \"replace\");
		";
		print "win.document.write(\"" . trim($xml) . "\");";
		print "win.document.close();
			'
			> (Show Detail)</font>
			</div>
		";
	}

	public function get_item_location($country_id)
	{
		switch ($country_id)
		{
			case "US":
				$location = "US";
				break;
			case "AU":
			case "SG":
				$location = "HK";
				break;
			default:
				$location = "GB";
				break;
		}
		return $location;
	}

	public function get_site_id($country_id)
	{
		switch ($country_id)
		{
			case "GB":
				$site_id = 3;
				break;
			case "DE":
				$site_id = 77;
				break;
			case "AU":
				$site_id = 15;
				break;
			case "SG":
				$site_id = 216;
				break;
			case "MY":
				$site_id = 207;
				break;
			default:
				$site_id = 0;
				break;
		}
		return $site_id;
	}

	public function get_shipping_service($country_id)
	{
		switch ($country_id)
		{
			case "US":
				$service = "UPSGround";
				break;
			case "AU":
				$service = "AU_StandardDeliveryFromOutsideAU";
				break;
			case "DE":
				$service = "DE_DPBuecherWarensendung";
				break;
			case "GB":
				$service = "UK_StandardShippingFromOutside";
				break;
			case "SG":
				$service = "SG_DomesticSpeedpostIslandwide";
				break;
			default:
				$service = "UK_RoyalMailSpecialDeliveryNextDay";
				break;
		}
		return $service;
	}

	public function get_ebay_paypal_account($country_id)
	{
		switch ($country_id)
		{
			case "AU":
				$paypal_acc = "paypal.oce@valuebasket.com";
				break;
			case "SG":
				$paypal_acc = "paypal.asia@valuebasket.com";
				break;
			case "US":
			default:
				$paypal_acc = "paypal.ebay@valuebasket.com";
				break;
		}
		return $paypal_acc;
	}

	public function get_exclude_country_list($country_id)
	{
		// return empty will use ebay default setting instead
		switch($country_id)
		{
			case "AU":
				return "<ExcludeShipToLocation>PO Box</ExcludeShipToLocation>
				<ExcludeShipToLocation>Africa</ExcludeShipToLocation>
				<ExcludeShipToLocation>MiddleEast</ExcludeShipToLocation>
				<ExcludeShipToLocation>Europe</ExcludeShipToLocation>
				<ExcludeShipToLocation>SouthAmerica</ExcludeShipToLocation>
				<ExcludeShipToLocation>Central America and Caribbean</ExcludeShipToLocation>
				<ExcludeShipToLocation>CH</ExcludeShipToLocation>
				<ExcludeShipToLocation>GI</ExcludeShipToLocation>
				<ExcludeShipToLocation>AD</ExcludeShipToLocation>
				<ExcludeShipToLocation>ME</ExcludeShipToLocation>
				<ExcludeShipToLocation>JE</ExcludeShipToLocation>
				<ExcludeShipToLocation>BM</ExcludeShipToLocation>
				<ExcludeShipToLocation>LI</ExcludeShipToLocation>
				<ExcludeShipToLocation>HR</ExcludeShipToLocation>
				<ExcludeShipToLocation>NO</ExcludeShipToLocation>
				<ExcludeShipToLocation>MC</ExcludeShipToLocation>
				<ExcludeShipToLocation>AE</ExcludeShipToLocation>
				<ExcludeShipToLocation>BA</ExcludeShipToLocation>
				<ExcludeShipToLocation>YE</ExcludeShipToLocation>
				<ExcludeShipToLocation>OM</ExcludeShipToLocation>
				<ExcludeShipToLocation>RU</ExcludeShipToLocation>
				<ExcludeShipToLocation>LB</ExcludeShipToLocation>
				<ExcludeShipToLocation>CN</ExcludeShipToLocation>
				<ExcludeShipToLocation>IQ</ExcludeShipToLocation>
				<ExcludeShipToLocation>RS</ExcludeShipToLocation>
				<ExcludeShipToLocation>JO</ExcludeShipToLocation>
				<ExcludeShipToLocation>SM</ExcludeShipToLocation>
				<ExcludeShipToLocation>GL</ExcludeShipToLocation>
				<ExcludeShipToLocation>TR</ExcludeShipToLocation>
				<ExcludeShipToLocation>MX</ExcludeShipToLocation>
				<ExcludeShipToLocation>IS</ExcludeShipToLocation>
				<ExcludeShipToLocation>UA</ExcludeShipToLocation>
				<ExcludeShipToLocation>SA</ExcludeShipToLocation>
				<ExcludeShipToLocation>MD</ExcludeShipToLocation>
				<ExcludeShipToLocation>IL</ExcludeShipToLocation>
				<ExcludeShipToLocation>VA</ExcludeShipToLocation>
				<ExcludeShipToLocation>CA</ExcludeShipToLocation>
				<ExcludeShipToLocation>GG</ExcludeShipToLocation>
				<ExcludeShipToLocation>QA</ExcludeShipToLocation>
				<ExcludeShipToLocation>PM</ExcludeShipToLocation>
				<ExcludeShipToLocation>SJ</ExcludeShipToLocation>
				<ExcludeShipToLocation>AL</ExcludeShipToLocation>
				<ExcludeShipToLocation>BH</ExcludeShipToLocation>
				<ExcludeShipToLocation>KW</ExcludeShipToLocation>";
				break;
			case "SG":
				return "";
				break;
			default:
				return "<ExcludeShipToLocation>PO Box</ExcludeShipToLocation>
				<ExcludeShipToLocation>Africa</ExcludeShipToLocation>
				<ExcludeShipToLocation>MiddleEast</ExcludeShipToLocation>
				<ExcludeShipToLocation>Oceania</ExcludeShipToLocation>
				<ExcludeShipToLocation>SouthAmerica</ExcludeShipToLocation>
				<ExcludeShipToLocation>Central America and Caribbean</ExcludeShipToLocation>
				<ExcludeShipToLocation>CH</ExcludeShipToLocation>
				<ExcludeShipToLocation>GI</ExcludeShipToLocation>
				<ExcludeShipToLocation>AD</ExcludeShipToLocation>
				<ExcludeShipToLocation>ME</ExcludeShipToLocation>
				<ExcludeShipToLocation>JE</ExcludeShipToLocation>
				<ExcludeShipToLocation>BM</ExcludeShipToLocation>
				<ExcludeShipToLocation>LI</ExcludeShipToLocation>
				<ExcludeShipToLocation>HR</ExcludeShipToLocation>
				<ExcludeShipToLocation>NO</ExcludeShipToLocation>
				<ExcludeShipToLocation>MC</ExcludeShipToLocation>
				<ExcludeShipToLocation>AE</ExcludeShipToLocation>
				<ExcludeShipToLocation>BA</ExcludeShipToLocation>
				<ExcludeShipToLocation>YE</ExcludeShipToLocation>
				<ExcludeShipToLocation>OM</ExcludeShipToLocation>
				<ExcludeShipToLocation>RU</ExcludeShipToLocation>
				<ExcludeShipToLocation>LB</ExcludeShipToLocation>
				<ExcludeShipToLocation>CN</ExcludeShipToLocation>
				<ExcludeShipToLocation>IQ</ExcludeShipToLocation>
				<ExcludeShipToLocation>RS</ExcludeShipToLocation>
				<ExcludeShipToLocation>JO</ExcludeShipToLocation>
				<ExcludeShipToLocation>SM</ExcludeShipToLocation>
				<ExcludeShipToLocation>GL</ExcludeShipToLocation>
				<ExcludeShipToLocation>TR</ExcludeShipToLocation>
				<ExcludeShipToLocation>MX</ExcludeShipToLocation>
				<ExcludeShipToLocation>IS</ExcludeShipToLocation>
				<ExcludeShipToLocation>UA</ExcludeShipToLocation>
				<ExcludeShipToLocation>SA</ExcludeShipToLocation>
				<ExcludeShipToLocation>MD</ExcludeShipToLocation>
				<ExcludeShipToLocation>IL</ExcludeShipToLocation>
				<ExcludeShipToLocation>VA</ExcludeShipToLocation>
				<ExcludeShipToLocation>CA</ExcludeShipToLocation>
				<ExcludeShipToLocation>GG</ExcludeShipToLocation>
				<ExcludeShipToLocation>QA</ExcludeShipToLocation>
				<ExcludeShipToLocation>PM</ExcludeShipToLocation>
				<ExcludeShipToLocation>SJ</ExcludeShipToLocation>
				<ExcludeShipToLocation>AL</ExcludeShipToLocation>
				<ExcludeShipToLocation>BH</ExcludeShipToLocation>
				<ExcludeShipToLocation>KW</ExcludeShipToLocation>";
		}
	}

	public function response_xml($call_name, $rs)
	{
		if($this->enable_log)
		{
			$this->_show_debug_xml("Response XML", $rs);
		}
		$xml = simplexml_load_string($rs);
		switch ($call_name)
		{
			case "AddItems":
				if ($xml->AddItemResponseContainer)
				{
					$err_msg = "";
					$price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
					for ($i=0; $i<count($xml->AddItemResponseContainer); $i++)
					{
						$rskey = (int)$xml->AddItemResponseContainer[$i]->CorrelationID - 1;
						$item = $this->add_item_list[$rskey];
						if ($price_ext_obj = $price_ext_dao->get(array("sku"=>$item->get_sku(), "platform_id"=>$item->get_platform_id())))
						{
							if ($ext_item_id = (string)$xml->AddItemResponseContainer[$i]->ItemID)
							{
								$price_ext_obj->set_ext_item_id($ext_item_id);
								$price_ext_obj->set_ext_status('L');
								$price_ext_dao->update($price_ext_obj);
							}

							$n = 0;
							for ($j=0; $j<count($xml->AddItemResponseContainer[$i]->Errors); $j++)
							{
								if((string)$xml->AddItemResponseContainer[$i]->Errors[$j]->SeverityCode == 'Error')
								{
									if($n == 0)
									{
										$err_msg .= $item->get_sku() . "\n";
										$err_msg .= "Reason:\n";
									}
									$err_msg .=  (string)$xml->AddItemResponseContainer[$i]->Errors[$j]->LongMessage . "\n";
									$n++;
								}
							}
							$err_msg .= "\n";
						}
					}
					return $err_msg;
				}
				break;
			case "EndItem":
				$error_msg = "";
				switch($xml->Ack)
				{
					case "Failure":
						foreach($xml->Errors AS $error)
						{
							$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
						}
						return array("response"=>0, "message"=> "End eBay Listing Failed:\n" . $error->LongMessage);
						break;
					case "Warning":
						foreach($xml->Errors AS $error)
						{
							$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
						}
						return array("response"=>0, "message"=>"End eBay Listing Success with Warning:\n" . $error->LongMessage);
						break;
					case "Success":
						$price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
						$price_ext_dao->get(array("sku", ""));
						return array("response"=>1, "message"=>"End eBay Listing Sucess");
						break;
				}
				break;
			case "ReviseItem":
				$error_msg = "";
				switch($xml->Ack)
				{
					case "Failure":
						foreach($xml->Errors AS $error)
						{
							$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
						}
						return array("response"=>0, "message"=> "Revise eBay Listing Failed:\n" . $error->LongMessage);
						break;
					case "Warning":
						foreach($xml->Errors AS $error)
						{
							$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
						}
						return array("response"=>0, "message"=>"Revise eBay Listing Success with Warning:\n" . $error->LongMessage);
						break;
					case "Success":
						$price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
						$price_ext_dao->get(array("sku", ""));
						return array("response"=>1, "message"=>"Revise eBay Listing Sucess");
						break;
				}
				break;

			case "CompleteSale":
				$error_msg = "";
				switch($xml->Ack)
				{
					case "Failure":
						foreach($xml->Errors AS $error)
						{
							$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
						}
						return array("response"=>0, "message"=> "eBay Update Shipment Status Failed:\n" . $error->LongMessage);
						break;
					case "Warning":
						foreach($xml->Errors AS $error)
						{
							$error_msg = "ShortMessage: ".$error->ShortMessage."\nLongMessage: ".$error->LongMessage."\nErrorCode: ".$error->ErrorCode."\nSeverityCode: ".$error->SeverityCode."\n\n";
						}
						return array("response"=>0, "message"=>"eBay Update Shipment Status Success with Warning:\n" . $error->LongMessage);
						break;
					case "Success":
						return array("response"=>1, "message"=>"eBay Update Shipment Status Sucess");
						break;
				}
				break;
		}
	}

	public function send_feedback_email(Base_dto $dto)
	{
		if($obj = $this->get_so_srv()->get_ebay_feedback_email_content(array("so.so_no"=>$dto->get_so_no()), array("limit"=>1)))
		{
			$platform_id = $obj->get_platform_id();
			$replace["bill_name"] = $obj->get_delivery_name();
			$replace["cs_email_address"] = "ebaycs@valuebasket.com";

			switch($platform_id)
			{
				case "EBAYAU":
					$country_id = "AU";
					$replace["ebay_store_url"] = "http://stores.ebay.com.au/valuebasketzonea";
					break;
				case "EBAYUK":
					$country_id = "UK";
					$replace["ebay_store_url"] = "http://stores.ebay.co.uk/ValueBasket";
					break;
				case "EBAYUS":
					$country_id = "US";
					$replace["ebay_store_url"] = "http://stores.ebay.com/ValueBasket";
					break;
				case "EBAYSG":
					$country_id = "SG";
					$replace["ebay_store_url"] = "http://stores.ebay.com.sg/ValueBasket-SG";
					break;
				default:
					$country_id = "UK";
					$replace["ebay_store_url"] = "http://stores.ebay.co.uk/ValueBasket";
			}

			$replace["item_detail"] = "";
			if($item_list = $obj->get_item_list())
			{
				$item_arr = explode("||", $item_list);
				if($item_arr)
				{
					foreach($item_arr as $item_str)
					{
						list($ext_item_cd,$prod_name) = explode(",", $item_str);
						$replace["item_detail"] = $prod_name . " (eBay Item ID: " . $ext_item_cd . ")</ br>";
					}
				}
			}
			$replace["logo_file_name"] = $this->get_config()->value_of("logo_file_name");

			$this->get_so_srv()->include_dto("Event_email_dto");
			$dto = new Event_email_dto();
			$dto->set_mail_to($obj->get_email());
			$lang_id = "en";
			$dto->set_lang_id($lang_id);

			$dto->set_event_id("ebay_feedback_email");
			$dto->set_tpl_id("ebay_feedback_email");
			include_once(APPPATH."hooks/country_selection.php");
			$replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));
			$email_sender = "no-reply@" . strtolower($replace["site_name"]);
			$dto->set_mail_from($email_sender);
			$dto->set_replace($replace);
			$this->get_event_srv()->fire_event($dto);
		}
	}

	public function cron_send_feedback_email()
	{
		if($objlist = $this->get_so_srv()->get_ebay_feedback_email_content())
		{
			foreach($objlist as $obj)
			{
				$this->send_feedback_email($obj);
			}
		}
	}

	public function isHtml($string)
	{
		$pattern = '/^(<.+>)(.*)(<\/[a-zA-Z ]+>)$/s';

		if(preg_match($pattern, trim($string)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

/* End of file ebay_service.php */
/* Location: ./system/application/libraries/service/Ebay_service.php */