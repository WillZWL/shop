<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Altapay/altapay_integrator.php");

class Payment_gateway_redirect_altapay_service extends Payment_gateway_redirect_service
{
	private $_altapay_integrator;
    public $config_service;
	public $credit_check_3d_amount = array("ES" => 350
											, "IT" => 350);
	public $credit_check_3d_amount_level2 = array("ES" => 100
											, "IT" => 100);

	public function __construct($debug = 0)
	{
		parent::__construct($debug);
        $this->_altapay_integrator = new Altapay_integrator($debug);
		include_once(APPPATH . "libraries/service/Context_config_service.php");
		$this->config_service = new Context_config_service();
	}

	public function get_payment_gateway_name()
	{
		return "altapay";
	}

	public function get_technical_support_email()
	{
		return "oswald-alert@eservicesgroup.com";
	}

	public function prepare_get_url_request($payment_info = array(), &$request_data)
	{
//		$card_id = $payment_info["card_type"];

		$formData = $this->_altapay_integrator->form_payment_request($this->so, $this->client, $this->get_payment_response_page());
        $request_data = @http_build_query($formData);
		return $formData;
	}

    public function get_payment_response_page()
    {
        $url = parent::get_payment_response_page();
        return str_replace("https://", "http://", $url);
    }

	public function get_redirect_url($request_data, &$response_data)
	{
		$error_occur = false;
		$trycount = 0;
		do
		{
			$output = $this->_altapay_integrator->submitCreatePaymentRequest($request_data);
			$trycount++;
		}while (($trycount < 2) && (!empty($output["error"])));

		if (($output != "") && ($output["error"] == ""))
		{
			$response_data = $output["result"];
			$xmlReponse = simplexml_load_string($response_data);
			if (isset($xmlReponse->Body->Result) && (((string) $xmlReponse->Body->Result) == "Success"))
			{
				$redirectUrl = (string) $xmlReponse->Body->Url;
			}
			else
			{
				$error_occur = true;
			}
		}
		else
		{
			$response_data = $output["error"] . " " . $this->array_implode('=', ',', $output["info"]);
			$error_occur = true;
		}

		if ($error_occur)
		{
			$implodeData = $this->array_implode('=', ',', $request_data);
			$down_message = "Session: " . $session . "Please contact " . $this->get_payment_gateway_name() . ", IT please consider to switch payment gateway." . "O:" . $implodeData . ", I:" . $response_data;
			mail($this->sitedown_email, $this->get_payment_gateway_name() . " payment issue", $down_message, 'From: website@valuebasket.com');
			return "ERROR::" . base_url() . "checkout_onepage/payment_result/0/{$this->so->get_so_no()}?type=sitedown";
		}
		return $redirectUrl;
	}

	public function payment_notification($input_data)
	{
		$simpleXml = new SimpleXMLElement($input_data);
    }

    public function get_pending_schedule_id()
    {
        return "ALTAPAY_ORDERS_VERIFICATION";
    }

	public function update_pending_list()
	{
//check and reset the altapay limit
        $this->_check_and_reset_altapay_limit();

		include_once(APPPATH."libraries/dao/Schedule_job_dao.php");
		$scj_dao = new Schedule_job_dao();

		$schedule_id = $this->get_pending_schedule_id();
		$sjob_obj = $scj_dao->get(array("id" => $schedule_id, "status" => "1"));
		if ($sjob_obj)
		{
			$last_access = $sjob_obj->get_last_access_time();
			$timeShift = 60 * 30;
			$start_time = strtotime($last_access) - $timeShift;
			$end_time = date('Y-m-d H:i:s');
			$shiftedEndTime = date("Y-m-d H:i:s", (strtotime($end_time) - $timeShift));
			$sops_dao = $this->get_so_srv()->get_sops_dao();
			$sops_list = $sops_dao->get_list(array("payment_gateway_id" => $this->get_payment_gateway_name()
												, "payment_status" => "P"
												, "create_on >" => date("Y-m-d H:i:s", $start_time)
												, "create_on <=" => $shiftedEndTime)
										, array("limit" => -1));

			foreach($sops_list as $sops)
			{
				$this->query_payment_status_in_general($sops->get_so_no());
			}
			$sjob_obj->set_last_access_time($end_time);
			$scj_dao->update($sjob_obj);
		}
	}

