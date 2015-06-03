<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Trustly/trustly_integrator.php");

class Payment_gateway_redirect_trustly_service extends Payment_gateway_redirect_service
{
	private $_trustly_integrator;

	public function __construct($debug)
	{
		parent::__construct($debug);
		$this->_trustly_integrator = new Trustly_integrator($debug);
	}

	public function get_payment_gateway_name()
	{
		return "trustly";
	}

	public function get_notification_url()
	{
		$debug_string = ($this->debug) ? "/1" : "";
// double check the url with lang_id_countryID pair
// this is to prevent redirection
		$check_url = base_url();
		if ((substr($check_url, -6, 2) != get_lang_id())
			|| (strtoupper(substr($check_url, -3, 2)) != PLATFORMCOUNTRYID))
			$check_url = base_url() . get_lang_id() . "_" . PLATFORMCOUNTRYID . "/";

		$url = $check_url . "checkout_redirect_method/trustly_payment_notification/". $this->get_payment_gateway_name() . $debug_string;
		return $url;
	}

	public function prepare_get_url_request($payment_info = array(), &$request_data)
	{
		$requestInformation = array();
		$requestInformation['clientId'] = $this->so->get_client_id();
		$requestInformation['orderId'] = $this->so->get_so_no();
		$requestInformation['totalAmount'] = $this->so->get_amount();
		$requestInformation['currency'] = strtoupper($this->so->get_currency_id());
		$requestInformation['firstName'] = $this->client->get_forename();
		$requestInformation['lastName'] = $this->client->get_surname();

		$requestInformation['language'] = $this->so->get_lang_id();
		$requestInformation['countryId'] = $this->so->get_bill_country_id();
		$requestInformation['tel'] = $this->client->get_tel_1() . $this->client->get_tel_2() . $this->client->get_tel_3();

		$requestInformation['notificationUrl'] = $this->get_notification_url();
		$requestInformation['successUrl'] = $this->get_successful_page();
		$requestInformation['failUrl'] = $this->get_failure_page();

		$json_data = $this->_trustly_integrator->form_payment_array($requestInformation);
		$request_data = $json_data;
		return $json_data;
	}

	public function get_redirect_url($request_data, &$response_data)
	{
		$trycount = 0;
		$server_result = '';
		$server_error = '';
		$server_info = '';
		do
		{
			$this->_trustly_integrator->send_data_to_trustly($request_data, $server_result, $server_error, $server_info);
			$trycount++;
		}while (($trycount < 2) && ((!$server_result) || (empty($server_result))));
		$response_data = $server_result;

		if (empty($server_result) || ($server_result == ""))
		{
//log the error
			$response_data = $this->array_implode('=', ',', $server_error) . " info:" . $this->array_implode('=', ',', $server_info);
			return $this->keep_error_message("request timeout, please try again");
		}
		else
		{
			$parse_result = $this->_trustly_integrator->get_submit_result($server_result);
			if ($parse_result)
			{
				if ($parse_result->result)
				{
					$result = $parse_result->result;
					if ($result->error)
					{
						return $this->keep_error_message($result->error->data->code . " " . $result->error->data->message);
					}
					else
					{
						if ($result->data->orderid)
						{
							$this->so->set_txn_id($result->data->orderid);
							$this->get_so_srv()->get_dao()->update($this->so);
						}
						return $result->data->url;
					}
				}
			}
		}

		return $this->keep_error_message("cannot retrieve redirect url, please try again");
	}

	public function test_notification()
	{
		$data = json_encode(array("test" => "1", "test2" => array("leve2" => 1)));
		$this->_trustly_integrator->setServer("dev.valuebasket.es/en_ES/checkout_redirect_method/trustly_payment_notification/trustly/1");
		$server_result = "";
		$server_error = "";
		$this->_trustly_integrator->send_data_to_trustly($data, $server_result, $server_error, $server_info);
	}

