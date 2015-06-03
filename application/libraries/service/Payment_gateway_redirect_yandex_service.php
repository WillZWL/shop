<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Yandex/yandex_integrator.php");

// in order to insert flex_ria data when mail vbyandexpay@eservicesgroup.com
include_once(APPPATH . "libraries/service/Flex_service.php");

class Payment_gateway_redirect_yandex_service extends Payment_gateway_redirect_service
{
	const PAYMENT_SUCCESS_PASSWORD_ENCODE = "[ZiYanO4Odexna]";

	private $_yandex_integrator;
	public $notification_result;
	public $payment_monitor_group = "vbyandexpay@eservicesgroup.com";
	private $_flex_service;

	public function __construct($debug = 0)
	{
		parent::__construct($debug);
		$this->_yandex_integrator = new Yandex_integrator($debug, $this->get_config()->value_of("data_path"));
		$this->_flex_service = new Flex_service();
	}

	public function get_payment_process_url()
	{
		return base_url() . "checkout_redirect_method/payment_response?payment_type=yandex";
	}

	public function get_encoded_url_key($so_no)
	{
		return md5($so_no . self::PAYMENT_SUCCESS_PASSWORD_ENCODE);
	}

	protected function get_failure_page()
	{
		$encodedURL = $this->get_encoded_url_key($this->so->get_so_no());
		$url = base_url() . $this->failure_page . $this->so->get_so_no() . "?key=" . $encodedURL;
		return $url;
	}

	public function get_successful_page($so_no = null)
	{
//		$debug_string = ($this->debug) ? "&debug=1" : "";

		if ($so_no == null)
			$put_so_no = $this->so->get_so_no();
		else
			$put_so_no = $so_no;

		$encodedURL = $this->get_encoded_url_key($put_so_no);

		$url = base_url() . $this->successful_page . $put_so_no . "?key=" . $encodedURL;
		return $url;
	}

	public function prepare_get_url_request($payment_info = array(), &$request_data)
	{
		$requestInformation = array();
		$requestInformation['clientId'] = $this->so->get_client_id();
		$requestInformation['orderId'] = $this->so->get_so_no();
		$requestInformation['totalAmount'] = $this->so->get_amount();
//		$requestInformation['currency'] = strtoupper($this->so->get_currency_id());
		$requestInformation['email'] = $this->client->get_email();

//		$requestInformation['language'] = $this->so->get_lang_id();
//		$requestInformation['countryId'] = $this->so->get_bill_country_id();
		$requestInformation['tel'] = $this->client->get_tel_1() . $this->client->get_tel_2() . $this->client->get_tel_3();

		$requestInformation['notificationUrl'] = $this->get_notification_url();
		$requestInformation['successUrl'] = $this->get_successful_page();
		$requestInformation['failUrl'] = $this->get_failure_page();
		$requestInformation['process_order_url'] = $this->get_payment_process_url();
		$requestInformation['card_code'] = $payment_info["card_type"];

		$form_data = $this->_yandex_integrator->form_payment_array($requestInformation);
		$request_data = @http_build_query($form_data);
		return $form_data;
	}

	public function get_redirect_url($request_data, &$response_data)
	{
		$trycount = 0;
		do
		{
			$output = $this->_yandex_integrator->submitPaymentForm($request_data);
			$trycount++;
		}while (($trycount < 1) && ((!$output) || (empty($output))));

		if (($output != "") && ($this->_yandex_integrator->getCurlError() == ""))
			$response_data = $output;
		else
		{
			$response_data = $this->_yandex_integrator->getCurlResult();
			$down_message = "Session: " . $session . "Please contact Yandex, IT please consider to switch payment gateway." . "O:" . @http_build_query($request_data) . ", I:" . $this->_yandex_integrator->getCurlError() . $this->_yandex_integrator->getCurlInfo();
			mail($this->sitedown_email, "Yandex payment issue", $down_message, 'From: website@valuebasket.com');
			return "ERROR::" . base_url() . "checkout_onepage/payment_result/0/{$this->so->get_so_no()}?type=sitedown";
		}
		return $output;
	}

	public function process_payment_status_in_general($general_data = array(), $get_data = array())
	{
		$action = $general_data["action"];
		$customerNumber = $general_data["customerNumber"];

		if (strpos($customerNumber, "debug") !== FALSE)
		{
			$this->_yandex_integrator->setDebug(1);
		}

		if ($action == "checkOrder")
		{
			$this->_checkOrder($general_data, $get_data, $so_number, $data_from_pmgw, $data_to_pmgw);
		}
		else if ($action == "paymentAviso")
		{
			parent::process_payment_status_in_general($general_data, $get_data);
		}
	}