	public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
	{
        $so_no = $input_parameters["so_no"];

		$requestData = $this->_altapay_integrator->form_transaction_query($input_parameters, $this->so->get_currency_id());
		$data_to_pmgw = $this->array_implode('=', ',', $requestData);

		$trycount = 0;
		do
		{
			$output = $this->_altapay_integrator->submitCreateQueryPaymentRequest($requestData);
			$trycount++;
		}while (($trycount < 2) && ((!$output) || (empty($output["result"]))));

        $data_from_pmgw = $output["result"];
        $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
		if ($output["result"])
		{
			$simpleXml = new SimpleXMLElement($output["result"]);
            $this->_extractXmlData($output["result"], $sops_data, $socc_data);
			$numberOfTransactions = sizeof($simpleXml->Body->Transactions->Transaction);
			for ($i=0;$i<$numberOfTransactions;$i++)
			{
				if ((string) $simpleXml->Body->Transactions->Transaction[$i]->TransactionStatus)
				{
					$transactionResult = (string) $simpleXml->Body->Transactions->Transaction[$i]->TransactionStatus;
					$currency_id = (string) $simpleXml->Body->Transactions->Transaction[$i]->MerchantCurrency;
					$amount = (string) $simpleXml->Body->Transactions->Transaction[$i]->CapturedAmount;

					if ($transactionResult == "captured")
					{
						if (($this->currency_id_mapping[$this->so->get_currency_id()] != $currency_id)
							|| ($amount < $this->so->get_amount()))
						{
							$message = $data_from_pmgw;
							mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " currency/amount mismatch in query, so_no:" . $so_no, $message, 'From: website@valuebasket.com');
						}
						else
						{
							$payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
						}
						break;
					}
				}
				else if ((string) $simpleXml->Body->Transactions)
				{
// this checking may not be too good, as we don't know multiple transaction, need monitor
					$message = $data_from_pmgw;
					mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " no status in query, so_no:" . $so_no, $message, 'From: website@valuebasket.com');
				}
			}
		}
        else
        {
            $message = $data_from_pmgw;
            mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " no result in query, so_no:" . $so_no, $message, 'From: website@valuebasket.com');
        }
		return $payment_result;
	}

	public function process_payment_status($general_data = array(), $get_data = array(), &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
	{
        $so_number = $general_data["shop_orderid"];
        $transactionId = $general_data["transaction_id"];
        $currency = $general_data["currency"];
        $amount = $general_data["amount"];
        $paymentType = $general_data["type"];
        $xml_response = $general_data["xml"];
        $checksum = $general_data["checksum"];

        $calculatedCheckSum = $this->_altapay_integrator->calculateChecksum(array("amount" => $amount
                                                            , "currency" => $currency
                                                            , "shop_orderid" => $so_number));

//        error_log($calculatedCheckSum . __METHOD__ . __LINE__ . " " . $checksum);
        $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
        $data_from_pmgw = $this->array_implode('=', ',', $general_data);

        if ($calculatedCheckSum != $checksum)
        {
//alert, wrong checksum
            $message = $data_from_pmgw;
            mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Wrong checksum, so_no:" . $so_number, $message, 'From: website@valuebasket.com');
        }
        else if ($paymentType != "paymentAndCapture")
        {
//alert, not capture
            $message = "paymentType is not paymentAndCapture";
            mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Wrong payment type, so_no:" . $so_number, $message, 'From: website@valuebasket.com');
        }
        else
        {
            $this->so = $this->get_so_srv()->get_dao()->get(array("so_no" => $so_number));
            if ($this->so)
            {
                $so_number = $this->so->get_so_no();
                $so_data["txn_id"] = $transactionId;

                if ($general_data["status"] == "succeeded")
                {
                    $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
                }
//prevent fraud attack checking
                if (($general_data["payment_status"] != "captured")
					&& ($general_data["payment_status"] != "3dsecure_error")
					&& ($general_data["payment_status"] != "3dsecure_sale_started")
					&& ($general_data["payment_status"] != "sale_failed")
					&& ($general_data["payment_status"] != "sale_error")
					&& ($general_data["payment_status"] != "3dsecure_failed"))
                {
                    $message = $data_from_pmgw;
                    mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Payment not captured, so_no:" . $so_number, $message, 'From: website@valuebasket.com');
                }
//prevent fraud attack checking, check currency and amount
                if (($this->currency_id_mapping[$this->so->get_currency_id()] != $currency)
                    || ($this->so->get_amount() < $amount))
                {
                    $message = $data_from_pmgw;
                    mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Payment currency/amount not match, so_no:" . $so_number, $message, 'From: website@valuebasket.com');
                }
                $this->_extractXmlData($xml_response, $sops_data, $socc_data, $transactionId);
            }
            else
            {
                $message = $data_from_pmgw;
                mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Cannot get so_no", $message, 'From: website@valuebasket.com');
            }
        }
		return $payment_result;
	}

    private function _extractXmlData($xml, &$sops_data, &$socc_data, $transactionId = null)
    {
        $xmlReponse = simplexml_load_string($xml);
        if ((($transactionId)
            && (((string) $xmlReponse->Body->Transactions->Transaction[0]->TransactionId) == $transactionId))
            || ($transactionId == null))
        {
            if ((string) $xmlReponse->Body->Transactions->Transaction->CreditCardMaskedPan)
            {
                $creditCard = (string) $xmlReponse->Body->Transactions->Transaction->CreditCardMaskedPan;
                $socc_data["card_last4"] = substr($creditCard, -4);
            }
            if ((string) $xmlReponse->Body->Transactions->Transaction->CreditCardExpiry->Year)
            {
                $expiryYear = (string) $xmlReponse->Body->Transactions->Transaction->CreditCardExpiry->Year;
                $socc_data["card_exp_year"] = $expiryYear;
            }
            if ((string) $xmlReponse->Body->Transactions->Transaction->CreditCardExpiry->Month)
            {
                $expiryMonth = (string) $xmlReponse->Body->Transactions->Transaction->CreditCardExpiry->Month;
                $socc_data["card_exp_month"] = $expiryMonth;
            }
            if ((string) $xmlReponse->Body->Transactions->Transaction->ThreeDSecureResult)
            {
                $sops_data["risk_ref2"] = (string) $xmlReponse->Body->Transactions->Transaction->ThreeDSecureResult;
            }
            if ((string) $xmlReponse->Body->Transactions->Transaction->CVVCheckResult)
            {
                $sops_data["risk_ref3"] = (string) $xmlReponse->Body->Transactions->Transaction->CVVCheckResult;
            }
            if ((string) $xmlReponse->Body->Transactions->Transaction->CardStatus)
            {
                $sops_data["risk_ref4"] = (string) $xmlReponse->Body->Transactions->Transaction->CardStatus;
            }
        }
    }

    protected function payment_success_operation($so_para = array(), $sops_para = array(), $socc_para = array(), $sor_data = array())
    {
        parent::payment_success_operation($so_para, $sops_para, $socc_para, $sor_data);
        $this->check_altapay_limit_reach();
    }

    public function _check_and_reset_altapay_limit()
    {
        $para = $this->config_service->value_of("altapay_para");
        $para_arr = explode("||", $para);
        $cut_off_day = $para_arr[0];
        $todayDay = date("d");

        if ($cut_off_day == $todayDay)
        {
            $altapay_limit_reach = $this->config_service->value_of("altapay_limit_reach");
            if (($altapay_limit_reach) && (!$this->check_altapay_limit_reach()))
            {
                $configObj = $this->config_service->get_dao()->get(array("variable" => "altapay_limit_reach"));
                $configObj->set_value(0);
                $configUpdateResult = $this->config_service->get_dao()->update($configObj);
                if ($configUpdateResult === false)
                {
                    $message = $this->config_service->get_dao()->db->last_query();
                    mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " cannot update altapay_limit_reach to 0", $message, 'From: website@valuebasket.com');
                }
            }
        }
    }

    public function check_altapay_limit_reach()
    {
//        $limit_is_reach = $this->config_service->value_of("altapay_limit_reach");
//limit not reach, we check it
        $para = $this->config_service->value_of("altapay_para");
        $para_arr = explode("||", $para);
        $cut_off_day = $para_arr[0];
        $altapay_amount_limit = $para_arr[1];

        $todayDay = date("d");
        if ($cut_off_day <= $todayDay)
        {
            $start_date = date("Y-m") . "-" . $cut_off_day . " 00:00:00";
            $end_date = date("Y-m-d H:i:s");
        }
        else
        {
            $last_month = date("n") - 1;
            $year = date("Y");
            if ($last_month == 0)
            {
                $year = $year - 1;
                $last_month = 12;
            }
            $start_date = $year . "-" . $last_month . "-" . $cut_off_day . " 00:00:00";
            $end_date = date("Y-m-d H:i:s");
        }

        $current_total_amount = $this->get_so_srv()->get_dao()->get_altapay_captured_amount($start_date, $end_date);
/*
error_log(__METHOD__ . __LINE__);
error_log($current_total_amount[0]["total_amount"]);
error_log($altapay_amount_limit);
*/
        if ($current_total_amount[0]["total_amount"] >= $altapay_amount_limit)
        {
//error_log(__METHOD__ . __LINE__);
            $configObj = $this->config_service->get_dao()->get(array("variable" => "altapay_limit_reach"));
            $configObj->set_value(1);
            $configUpdateResult = $this->config_service->get_dao()->update($configObj);
            if ($configUpdateResult === false)
            {
                $message = $this->config_service->get_dao()->db->last_query();
                mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " cannot update altapay_limit_reach to 1", $message, 'From: website@valuebasket.com');
            }
            return true;
        }
        else
        {
//error_log(__METHOD__ . __LINE__);
            return false;
        }
    }

	public function process_failure_action()
	{
        header("Location:" . $this->_get_failure_page());
	}

	public function process_cancel_action()
	{
// no cancel button
//        header("Location:" . $this->_get_failure_page());
	}

	public function process_success_action()
	{
        $this->fire_success_event();
		mail("altapay_payment@eservicesgroup.com", "Payment by Altapay so_no" . $this->so->get_so_no(), "Amount:" . $this->so->get_amount(), 'From: website@valuebasket.com');
        header("Location:" . $this->_get_successful_page());
	}

	private function _get_successful_page($so_number = null)
	{
		return $this->get_successful_page_top($so_number);
	}

	public function _get_failure_page()
	{
		return $this->get_failure_page_top();
	}

	public function is_payment_need_credit_check($is_fraud = false)
	{
		return $this->is_payment_need_credit_check_3D($is_fraud);
	}

	public function is_need_dm_service($is_fraud = false)
	{
		return $this->is_payment_need_credit_check_3D($is_fraud);
	}

	public function is_payment_need_credit_check_3D($is_fraud = false)
	{
		if ($is_fraud)
		{
			return false;
		}
		else
		{
			$amount = $this->so->get_amount();
			$threeDResult = $this->sops->get_risk_ref2();
			$cvvResult = $this->sops->get_risk_ref3();
			$country_id = $this->so->get_bill_country_id();

			if (($threeDResult == "Successful") && ($cvvResult == "Matched"))
			{
				if ((array_key_exists($country_id, $this->credit_check_3d_amount))
					&& ($amount > $this->credit_check_3d_amount[$country_id]))
					return true;
				else
					return false;
			}
			else
			{
				if ((array_key_exists($country_id, $this->credit_check_3d_amount_level2))
					&& ($amount < $this->credit_check_3d_amount_level2[$country_id]))
					return false;
				else
					return true;
			}
		}
		return true;
	}
}