	public function process_payment_status($general_data = array(), $get_data = array(), &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
	{
//we do nothing because trustly won't send any information to us
	}

	public function process_failure_action()
	{
		return true;
	}

	public function process_cancel_action()
	{
		return true;
	}

	public function process_success_action()
	{
		return true;
	}

	public function is_payment_need_credit_check($is_fraud = false)
	{
		return parent::is_payment_need_credit_check($is_fraud);
	}

	public function is_need_dm_service($is_fraud = false)
	{
		return parent::require_decision_manager($is_fraud);
	}

	public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
	{
		return true;
	}

	public function payment_notification($json_data)
	{
		$dataResponse = json_decode($json_data, true);
//we use messageid to store the so_no
		$so_no = $dataResponse['params']['data']['messageid'];

//		mail($this->get_technical_support_email(), '[VB] Trustly notification, temp', $json_data, 'From: website@valuebasket.com');
		$so_srv = $this->get_so_srv();

		if ($dataResponse)
			$this->get_sopl_srv()->add_log($so_no, "I", $json_data);

		if ($this->so = $so_srv->get(array("so_no" => $so_no)))
		{
			$method = $dataResponse['method'];
			$uuid = $dataResponse['params']['uuid'];
//			error_log("trustly log:" . $so_no);
//			mail($this->get_technical_support_email(), '[VB] Trustly notification, tracking - so_no:' . $this->so->get_client_id() . '-' . $so_no, $json_data, 'From: website@valuebasket.com');
			if ($this->_trustly_integrator->verifySignature($method, $uuid, $dataResponse['params']['data'], $dataResponse['params']['signature']))
			{
				if ($method == "credit")
				{
					$amount = $dataResponse['params']['data']['amount'];
					$currency = $dataResponse['params']['data']['currency'];
					$trustlyOrderId = $dataResponse['params']['data']['orderid'];

					if (($this->so->get_amount() == $amount) && ($this->so->get_currency_id() == $currency))
					{
						$sops_dao = $this->get_so_srv()->get_sops_dao();
						$this->sops = $sops_dao->get(array("so_no"=>$so_no));
						$sops_data = array();
						$socc_data = array("fd_status" => 0);

						if ($this->sops->get_payment_status() != 'S')
						{
							$this->payment_success_operation(array(), $sops_data, $socc_data);
//during debug, if email was echo, it will affect the reponse to trustly
							$this->fire_success_event();
						}
						$process_success = true;
					}
					else
					{
//reply ok, as advised by trustly that we received the notification, the order status is still 1, need someone to verify
						$process_success = true;
						mail($this->get_technical_support_email(), '[VB] Trustly notification, invalid order amount - so_no:' . $this->so->get_client_id() . '-' . $so_no, $json_data, 'From: website@valuebasket.com');
						if (($this->so->get_status() < 2)
							&& ($this->so->get_refund_status() == 0))
						{
//do auto refund only if this is not refunded before
							$this->_process_auto_refund($trustlyOrderId, $amount, $currency);
						}
					}
				}
				else if ($method == "debit")
				{
//we don't handle debit
					$process_success = true;
				}

				if ($process_success)
				{
					$response = $this->_trustly_integrator->send_notification_response($method, $uuid);
					if ($response)
						$this->get_sopl_srv()->add_log($so_no, "O", $response);
					return;
				}
			}
			else
			{
	//send technical email to alert IT
				mail($this->get_technical_support_email(), '[VB] Trustly notification, cannot verify signature - so_no:' . $this->so->get_client_id() . '-' . $so_no, $json_data, 'From: website@valuebasket.com');
			}
		}
	}

	private function _process_auto_refund($trustlyOrderId, $amount, $currency)
	{
		$refund_amount = $amount;
		if ($this->so->get_currency_id() != $currency)
		{
//converted to system currency
			$refund_amount = $this->convert_amount($amount, $currency, $this->so->get_currency_id());
		}
//set order status, refund status, etc...
		$is_refunded = $this->refund_so($refund_amount, 'Trustly, system auto refund, incorrect amount');
		if ($is_refunded)
		{
//do auto refund
			$result = $this->auto_refund($trustlyOrderId, $amount, $currency);
			if ($result)
			{
				error_log("refund done");
				mail($this->get_technical_support_email(), '[VB] Trustly Auto Refund done - so_no:' . $this->so->get_client_id() . '-' . $this->so->get_so_no(), "", 'From: website@valuebasket.com');
			}
			else
			{
				error_log("refund fail from trustly");
				mail($this->get_technical_support_email(), '[VB] Trustly Auto Refund error from trustly- so_no:' . $this->so->get_client_id() . '-' . $this->so->get_so_no(), $this->error_message, 'From: website@valuebasket.com');
			}
		}
		else
		{
			mail($this->get_technical_support_email(), '[VB] Trustly Auto Refund fail(DB status update) - so_no:' . $this->so->get_client_id() . '-' . $this->so->get_so_no(), "", 'From: website@valuebasket.com');
		}
	}

	public function auto_refund($trustlyOrderId, $amount, $currency)
	{
		$requestInformation = array();
		$requestInformation['orderId'] = $this->so->get_so_no();
		$requestInformation['refundAmount'] = $amount;
		$requestInformation['currency'] = $currency;
		$requestInformation['trustly_order_id'] = $trustlyOrderId;

		$json_data = $this->_trustly_integrator->form_refund_array($requestInformation);

		$trycount = 0;
		$server_result = '';
		$server_error = '';
		$server_info = '';
		do
		{
			$this->_trustly_integrator->send_data_to_trustly($json_data, $server_result, $server_error, $server_info);
			$trycount++;
		}while (($trycount < 2) && ((!$server_result) || (empty($server_result))));

		if ($json_data)
			$this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", str_replace("&", "\n&", $json_data));

		if (empty($server_result) || ($server_result == ""))
		{
//log the error
			$response_data = $this->array_implode('=', ',', $server_error) . " info:" . $this->array_implode('=', ',', $server_info);
			if ($response_data)
				$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $response_data));
			return $this->keep_error_message("request refund timeout, please try again");
		}
		else
		{
			$this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $server_result));
			$parse_result = $this->_trustly_integrator->get_submit_result($server_result);
			if ($parse_result)
			{
				if ($parse_result->error)
				{
					$error = $parse_result->error;
					return $this->keep_error_message($error->code . " " . $error->error->data->message . " amount:" . $amount);
				}
				elseif ($parse_result->result)
				{
					$result = $parse_result->result;
					if ($result->error)
					{
						return $this->keep_error_message($result->error->data->code . " " . $result->error->data->message);
					}
					else
					{
						if (($result->data->orderid) && ($result->data->result == 1))
						{
							return true;
						}
					}
				}
			}
		}

		return $this->keep_error_message("cannot refund, please try again");
	}

	public function convert_amount($amount, $from_currency, $to_currency)
	{
		include_once(APPPATH . "libraries/service/Exchange_rate_service.php");
		$this->exchange_rate_service = new Exchange_rate_service();

		$ex_rate_obj = $this->exchange_rate_service->get(array("from_currency_id" => $from_currency, "to_currency_id" => $to_currency));
		if ($ex_rate_obj)
		{
			$ex_rate = $ex_rate_obj->get_rate();
			return number_format(($amount * $ex_rate), 2, '.', '');
		}
		return $amount;
	}

	public function get_technical_support_email()
	{
		return "oswald-alert@eservicesgroup.com";
	}
}