	private function _checkOrder($general_data = array(), $get_data = array(), &$so_number, &$data_from_pmgw, &$data_to_pmgw)
	{
		$so_number = $general_data["orderNumber"];
		$amount = $general_data["orderSumAmount"];
		$paymentType = $general_data["paymentType"];
		$invoiceId = $general_data["invoiceId"];

		$md5 = $general_data["md5"];

		$calculatedMd5 = strtoupper(md5($general_data["action"] . ";"
						. $general_data["orderSumAmount"] . ";"
						. $general_data["orderSumCurrencyPaycash"] . ";"
						. $general_data["orderSumBankPaycash"] . ";"
						. $general_data["shopId"] . ";"
						. $general_data["invoiceId"] . ";"
						. $general_data["customerNumber"] . ";"
						. Yandex_integrator::DIGEST_PASSWORD));

		$data_from_pmgw = $this->array_implode('=', ',', $general_data);

//		error_log(__METHOD__ . __LINE__ . " " . $calculatedMd5 . $md5);
		if ($calculatedMd5 != $md5)
		{
			$message = "calculated md5:" . $calculatedMd5 . ", md5:" . $md5 . ", data_from_pmgw:" . $data_from_pmgw;
			mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " MD5 not match", $message, "From: website@valuebasket.com");
			$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_FAIL_MD5, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_CHECKORDER);
		}
		else
		{
			$so_srv = $this->get_so_srv();
			if ($this->so = $so_srv->get(array("so_no"=>$so_number)))
			{
				$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $data_from_pmgw));
//				$this->so->set_txn_id($invoiceId);
				if ($this->so->get_amount() == $amount)
				{
//update sops
					$sops_dao = $so_srv->get_sops_dao();
					$this->sops = $sops_dao->get(array("so_no"=>$this->so->get_so_no()));
					$this->sops->set_card_id($paymentType);
					$this->sops->set_payer_ref($invoiceId);
					$sops_dao->update($this->sops);
//reply yandex to accept the order
					$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_SUCCESS, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_CHECKORDER);
				}
				else
				{
//reject
					$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_REJECT_PAYMENT, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_CHECKORDER);
					mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " reject payment, amount not match, so_no:" . $this->so->get_so_no(), $data_from_pmgw, "From: website@valuebasket.com");
				}
