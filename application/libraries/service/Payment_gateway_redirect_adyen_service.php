<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");
include_once(APPPATH . "libraries/service/Adyen/adyen_integrator.php");
include_once(APPPATH . "libraries/service/So_service.php");
include_once(APPPATH . "libraries/service/Db_text_lookup_service.php");

// root sbf #4882
class Payment_gateway_redirect_adyen_service extends Payment_gateway_redirect_service
{
	private $_adyen_integrator;
    public $config_service;

    const DEV_NOTIFICATION_HEX_HMAC_VALUEBASKETBE = "16A1128E5320F3C2DEF8F8BA344D1E3654154F3499D364B1A5F3E57F130E9407";
    const DEV_NOTIFICATION_HEX_HMAC_VALUEBASKETFR = "98F5B31CEF31975A4CDC16BBB9A7DC5E82D7DF09CD91DBEE91547E646E292FBA";
    const DEV_NOTIFICATION_HEX_HMAC_VALUEBASKETIE = "161B00C6E01775EA4FDFEBF9FFF8DF7A6A5CC7D4FA911ABF25DDBEFAE98FF511";
    const DEV_NOTIFICATION_HEX_HMAC_VALUEBASKETUK = "F7F6D70AD80D808D4D984808CFB2E6534D2EB8D84724B5E30A9F67AAB32AF2E2";

    const NOTIFICATION_HEX_HMAC_VALUEBASKETBE = "2939E4F5875B83469685D9492C44E137CD41B39FCEB208C55F6C0416A4756AE9";
    const NOTIFICATION_HEX_HMAC_VALUEBASKETFR = "11EFD8C75C363A9A099484E63B08BF11B02BD4CB7E16BF6EA24F1263CD933C6D";
    const NOTIFICATION_HEX_HMAC_VALUEBASKETIE = "7487568071E3D2A812B6629C45730B32925C8A706E1FDCA860EE228E7A468E5C";
    const NOTIFICATION_HEX_HMAC_VALUEBASKETUK = "6A8D8D2854464C0BBF2CC188672735D68D06270DBAE4781EB82473DE2DE0D27F";

	public function __construct($debug = 0)
	{
		parent::__construct($debug);
        $this->_adyen_integrator = new Adyen_integrator($debug);
        $this->so_service = new So_service();
        $this->db_text_lookup_service = new Db_text_lookup_service();
		include_once(APPPATH . "libraries/service/Context_config_service.php");
		$this->config_service = new Context_config_service();

        $this->notification_email = "ping-alert@eservicesgroup.com,rachel@eservicesgroup.net";
	}

	public function get_payment_gateway_name()
	{
		return "adyen";
	}

	public function get_technical_support_email()
	{
		return "ping-alert@eservicesgroup.com";
	}

	public function prepare_get_url_request($payment_info = array(), &$request_data)
	{
		$card_id = $payment_info["card_type"];       // skinCode
        $card_code = $payment_info["card_code"];    // ay_VSA
		$formData = $this->_adyen_integrator->form_payment_request($this->so, $this->client, $card_id, $card_code, $this->get_payment_response_page());
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
			$output = $this->_adyen_integrator->submitCreatePaymentRequest($request_data);
			$trycount++;
		}while (($trycount < 2) && (!empty($output["error"])));

		if (!empty($output) && empty($output["error"]))
		{
			$response_data = $output["result"];
			if ($response_data)
			{
				$redirectUrl = $response_data;
			}
			else
			{
				$error_occur = true;
			}
		}
		else
		{
			$response_data = $output["error"] . " " . "\r\n{$output["result"]}";
			$error_occur = true;
		}

