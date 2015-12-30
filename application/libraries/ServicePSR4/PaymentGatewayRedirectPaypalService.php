<?php
namespace ESG\Panther\Service;
use ESG\Panther\Service\Paypal\PaypalRequest;

class PaymentGatewayRedirectPaypalService extends PaymentGatewayRedirectService
{
//paid by UK test account with credit card, not bank transfer can bring user to retry
    const MAXIMUM_NUMBER_OF_RETRIES = 3;
    private $_activeAcct = "";
    private $_ppAcct = ["HK" => [
                                    "userName" => "paypal_api1.chatandvision.com",
                                    "password" => "G75Z557HZLN3YCZG",
                                    "signature" => "AfVaGWFlnJfCnZKqutSM-9.NQVccAN06nZTH8yJna9A2K6B-9iJC-.F3",
                                    "paypalEmailAddress" => "paypal@chatandvision.com"
                                ]
                             , "apiUrl" => "https://api-3t.paypal.com/nvp"
                             , "paypalUrl" => "https://www.paypal.com/cgi-bin/webscr?"
                             , "paypalHost" => "www.paypal.com"
                        ];
    private $_ppAcctDebug = ["AU" => [
                                "userName" => "oswald_api1.eservicesgroup.com",
                                "password" => "1380249734",
                                "signature" => "AVESqx77e6GffwXNNVtYA3e5OMRXAYHVsr--ya8m-hYLSj9fTUgxlbc7",
                                "paypalEmailAddress" => "oswald@eservicesgroup.com"
                                ]
                            , "FR" => [
                                "userName" => "oswald_1344414203_biz_api1.eservicesgroup.net",
                                "password" => "1344414229",
                                "signature" => "AMfVHpWfaR2TtXa0gTOjNp1cbyZ.AwRwMFUmcoXTl-ZoS5p-.CHVreXr",
                                "paypalEmailAddress" => "oswald_1344414203_biz@eservicesgroup.net"
                                ]
                            , "GB" => [
                                "userName" => "oswald_1344402161_biz_api1.eservicesgroup.net",
                                "password" => "1344402186",
                                "signature" => "AJalutgZ0QlqdfRK3MRN5tqq73OgAq5SkWV2-vpKNp7ESSN2-3NCVw9C",
                                "paypalEmailAddress" => "oswald_1344402161_biz@eservicesgroup.net"
                                ]
                            , "US" => [
                                "userName" => "oswald-facilitator_api1.eservicesgroup.com",
                                "password" => "1380248968",
                                "signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31AtRa4wXM1GDM6YUniq2PMOx.o21B",
                                "paypalEmailAddress" => "oswald-facilitator@eservicesgroup.com"
                                ]
                             , "apiUrl" => "https://api-3t.sandbox.paypal.com/nvp"
                             , "paypalUrl" => "https://www.sandbox.paypal.com/cgi-bin/webscr?"
                             , "paypalHost" => "www.sandbox.paypal.com"
                            ];
    private $_paypalRequest;
/*
    private $payment_methods;
    private $api_url = "https://api-3t.paypal.com/nvp";
    private $paypal_url = "https://www.paypal.com/cgi-bin/webscr?";
    private $post_array = [];
    private $promo;
    private $so_item_list;

    private $error_type = 1;
    private $payment_status;
    private $remark;
    private $display_message;
*/
    public $creditCheckAmountByCurrency = array("GBP" => 150
                                            , "AUD" => 150
                                            , "NZD" => 150
                                            , "EUR" => 150
                                            , "PNL" => 1000);

    public function __construct($soObj, $debug = 0)
    {
        parent::__construct($soObj, $debug);
        $this->_paypalRequest = new PaypalRequest($debug);
        if ($soObj)
        {
            $this->_setAccount($soObj->getBillCountryId(), $soObj->getCurrencyId());
        }
    }