//				$so_srv->update($this->so);
				$this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", str_replace("&", "\n&", $data_to_pmgw));
			}
			else
			{
				$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_TECHNICAL_ERROR, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_CHECKORDER);
				mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " invalid SO Number", $data_from_pmgw, "From: website@valuebasket.com");
			}
		}
		print $data_to_pmgw;
	}

	public function payment_notification($postData)
	{
		$this->process_payment_status_in_general($postData);
	}

	public function process_payment_status($general_data = array(), $get_data = array(), &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
	{
		$so_number = $general_data["orderNumber"];
		$amount = $general_data["orderSumAmount"];
		$paymentType = $general_data["paymentType"];
		$invoiceId = $general_data["invoiceId"];
//		$yandexPaymentId = $general_data["yandexPaymentId"];
		$cardDetails = $general_data["cdd_pan_mask"];

		$md5 = $general_data["md5"];

		$calculatedMd5 = strtoupper(md5($general_data["action"] . ";"
						. $general_data["orderSumAmount"] . ";"
						. $general_data["orderSumCurrencyPaycash"] . ";"
						. $general_data["orderSumBankPaycash"] . ";"
						. $general_data["shopId"] . ";"
						. $general_data["invoiceId"] . ";"
						. $general_data["customerNumber"] . ";"
						. Yandex_integrator::DIGEST_PASSWORD));

		$data_from_pmgw = $this->array_implode('=', ',', $general_data);
		if ($calculatedMd5 != $md5)
		{
			$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_FAIL_MD5, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_AVISO);
			$payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;

			$message = "calculated md5:" . $calculatedMd5 . ", md5:" . $md5 . ", data_from_pmgw:" . $data_from_pmgw;
			mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " MD5 not match in notification", $message, "From: website@valuebasket.com");
		}
		else
		{
			$so_srv = $this->get_so_srv();
			if ($this->so = $so_srv->get(array("so_no"=>$so_number)))
			{
				$so_data = array("txn_id" => $invoiceId);
				if ($this->so->get_amount() == $amount)
				{
//reply yandex to accept the order
					$payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
					$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_SUCCESS, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_AVISO);
$email_content = <<<EOT
order number:{$this->so->get_so_no()}
client id:{$this->so->get_client_id()}
Amount:{$this->so->get_amount()}
EOT;
					mail($this->payment_monitor_group, "[VB] " . $this->get_payment_gateway_name() . " payment success", $email_content, "From: website@valuebasket.com");
					//insert record to flex_ria table
					$this->yandex_order_to_flex_ria($this->so->get_so_no());

					if ($cardDetails)
					{
						$card = explode("|", $cardDetails);
						if (sizeof($card) > 0)
							$socc_data = array("card_bin" => $card[0], "card_last4" => $card[1]);
						else
							$socc_data = array("card_bin" => "noinfo");
					}
					else
					{
						$socc_data = array("fd_status" => "0");
					}

				}
				else
				{
					$payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
					$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_UNABLE_PROCESS, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_AVISO);
					mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " amount not match in so_no:" . $this->so->get_so_no(), $data_from_pmgw, "From: website@valuebasket.com");
				}
			}
			else
			{
				$payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
				$data_to_pmgw = $this->_yandex_integrator->createCheckOrderReply(Yandex_integrator::CHECK_ORDER_TECHNICAL_ERROR, $invoiceId, Yandex_integrator::RESPONSE_MESSAGE_TYPE_AVISO);
				mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " invalid SO Number in notification", $data_from_pmgw, "From: website@valuebasket.com");
			}
		}

		$this->notification_result = $data_to_pmgw;
		return $payment_result;
	}

	public function refund_order($refundObj, &$autoRefundObj, &$requestOut, &$requestIn)
	{
		$so_srv = $this->get_so_srv();
		if ($this->so = $so_srv->get(array("so_no" => $refundObj->get_so_no())))
		{
			$autoRefundObj = $this->get_auto_refund_requirement($refundObj, $this->so->get_amount());

			if ($autoRefundObj !== FALSE)
			{
				$formRequest = array("so_no" => $autoRefundObj->get_so_no()
									, "transaction_id" => $this->so->get_txn_id()
									, "amount" => $this->so->get_amount()
									, "currency_id" => $this->so->get_currency_id());
//form the request and save to file
				$refundRequest = $this->_yandex_integrator->form_refund_request_and_save($formRequest);
				try
				{
					$submitResult = $this->_yandex_integrator->submitRefund($refundRequest);
				}
				catch(Exception $ex)
				{
					$message = $ex->getMessage();
					mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " cannot send refund API, so_no:" . $this->so->get_so_no(), $message, "From: website@valuebasket.com");
				}
//				var_dump($submitResult);
//save to log also
				$requestOut = $refundRequest;
				if ($submitResult !== FALSE)
				{
					$requestIn = $submitResult["responseXml"];
					return $submitResult["result"];
				}
			}
		}
		return FALSE;
	}

	public function get_payment_gateway_name()
	{
		return "yandex";
	}

	public function get_technical_support_email()
	{
		return "oswald-alert@eservicesgroup.com";
	}

	public function process_failure_action()
	{
		print $this->notification_result;
	}

	public function process_cancel_action()
	{

	}

	public function process_success_action()
	{
//error_log(__METHOD__ . __LINE__ . "fire email");
		$this->fire_success_event();
		print $this->notification_result;
		return true;
	}

	public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
	{

	}

	public function is_payment_need_credit_check($is_fraud = false)
	{
		if ($is_fraud)
			return true;

		$card_id = $this->sops->get_card_id();

		if ($card_id == "GP")
			return false;
		elseif (($card_id == "AC") || ($card_id == "PC"))
		{
			return true;
		}
		else
		{
//web money
			return true;
		}
	}

	public function is_need_dm_service($is_fraud = false)
	{
		if ($is_fraud)
			return false;

		return $this->is_payment_need_credit_check($is_fraud);
	}

	public function yandex_order_to_flex_ria($so_no)
	{
		try {
			$result = $this->_flex_service->platfrom_order_insert_flex_ria('yandex', $so_no);
			if ($result) {
				mail('handy.hon@eservicesgroup.com', '[VB]-Yandex flex ria insert success', 'so_no : ' . $so_no, 'From: website@valuebasket.com');
			} else {
				mail('handy.hon@eservicesgroup.com', '[VB]-Yandex flex ria insert failed', 'so_no : ' . $so_no, 'From: website@valuebasket.com');
			}
		} catch (Exception $e) {
			mail('handy.hon@eservicesgroup.com', '[VB]-Yandex flex ria failed', $e->getMessage(), 'From: website@valuebasket.com');
		}
	}
}