		if ($error_occur)
		{
			$down_message = "Session: " . $session . "Please contact " . $this->get_payment_gateway_name() . ", IT please consider to switch payment gateway." . "O:" . $request_data . ", I:" . $response_data;
			mail($this->sitedown_email, $this->get_payment_gateway_name() . " payment issue", $down_message, 'From: website@valuebasket.com');
			return "ERROR::" . base_url() . "checkout_onepage/payment_result/0/{$this->so->get_so_no()}?type=sitedown";
		}
		return $redirectUrl;
	}


    public function get_pending_schedule_id()
    {
        return "ADYEN_ORDERS_VERIFICATION";
    }

	public function process_payment_status($general_data = array(), $get_data = array(), &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
	{
        // adyen redirecting back after customer pays will come into here
        // called by checkout_redirect_method/payment_response
        $so_number = $get_data["merchantReference"];
        $pspReference = $get_data["pspReference"];
        $authResult = $get_data["authResult"];
        $skinCode = $get_data["skinCode"];
        $merchantSig = $get_data["merchantSig"];
        $paymentMethod = $this->paymentMethod = $get_data["paymentMethod"];
        $shopperLocale = $get_data["shopperLocale"];

        $data_from_pmgw = $this->array_implode('=', ',', $get_data);
        $data_to_pmgw = null;
        $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;

        $sops_data = array("pay_date"=>date("Y-m-d H:i:s"), "payer_ref"=>$pspReference, "card_id"=>$skinCode);
        $sops_dao = $this->get_so_srv()->get_sops_dao();
        $this->sops = $sops_obj = $sops_dao->get(array("so_no"=>$so_number));

        $this->so = $this->get_so_srv()->get_dao()->get(array("so_no" => $so_number));
        if($authResult == "AUTHORISED")
        {
            if($this->so)
            {
                $so_number = $this->so->get_so_no();
                $so_data["txn_id"] = $pspReference;
                $socc_data["fd_proc_status"] = 0;
                $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
            }
            else
            {
                $message = $data_from_pmgw;
                mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Cannot get so_no", $message, 'From: website@valuebasket.com');
            }
        }
        // elseif($authResult == "PENDING")
        // {

        // }
        // elseif($authResult == "CANCELLED")
        // {

        // }
        else
        {
            //https://dev.valuebasket.be/fr_BE/checkout_redirect_method/payment_response?payment_type=adyen&merchantReference=585839&skinCode=OqigxE3L&shopperLocale=fr_BE&authResult=CANCELLED&merchantSig=R6vdPBru88A5O0KDCTh4l0LvOn4%3D
            $payment_result = Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;

            $remark = $authResult;
            $originalremark = $sops_obj->get_remark();
            if(!empty($originalremark) && strpos($originalremark, $authResult) === false)
                $remark = "$originalremark || $authResult";

            $sops_data["remark"] = $remark;
            $message = $data_from_pmgw;
            mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " Payment not Authorised", $message, 'From: website@valuebasket.com');
        }

        return $payment_result;

	}


    public function payment_notification($postData)
    {
        /*
            *   @$pspReference is the unique reference that Adyen assigned to the payment or modification.
            *   @$originalReference: If this is a notifcation for a modifcation request this will be the pspReference that was originally assigned to the
                authorisation, for a payment it will be blank.
            *   @$operations displays modification operations supported by this payment, will inform you the action needed: CAPTURE, REFUND, CANCEL.
                As of 2014-11-06, we do not send modifications via API and we do auto-capture, so $operations should not be in use.
            *   @$reason gives info on whether result is successful or not.  For AUTHORISATION events with
                the success feld set to true and a payment method of visa, mc or amex this feld contains the authorisation
                code, the last 4 digits of the card, and the expiry date in the following format:
                6 digit Authorisation Code:Last 4 digits:Expiry Date. For example, e.g. 874574:1935:11/2012.
                When the success feld is set to false it gives a reason as to why it was refused. For REPORT_AVAILABLE it contains the URL where
                the report can be downloaded from.
        */
        $debug = $this->_debug;
        if($postData)
        {

            $is_live             = $this->is_live = $postData["live"];
            $eventCode           = $this->eventCode = $postData["eventCode"];
            $success             = $this->success = (string)$postData["success"];
            $pspReference        = $this->pspReference = (string)$postData["pspReference"];
            $originalReference   = $this->originalReference = (string)$postData["originalReference"];
            $merchantReference   = $this->merchantReference = $postData["merchantReference"];
            $merchantAccountCode = $this->merchantAccountCode = $postData["merchantAccountCode"];
            $eventDate           = $postData["eventDate"];
            $hmacSignature       = $postData["additionalData_hmacSignature"];

            $currency = $postData["currency"];
            $value = $postData["value"];    # order value. adyen gives its number without decimal. e.g. $1000.80 will be 100080
            $reason = $this->reason = $postData["reason"];
            $paymentMethod = $this->paymentMethod = $postData["paymentMethod"];
            $operations = $postData["operations"];

            // if($postData["value"] != "")
            //     $value = substr($postData["value"], 0, -2) . "." . substr($postData["value"], -2); #adyen gives its number without decimals
            // else
            //     $value = $postData["value"];

            $this->data_from_pmgw = $data_from_pmgw = $this->array_implode('=', ', ', $postData);
            $checkstring = "$pspReference:$originalReference:$merchantAccountCode:$merchantReference:$value:$currency:$eventCode:$success";
            $check_signature = $this->_check_signature($checkstring, $hmacSignature, $merchantAccountCode);
            $this->check_signature_status = $check_signature["status"];
// $this->check_signature_status = true;
            // always need to print this to acknowledge, else may jam up notifications on adyen
            print "[accepted]";

            if($check_signature["status"] === false)
            {
                $message = "calculated hmac:" . $ret["calculatedhmac"] . ", hmacSignature:" . $hmacSignature . ", \r\ndata_from_pmgw: " . $data_from_pmgw;
                $message .= $ret["message"];
                $this->send_email("CHECK_ORDER_SIG_FAIL", $message);
            }

            if(strtoupper($eventCode) == "REPORT_AVAILABLE")
            {
                $this->send_email('ADYEN_REPORT', "Notification @ $eventDate. \nDownload report at $reason");
            }
            else
            {
                $so_obj = $this->so_service->get_dao()->get(array("so_no"=>$merchantReference));
                if($so_obj)
                {
                    $so_no = $so_obj->get_so_no();

                    // calls Payment_gateway_redirect_service, but process with query_transaction() back here
                    $this->query_payment_status_in_general($so_no);

                }
                else
                {
                    $message = "No SO object found for $merchantReference. DB error: {$this->so_service->get_dao()->db->_error_message()}.\n";
                    $this->send_email("NO_DB_DATA", $message, $postData);
                }
            }
        }
    }

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
    {
        return $this->_process_by_get_order_status($this->so->get_so_no(), $sops_data, $socc_data);
    }

    private function _process_by_get_order_status($so_number, &$sops_data, &$socc_data)
    {
        /* ==================================================================================================
            @eventCode refers to the type of notification, and its status is determined by "success" field.
            @eventCode = AUTHORISATION is for a normal payment event.
            NOTE that eventCode
            SBF #4882 - Integration Manual PG25
                - Modification Payment eventCodes refers to modification made via API only. As of 2014-11-08, we are not using.
                - NOTE @eventCode = "REFUNDED_REVERSED" (modification) will always return success = false.
            @eventCode for disputes include:
                REQUEST_FOR_INFORMATION, NOTIFICATION_OF_CHARGEBACK, ADVICE_OF_DEBIT, CHARGEBACK, CHARGEBACK_REVERSED
         ================================================================================================== */

        // add in log for the data constructed in payment_notification()
        $this->get_sopql_service()->add_log($so_number, "I", $this->data_from_pmgw);

        if($this->is_live == "false")
            $message = "THIS IS A TEST.\r\n";

        // only continue to process if signature status is success
        if($this->check_signature_status)
        {
            if($this->eventCode == "AUTHORISATION")
            {
                // update ddl on db!
                $resp_3doffered = $this->resp_3doffered = (string)$_POST["additionalData_threeDOffered"];
                $resp_3dauth = $this->resp_3dauth = (string)$_POST["additionalData_threeDAuthenticated"];
                $resp_liabilityshift = $this->resp_3doffered = (string)$_POST["additionalData_liabilityShift"];

                $resp_fraudresult = $_POST["additionalData_totalFraudScore"];
                $resp_authcode = $_POST["additionalData_authCode"];     # 6 digit authorisation code
                $resp_refusalreason = (string)$_POST["additionalData_refusalReasonRaw"];
                $resp_ccno = $_POST["additionalData_cardSummary"];
                list($exp_month, $exp_year) = explode('/', $_POST["additionalData_expiryDate"]);
                $resp_name = (string)$_POST["additionalData_cardHolderName"];
                $resp_cardtype = (string)$this->paymentMethod;

                // $resp_fraudresult2 = $_POST["additionalData_fraudCheck-6-ShopperIpUsage"];

                if ($resp_3dauth != "")
                    $sops_data["risk_ref1"] = "3DOffer:{$resp_3doffered}||3DAuth:{$resp_3dauth}||LiabilityShift:{$resp_liabilityshift}";

                if ($resp_fraudresult != "")
                    $sops_data["risk_ref2"] = $resp_fraudresult;
                if ($resp_authcode != "")
                    $sops_data["risk_ref3"] = $resp_authcode;
                if($resp_refusalreason != "")
                    $sops_data["risk_ref4"]= $resp_refusalreason;
                if ($resp_ccno != "")
                    $socc_data["card_last4"] = $resp_ccno;
                if($exp_month != "")
                    $socc_data["card_exp_month"] = $exp_month;
                if($exp_year != "")
                    $socc_data["card_exp_year"] = $exp_year;
                if($resp_name != "")
                    $socc_data["card_holder"] = $resp_name;
                if($resp_cardtype != "")
                    $socc_data["card_type"] = $resp_cardtype;

                /*
                    in a case of modification, pspReference may be a new unique number, and originalReference is the original
                    reference number of the event
                */
                if( ($this->pspReference != "" || $this->pspReference != NULL) &&  strpos($this->so->get_txn_id(), $this->pspReference) === false)
                {
                    if($this->so->get_txn_id() == NULL || $this->so->get_txn_id() == "")
                        $so_data["txn_id"] = $this->pspReference;
                    else
                        $so_data["txn_id"] = $this->so->get_txn_id() . "||" . $this->pspReference;

                    set_value($this->so, $so_data);
                    $this->get_so_srv()->get_dao()->update($this->so);

                    if($this->so->get_status() > 1)
                    {
                        $message = "This may be a Adyen Duplicate; SO#:$so_number, pspReference 1: {$this->so->get_txn_id()}, pspReference 2: {$this->pspReference}";
                        mail("compliance@valuebasket.com, ping@eservicesgroup.com", "[VB] ADYEN DUPLICATE PAYMENT", $message);
                    }
                }

                if(is_array($sops_data))
                {
                    $sops_dao = $this->get_so_srv()->get_sops_dao();

                    if($sops_obj = $sops_dao->get(array("so_no"=>$so_number)))
                    {
                        $sops_action = "update";
                    }
                    else
                    {
                        $sops_obj = $sops_dao->get();
                        $sops_data["so_no"] = $so_number;
                        $sops_action = "insert";
                    }

                    include_once(APPPATH."helpers/object_helper.php");
                    set_value($sops_obj, $sops_data);

                    // We update 3D and fraud info into so_payment_status first, "success" actions will do necessary updates to payment_status later
                    if($sops_dao->$sops_action($sops_obj) === false)
                    {
                        $message .= __LINE__." Error $sops_action so_payment_status after getting Adyen notification. Db error: \n{$sops_dao->db->_error_message()}";
                        $this->send_email("ERROR_WRITE_DB", $message);
                    }
                }

                if($_POST)
                {
                    foreach ($_POST as $key => $value)
                    {
                       $debugcontent .= "$key: $value \n";
                    }
                    mail("ping@eservicesgroup.com", "ADYEN DEBUG", $debugcontent);
                }

                $message .= "VB order $so_number - Adyen AUTHORISATION status: {$this->success}. \r\n";
                $message .= "3D Offered: $resp_3doffered";
                if($resp_3doffered != "false")
                    $message .= " || 3D Authenticated: $resp_3dauth || Total Fraud Score: $resp_fraudresult \r\n";
                $message .= "Order Amount: " . $this->so->get_currency_id() . " " . $this->so->get_amount();
                // $message .= "data_from_pmgw: \n{$this->data_from_pmgw}";

                if($this->success == "false")
                {
                    $this->send_email("AUTH_EVENT_FAIL", $message);
                    // $sops_data["pending_action"] = "NA";
                    return Payment_gateway_redirect_service::PAYMENT_STATUS_FAIL;
                }
                else
                {
                    $this->send_email("AUTH_EVENT_SUCCESS", $message);
                    // $sops_data["pending_action"] = "NA";

                    if($this->so->get_status() > 0 )
                    {
                        // going back to parent:query_payment_status_in_general() will not do anything because it was considered a success
                        // thus we go to payment_success_operation again and do fraud check with new info
                        $this->payment_success_operation($so_data, $sops_data, $socc_data, $sor_data);
                        return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
                    }
                    else
                    {
                        // if so was set at so.status = 0, means user cancelled. we don't pass it to success again.
                    }
                }
            }
            else
            {
                if($this->eventCode !== "REFUNDED_REVERSED" && $this->success == "false")
                {
                    $message .= __LINE__."ADYEN status failed for eventCode $this->eventCode .";
                    $this->send_email("EVENT_FAIL", $message);

                    $message .= " \r\ndata_from_pmgw:\n{$this->data_from_pmgw}";
                    mail($this->get_technical_support_email(), $this->get_payment_gateway_name() . " EVENT FAIL", $message, 'From: website@valuebasket.com');
                }
                else
                {
                    // most likely a dispute event notification will end up here, e.g. refund

                    $success_status = $this->success;
                    if($this->eventCode == "REFUNDED_REVERSED" && $this->success == "false")
                        $success_status = "done";

                    $message .= "VB order $so_number - Adyen notification type: {$this->eventCode} || Event status: $success_status || Remarks: {$this->reason}\r\n";
                    $message .= "Order Amount: " . $this->so->get_currency_id() . " " . $this->so->get_amount();
                    // $message .= "data_from_pmgw: \n{$this->data_from_pmgw}";

                    $this->send_email("[VB] Adyen {$this->eventCode}", $message);
                }
            }
        }
    }

    private function _check_signature($checkstring = "", $hmacSignature = "", $merchantAccountCode = "")
    {
        $ret["status"] = false;
        if($checkstring && $hmacSignature && $merchantAccountCode)
        {
            if(strpos($_SERVER["HTTP_HOST"], "dev") === FALSE)
                $hmackey = constant("self::NOTIFICATION_HEX_HMAC_".strtoupper($merchantAccountCode));   # LIVE
            else
                $hmackey = constant("self::DEV_NOTIFICATION_HEX_HMAC_".strtoupper($merchantAccountCode));

            $secretkey = pack("H*", $hmackey);
            $ret["calculatedhmac"] = base64_encode(hash_hmac('sha256', $checkstring, $secretkey, true));
            if($ret["calculatedhmac"] == $hmacSignature)
            {
                $ret["status"] = true;
            }
        }

        $ret["message"] = "\r\ncalculation input- merchantAccountCode::$merchantAccountCode || checkstring::$checkstring || HEXHMAC::$hmackey";
        return $ret;
    }



	public function process_failure_action()
	{
        header("Location:" . $this->_get_failure_page());
	}

	public function process_cancel_action()
	{
//no cancel button
       // header("Location:" . $this->_get_failure_page());
	}

	public function process_success_action()
	{
        $this->fire_success_event();
        header("Location:" . $this->_get_successful_page());
	}

	private function _get_successful_page($so_number = null)
	{
        $debug_string = ($this->debug) ? "?debug=1" : "";
        if($so_number == null)
            $put_so_no = $this->so->get_so_no();
        else
            $put_so_no = $so_number;

        // Adyen doesn't allow specifying return url, so we construct lang_pair
        $base_url = $this->rewrite_base_url();
        $url = "https://" . $base_url . $this->successful_page . $put_so_no . $debug_string;

        return $url;
		// return $this->get_successful_page_top($so_number);
	}

	public function _get_failure_page()
	{
        $debug_string = ($this->debug) ? "?debug=1" : "";
        $put_so_no = "";
        if($so_number == null)
        {
            if($this->so)
                $put_so_no = $this->so->get_so_no();
        }
        else
            $put_so_no = $so_number;
        // Adyen doesn't allow specifying return url, so we construct lang_pair
        $base_url = $this->rewrite_base_url();

        $url = "https://" . $base_url . $this->failure_page . $put_so_no . $debug_string;
        return $url;
		// return $this->get_failure_page_top();
	}

	public function is_payment_need_credit_check($is_fraud = false)
    {
        if ($is_fraud)
            return true;

        $paymentMethod = $this->paymentMethod;

        # auto accept if Mister Cash used
        if ($paymentMethod == "bcmc")
            return false;
        else
        {
            $platform_id = $this->so->get_platform_id();
            if($this->resp_3doffered == "")
            {
                // at this point, we do not have 3D info yet
                // happens when customer pays and Adyen hasn't ping our server
                return parent::is_payment_need_credit_check($is_fraud);
            }
            else
            {
                switch ($platform_id)
                {
                    case 'WEBFR':
                    case 'WEBIE':
                        if($this->resp_3dauth == "true" && $this->success == "true")
                        {
                            if($this->so->get_amount() <= 350)
                                return false;
                            else
                                return true;
                        }
                        else
                        {
                            if($this->so->get_amount() <= 150)
                                return false;
                            else
                                return true;
                        }
                        break;

                    case 'WEBBE':
                        if($this->resp_3dauth == "true" && $this->success == "true")
                        {
                            if($this->so->get_amount() <= 600)
                                return false;
                            else
                                return true;
                        }
                        else
                        {
                            if($this->so->get_amount() <= 200)
                                return false;
                            else
                                return true;
                        }
                        break;

                    case 'WEBGB':
                        if($this->resp_3dauth == "true" && $this->success == "true")
                        {
                            if($this->so->get_amount() <= 300)
                                return false;
                            else
                                return true;
                        }
                        else
                        {
                            if($this->so->get_amount() <= 100)
                                return false;
                            else
                                return true;
                        }
                        break;

                    default:
                        $this->send_email("CREDIT_CHECK", "PMGW Adyen Service cannot process credit check for <{$this->so->get_so_no()} - $platform_id>. Please add case for platform.");
                        break;
                }
            }
        }

        return true;
    }

	public function is_need_dm_service($is_fraud = false)
	{
		// return parent::is_payment_need_credit_check($is_fraud);
        return parent::require_decision_manager($is_fraud);
	}

    private function send_email($action = "", $message="", $input_data=array())
    {
        switch ($action)
        {
            case 'NO_DB_DATA':
                $subject = "[VB] ADYEN ERROR: No data found in database";
                if(is_array($input_data))
                {
                    $message .= "Below are details sent by Adyen: \n";
                    foreach ($input_data as $key => $value)
                    {
                        $message .= "$key = $value\n";
                    }
                }
                break;

            case 'EVENT_FAIL':
                $subject = "[VB] ADYEN ERROR: EventCode's Status Fail";
                break;

            case 'CHECK_ORDER_SIG_FAIL':
                $subject = "[VB] ADYEN ERROR: Check Signature Fail";
                break;

            case 'ERROR_WRITE_DB':
                $subject = "[VB] ADYEN ERROR: Error writing to VB database";
                break;

            case 'ADYEN_DETAILS':
                $subject = "[VB] ADYEN Notification Received";
                break;

            case 'AUTH_EVENT_FAIL':
                $subject = "[VB] ADYEN PAYMENT AUTHORISATION FAIL";
                break;

            case 'AUTH_EVENT_SUCCESS':
                $this->notification_email = "rachel@eservicesgroup.net,alan@eservicesgroup.net";
                $subject = "[VB] ADYEN PAYMENT AUTHORISATION SUCCESS";
                break;

            case 'ADYEN_REPORT':
                $this->notification_email = "rachel@eservicesgroup.net";
                $subject = "[VB] ADYEN Report Available";
                break;

            case 'CREDIT_CHECK':
                $subject = "[VB] Credit Check Process Problem";
                break;

            default:
                $subject = $action;
                break;
        }

        if($this->_debug)
            echo $subject; echo $message;

        mail($this->notification_email, $subject, $message, "From: website@valuebasket.com");
    }


}