    private function _setAccount($countryId, $currency = null)
    {
        if ($this->debug)
        {
            if (!array_key_exists($countryId, $this->_ppAcctDebug))
                $countryId = "HK";
            $this->_paypalRequest->setAccount($this->_ppAcctDebug[$countryId], $this->_ppAcctDebug["apiUrl"], $this->_ppAcctDebug["paypalUrl"], $this->_ppAcctDebug["paypalHost"]);
            $this->_activeAcct = $this->_ppAcctDebug[$countryId];
        }
        else
        {
//            if ($currency == "")
                $countryId = "HK";
            $this->_paypalRequest->setAccount($this->_ppAcct[$countryId], $this->_ppAcct["apiUrl"], $this->_ppAcct["paypalUrl"], $this->_ppAcct["paypalHost"]);
            $this->_activeAcct = $this->_ppAcct[$countryId];
        }
    }
/***********************************
**  interface function getPaymentGatewayName
************************************/    
    public function getPaymentGatewayName()
    {
        return "paypal";
    }
/***********************************
**  interface function prepareGetUrlRequest
************************************/
    public function prepareGetUrlRequest($paymentInfo = [], &$requestData)
    {
        $order = [];
        $setting = [];
        $orderPayment = [];
        $orderObj = $this->so;

        $order["soNo"] = $orderObj->getSoNo();
        $order["amount"] = $orderObj->getAmount();
        $order["vat"] = 0;
        $order["currency"] = $orderObj->getCurrencyId();
        $order["deliveryCharge"] = $orderObj->getDeliveryCharge();
        $order["paymentMethods"] = $paymentInfo["paymentCardId"];
        $order["countryId"] = $orderObj->getBillCountryId();
        $order["deliveryName"] = $orderObj->getDeliveryName();
        $address = explode("||", $orderObj->getDeliveryAddress());
        $order["deliveryAddress1"] = $address[0];
        if (count($address) > 1)
            $order["deliveryAddress2"] = $address[1];
        else
            $order["deliveryAddress2"] = "";

        $order["deliveryCity"] = $orderObj->getDeliveryCity();
        $order["deliveryState"] = $orderObj->getDeliveryState();
        $order["deliveryPostal"] = $orderObj->getDeliveryPostcode();
        $order["deliveryCountry"] = $orderObj->getDeliveryCountryId();

        $this->soids = $this->getSoItemDetail($orderObj->getSoNo());
        $order["item"] = [];
        foreach($this->soids as $item)
        {
            $orderItem = [];
            $orderItem["lineNo"] = $item->getLineNo();
            $orderItem["sku"] = $item->getItemSku();
            $orderItem["name"] = $item->getProdName();
            $orderItem["qty"] = $item->getQty();
            $orderItem["unitPrice"] = $item->getUnitPrice();
            array_push($order["item"], $orderItem);
        }

        $setting["notificationUrl"] = $this->getNotificationUrl($order["soNo"]);
        $setting["responseUrl"] = $this->getResponseUrl($order["soNo"]);
        $setting["cancelUrl"] = $this->getCancelUrl($order["soNo"]);
        $setting["siteLogo"] = $this->getSiteLogo();
        if (defined("SITE_NAME"))
            $setting["siteName"] = SITE_NAME;
        $postData = $this->_paypalRequest->formPaymentRequest($order, $setting);
        $requestData = @http_build_query($postData);
        return $postData;
    }

