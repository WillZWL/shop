<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_ctpe_service.php");

class Payment_gateway_redirect_inpendium_ctpe_service extends Payment_gateway_redirect_ctpe_service
{
	const CTPE_TEST_QUERY_SERVER = "test.inpendium.net";
	const CTPE_QUERY_SERVER = "inpendium.net";
	const CTPE_QUERY_ACTION_PAGE = "/payment/query";
	const CTPE_TEST_PAYMENT_SERVER = "test.inpendium.net";
	const CTPE_PAYMENT_SERVER = "inpendium.net";
	const CTPE_TEST_SECURITY_SENDER = "ff8080813eeffabf013efead87890754";
	const CTPE_SECURITY_SENDER = "8a8394c340727a5a014076510f6d0721";
	const CTPE_TEST_TRANSACTION_CHANNEL = "ff8080813eeffabf013efeaf0697075d";
	const CTPE_TRANSACTION_CHANNEL = "8a8394c340727a5a01407651c7360727";
	const CTPE_TRANSACTION_3D_CHANNEL = "8a8394c6419be6360141a16dc8030bde";
	const CTPE_TRANSACTION_SOFORT_CHANNEL = "8a8394c247e5b8010148405ae830084f";
	const CTPE_TEST_USER_LOGIN_ID = "ff8080813eeffabf013efead878b0758";
	const CTPE_USER_LOGIN_ID = "8a8394c340727a5a014076510f6d0725";
	const CTPE_TEST_USER_PASSWORD = "7pBhjZ39";
	const CTPE_USER_PASSWORD = "pwY65MkQ";
	const CTPE_TEST_SECURITY_TOKEN = "cdWk7prAwpqEwSz7";
	const CTPE_SECURITY_TOKEN = "jAtRwaaWPqBrD5h7";
	const CTPE_TEST_TRANSACTION_MODE = "INTEGRATOR_TEST";
	const CTPE_TRANSACTION_MODE = "LIVE";
	const CTPE_ACTION_PAGE = "/frontend/payment.prc";
/*
	public $credit_check_dm_amount = array ("AU" => 150,
											"HK" => 1500,
											"MY" => 380,
											"NZ" => 300,
											"SG" => 400,
											"US" => 150);
*/
	public function __construct($debug)
	{
		parent::__construct($debug);
	}

	public function get_payment_gateway_name()
	{
		return "inpendium_ctpe";
	}

	public function get_customized_css()
	{
//		return "";
		return "https://{$_SERVER['HTTP_HOST']}/css/inpendium_ctpe.css";
	}

	public function get_customized_js()
	{
		return "https://{$_SERVER['HTTP_HOST']}/js/inpendium_ctpe_" . get_lang_id() . ".js";
	}

	public function get_query_server()
	{
		if ($this->debug)
		{
			return array("server" => self::CTPE_TEST_QUERY_SERVER, "path" => self::CTPE_QUERY_ACTION_PAGE);
		}
		else
		{
			return array("server" => self::CTPE_QUERY_SERVER, "path" => self::CTPE_QUERY_ACTION_PAGE);
		}
	}

	public function get_verification_schedule_id()
	{
		return "INPENDIUM_ORDERS_VERIFICATION";
	}

	public function get_ctpe_integrator($currency = null, $card_type = null)
	{
		if ($this->debug)
		{
			return array("server" => self::CTPE_TEST_PAYMENT_SERVER,
						"path" => self::CTPE_ACTION_PAGE,
						"sender" => self::CTPE_TEST_SECURITY_SENDER,
						"channel" => self::CTPE_TEST_TRANSACTION_CHANNEL,
						"userid" => self::CTPE_TEST_USER_LOGIN_ID,
						"userpwd" => self::CTPE_TEST_USER_PASSWORD,
						"token" => self::CTPE_TEST_SECURITY_TOKEN,
						"transaction_mode" => self::CTPE_TEST_TRANSACTION_MODE,
						"transaction_response" => "SYNC"
						);
		}
		else
		{
			$channel = self::CTPE_TRANSACTION_CHANNEL;
			$threeDChannel = array("EUR", "GBP", "CHF", "AUD", "MYR", "USD", "NZD", "SGD", "HKD", "PLN");
			if ($this->so)
			{
				$checkCurrency = $this->so->get_currency_id();
			}
			else if ($currency != null)
			{
				$checkCurrency = $currency;
			}

			if ($card_type == "SOFORT")
			{
				$channel = self::CTPE_TRANSACTION_SOFORT_CHANNEL;
			}
			else if (($this->so) || ($currency != null))
			{
				if (in_array($checkCurrency, $threeDChannel))
					$channel = self::CTPE_TRANSACTION_3D_CHANNEL;
				else
					$channel = self::CTPE_TRANSACTION_CHANNEL;
			}

			return array("server" => self::CTPE_PAYMENT_SERVER,
						"path" => self::CTPE_ACTION_PAGE,
						"sender" => self::CTPE_SECURITY_SENDER,
						"channel" => $channel,
						"userid" => self::CTPE_USER_LOGIN_ID,
						"userpwd" => self::CTPE_USER_PASSWORD,
						"token" => self::CTPE_SECURITY_TOKEN,
						"transaction_mode" => self::CTPE_TRANSACTION_MODE,
						"transaction_response" => "SYNC"
						);
		}
	}

	public function is_need_dm_service($is_fraud = false)
	{
		return $this->is_payment_need_credit_check($is_fraud);
	}

	public function is_payment_need_credit_check($is_fraud = false)
	{
		if ($this->sops->get_card_id() == "SOFORT")
			return false;
		else
			return parent::is_payment_need_credit_check_3D($is_fraud);
	}

	public function get_technical_support_email()
	{
		return "oswald-alert@eservicesgroup.com";
	}
}
?>