    public function processNotification($data, &$soNo, &$soPara = [], &$sopsPara = [], &$soccPara = [], &$sorData = [], &$dataToPmgw, &$dataFromPmgw)
    {
        $orderObj = "";
        $orderPaymentObj = "";
        $result = FALSE;

        $encodedData = "cmd=_notify-validate&" . $data;
//error_log(__METHOD__ . __LINE__ . $encodedData);
        $dataFromPmgw = $data;
        $this->_setAccount("", "");

        $verifedResult = $this->_paypalRequest->verifyNotification($encodedData);
//error_log(__METHOD__ . __LINE__ . $verifedResult["response"]);
        if (strpos($verifedResult["response"], "VERIFIED") !== false)
        {
            $soNo = $_POST["invoice"];
            $this->so = $this->getSo($soNo);
            $this->sops = $this->getSoPaymentStatus($soNo);

            if ($this->so && $this->sops)
            {
                $this->_setAccount($this->so->getBillCountryId(), $this->so->getCurrencyId());
                $paymentStatus = $_POST["payment_status"];
                if ($paymentStatus == "Completed")
                {
                    $this->so->setTxnId($_POST["txn_id"]);
                    if (isset($_POST["payer_email"]))
                        $this->sops->setPayerEmail($_POST["receiver_email"]);
                    if (isset($_POST["payer_id"]))
                        $this->sops->setPayerRef($_POST["payer_id"]);
                    if (isset($_POST["address_status"]))
                        $this->sops->setRiskRef3($_POST["address_status"]);
                    if (isset($_POST["payer_status"]))
                        $this->sops->setRiskRef4($_POST["payer_status"]);
                    if (isset($_POST["protection_eligibility"]))
                        $this->sops->setRiskRef1($_POST["protection_eligibility"]);
                    $this->sops->setPayToAccount($this->_activeAcct["paypalEmailAddress"]);
                    $result = PaymentGatewayRedirectService::PAYMENT_STATUS_SUCCESS;
                }
                else if ($paymentStatus == "Reversed")
                {
                    $result = PaymentGatewayRedirectService::PAYMENT_STATUS_REVERSE;
                }
                else if ($paymentStatus == "Refunded")
                {
    /*
                    $orderService = $this->getServiceLocator()->get("boostbase_order");
                    $message = "Paypal Reversed";
                    if ($orderObj->getStatus() >= 20)
                    {
                        $orderService->holdOrder($orderObj, $message, 4);
                    }
    */
                    $result = PaymentGatewayRedirectService::PaymentGatewayRedirectService;
                }
            }
            else
            {
                if ((!$this->sops)
                    || (!$this->so))
                {
                    $subject = "[Panther] Paypal notification no so or sops - " . $paymentStatus . ", soNo:" . $soNo . ", " . __METHOD__ . __LINE__;
                    $this->sendAlert($subject, str_replace("&", "\n&", $data), $this->getTechnicalSupportEmail());                
                }
            }
//error_log(__METHOD__ . __LINE__);
            $subject = "[Panther] Paypal notification - " . $paymentStatus . ", soNo:" . $soNo;
            $this->sendAlert($subject, str_replace("&", "\n&", $data), $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
        }
        else
        {
            $subject = "[Panther] Paypal Verify notification fail" . ", " . __METHOD__ . __LINE__;
            $this->sendAlert($subject, $verifedResult["response"] . $data, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
        }
//error_log(__METHOD__ . __LINE__ . $result);
        return $result;
    }

/**********************************************
**  interface function getRedirectUrl
************************************************/
    public function getRedirectUrl($requestData, &$responseData)
    {
        $url = "";
        $errorMessage = "";
        $callResult = false;
        $siteDown = false;

        $getUrlResult = $this->_paypalRequest->executeSetExpressChecout($requestData);
        if ($getUrlResult["result"])
        {
            $responseData = $getUrlResult["response"];
            $paypalResult = $getUrlResult["parseResult"];
            $this->sops = $this->getSoPaymentStatus();
            $this->sops->setRetry(1);
            if (isset($paypalResult["TOKEN"]))
                $this->sops->setMacToken($paypalResult["TOKEN"]);

            if ($paypalResult["ACK"] == "Success")
            {
                $url = $this->_getPaypalUrl() . "cmd=_express-checkout" . "&token=" . $paypalResult["TOKEN"] . "&useraction=commit";
                $callResult = true;
            }
            else
            {
                $errorMessage = $paypalResult["L_LONGMESSAGE0"];
            }
        }
        else
        {
//need to pass the error
            $siteDownErrorMessage = "error:" . $response["erroNo"] . ", errorMessage:" . $response["errorMessage"] . ", info:" . @http_build_query($response["callInfo"]);
            $siteDown = true;
        }
        return ["result" => $callResult
                ,"errorMessageToClient" => $errorMessage
                , "siteDown" => $siteDown
                , "siteDownErrorMessage" => $siteDownErrorMessage
                , "url" => $url];
    }

/**********************************************
**  interface function processPaymentStatus
************************************************/
    public function processPaymentStatus($generalData = [], $getData = [], &$soNumber, &$dataFromPmgw, &$dataToPmgw, &$soData, &$sopsData, &$soccData, &$sorData)
    {
        $soNo = $getData["soNo"];
        $this->so = $this->getSo($soNo);
        if ($this->so)
        {
//we do add log by ourselves because it may be redirected if we use Paypal retry
            $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "I", $this->arrayImplode("=", ",", $getData));
            $this->_setAccount($this->so->getBillCountryId(), $this->so->getCurrencyId());
            $token = $getData["token"];
            $this->sops = $this->getSoPaymentStatus();

            if ($token == $this->sops->getMacToken())
            {
                $data = array("token" => $token);
                $expressDetailResult = $this->_paypalRequest->getExpressCheckoutDetail($postData, $data);
                $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "O", http_build_query($postData));
                $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "I", urldecode($expressDetailResult["response"]));

                if ($expressDetailResult["result"])
                {
                    parse_str($expressDetailResult["response"], $paypalResult);
                    if ($paypalResult["ACK"] == "Success")
                    {
                        $sopsData["risk_ref_3"] = (isset($paypalResult["ADDRESSSTATUS"])?$paypalResult["ADDRESSSTATUS"]:$paypalResult["PAYMENTREQUEST_0_ADDRESSSTATUS"]);
                        $sopsData["risk_ref_4"] = $paypalResult["PAYERSTATUS"];
                        $sopsData["payer_email"] = $paypalResult["EMAIL"];
                        $sopsData["payer_ref"] = $paypalResult["PAYERID"];
                        $sopsData["pay_to_account"] = $this->_activeAcct["paypalEmailAddress"];
                        return $this->_doExpressCheckout($this->so, $sopsData, $dataFromPmgw, $dataToPmgw);
                    }
                    return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;

                }
            }
            else
            {
                $subject = "[Panther] Not a valid Paypal token return";
                $this->sendAlert($subject, $dataFromPmgw, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
                return PaymentGatewayRedirectService::PAYMENT_WITH_INVALID_RESPONSE;
            }
        }
        else
        {
            $subject = "[Panther] Paypal invalid SoNo return";
            $this->sendAlert($subject, $dataFromPmgw, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
            return PaymentGatewayRedirectService::PAYMENT_WITH_INVALID_RESPONSE;
        }
        return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
    }

    private function _doExpressCheckout($soObj, &$sopsData, &$dataFromPmgw, &$dataToPmgw)
    {      
        $data = array("payerId" => $sopsData["payer_ref"]
                                , "amount" => $soObj->getAmount()
                                , "currency" => $soObj->getCurrencyId()
                                , "token" => $this->sops->getMacToken());
        $doExpressResult = $this->_paypalRequest->doExpressCheckout($doExpressData, $data);
        $this->getService("SoPaymentLog")->addLog($soObj->getSoNo(), "O", $this->arrayImplode("=", ",", $doExpressData));
        $this->getService("SoPaymentLog")->addLog($soObj->getSoNo(), "I", urldecode($doExpressResult["response"]));

        if ($doExpressResult["result"])
        {
            parse_str($doExpressResult["response"], $doExpressPaypalResult);
            $sopsData["risk_ref_1"] = $doExpressPaypalResult["PAYMENTINFO_0_PROTECTIONELIGIBILITY"];
            $sopsData["risk_ref_2"] = $doExpressPaypalResult["PAYMENTINFO_0_PROTECTIONELIGIBILITYTYPE"];
            $soObj->setTxnId($doExpressPaypalResult["PAYMENTINFO_0_TRANSACTIONID"]);
            $paymentStatus = $doExpressPaypalResult["PAYMENTINFO_0_PAYMENTSTATUS"];

            if ($paymentStatus == "Completed")
            {
                return PaymentGatewayRedirectService::PAYMENT_STATUS_SUCCESS;
            }
            elseif ($paymentStatus == "Pending")
            {
                $paymentPendingReason = (isset($doExpressPaypalResult["PAYMENTINFO_0_PENDINGREASON"])) ? strtolower($doExpressPaypalResult["PAYMENTINFO_0_PENDINGREASON"]) : "";
                $subject = "[Panther] Paypal pending status order so_no:" . $soObj->getSoNo();
                $this->emailAlert($subject, $dataFromPmgw);
                if ($paymentPendingReason == "paymentreview")
                {
                    return PaymentGatewayRedirectService::PAYMENT_STATUS_REVIEW;
                }
            }
            else
            {
//update before redirect
                if ($this->sops->getRetry() < self::MAXIMUM_NUMBER_OF_RETRIES)
                {
                    $this->getService("SoFactory")->getDao("So")->update($soObj);
//will be redirect back to Paypal, before redirect, update the object first
                    set_value($this->sops, $sopsData);
                    $this->paymentRetry($this->sops);
                }
            }
        }
        return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
    }

	public function paymentRetry($sops)
	{
		$sops->setRetry($sops->getRetry() + 1);
		$token = $sops->getMacToken();
        $this->getService("SoFactory")->getDao("SoPaymentStatus")->update($sops);

        $url = $this->_getPaypalUrl() . "cmd=_express-checkout" . "&token=" . $token . "&useraction=commit";
        redirect($url);
	}

    public function getTechnicalSupportEmail() {
        return "oswald-alert@eservicesgroup.com";
    }

    public function processFailureAction() {
        header("Location:" . $this->getFailUrl());
        exit;
    }

    public function processCancelAction() {
        header("Location:" . $this->getCancelUrl());
        exit;
    }
/*
    public function processSuccessAction() {
        header("Location:" . $this->getSuccessfulUrl($this->so->getSoNo()));
        exit;
    }
*/
    public function processReviewAction() {
        header("Location:" . $this->getReviewUrl());
    }

    public function isPaymentNeedCreditCheck($isFraud = false) {
        if ($this->sops->getRiskRef1() == "Eligible") {
            return FALSE;
        } else {
            if (in_array($this->so->getCurrencyId(), $this->creditCheckAmountByCurrency)) {
                if ($this->so->getAmount() < $this->creditCheckAmountByCurrency[$this->so->getCurrencyId()])
                    return FALSE;
            } else
                return true;
        }
        return true;
    }

    public function isNeedDmService($isFraud = false) {
        return $this->isPaymentNeedCreditCheck($isFraud);
    }

    public function queryTransaction($inputParameters = [], &$dataFromPmgw, &$dataToPmgw, &$soData, &$soccData, &$sopsData) {
    }

    private function _getPaypalUrl() {
        if ($this->debug) {
            return $this->_ppAcctDebug["paypalUrl"];
        } else {
            return $this->_ppAcct["paypalUrl"];
        }    
    }
}


