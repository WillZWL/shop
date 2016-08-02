<?php
namespace ESG\Panther\Service;
use ESG\Panther\Service\Moneybookers\MoneybookersRequest;
use ESG\Panther\Service\Moneybookers\MoneybookersAccount;

class PaymentGatewayRedirectMoneybookersService extends PaymentGatewayRedirectService
{
    const DEBUG_PAY_TO_ACCT = "russel@chatandvision.com";
    const STATUS_EMAIL = "mbpayments@chatandvision.com";
    const DEBUG_MERCHANT_ID = "66380912";
    const MD5_SECRET = "FB6D0FD4B1A599DB89E2B099150E9BEC";
    const DEBUG_MD5_SECRET = "91B8159EB716E4A493EFD46272DBAD32";
    const MQI_API_PASSWORD = "edd1b1a7cd87bfd16e67a3399f21d199";
    const DEBUG_MQI_API_PASSWORD = "edd1b1a7cd87bfd16e67a3399f21d199";

    private $_acct = ["EUR" => ["payToEmail" => "eur-pay@chatandvision.com"
                                , "merchantId" => "17906192"]
                        , "GBP" => ["payToEmail" => "gbp-pay@chatandvision.com"
                                , "merchantId" => "17907241"]
                        , "PLN" => ["payToEmail" => "pln-pay@chatandvision.com"
                                , "merchantId" => "17906833"]
                        , "SGD" => ["payToEmail" => "sgd-pay@chatandvision.com"
                                , "merchantId" => "17906956"]
                        , "USD" => ["payToEmail" => "usd-pay@chatandvision.com"
                                , "merchantId" => "17906979"]
                        , "AUD" => ["payToEmail" => "aud-pay@chatandvision.com"
                                , "merchantId" => "17907024"]
                        , "NZD" => ["payToEmail" => "nzd-pay@chatandvision.com"
                                , "merchantId" => "17907047"]
                        , "HKD" => ["payToEmail" => "hkd-pay@chatandvision.com"
                                , "merchantId" => "18101494"]
                        , "CHF" => ["payToEmail" => "chf-pay@chatandvision.com"
                                , "merchantId" => "18103606"]];

    private $_creditCheckAmountByCountry = ["NL" => 350];
    private $_creditCheckAmountByCurrency = ["GBP" => 500
                                            , "AUD" => 500
                                            , "NZD" => 500
                                            , "EUR" => 500
                                            , "PLN" => 2000];
    private $_mbRequest = null;
    public $mbAccount = null;

    public function __construct($soObj = null, $debug = 0)
    {
        parent::__construct($soObj, $debug);
        $this->_mbRequest = new MoneybookersRequest($debug);
        if ($soObj) {
            $this->_setAccount($soObj->getCurrencyId());
        }
    }

    private function _setAccount($currency = null)
    {
        if (!$this->mbAccount) {
            $this->mbAccount = new MoneybookersAccount();
        }
        if ($this->debug) {
            $this->mbAccount->payToEmail = self::DEBUG_PAY_TO_ACCT;
            $this->mbAccount->status2Email = self::DEBUG_PAY_TO_ACCT;
            $this->mbAccount->diffAmountEmail = self::DEBUG_PAY_TO_ACCT;
            $this->mbAccount->merchantId = self::DEBUG_MERCHANT_ID;
            $this->mbAccount->queryPassword = self::DEBUG_MQI_API_PASSWORD;
            $this->mbAccount->swMd5 = self::DEBUG_MD5_SECRET;
        } else {
            $this->mbAccount->swMd5 = self::MD5_SECRET;
            $this->mbAccount->status2Email = self::STATUS_EMAIL;
            $this->mbAccount->queryPassword = self::MQI_API_PASSWORD;
            $this->mbAccount->payToEmail = $this->_acct[$currency]["payToEmail"];
            $this->mbAccount->merchantId = $this->_acct[$currency]["merchantId"];
        }
        $this->_mbRequest->setAccount($this->mbAccount);
    }
/***********************************
**  interface function getPaymentGatewayName
************************************/    
    public function getPaymentGatewayName()
    {
        return "moneybookers";
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
        $order["email"] = $paymentInfo["email"];
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

        $client = $this->getClient();

        $order["client"]["title"] = $client->getTitle();
        $order["client"]["id"] = $client->getId();
        $order["client"]["firstname"] = $client->getForename();
        $order["client"]["lastname"] = $client->getSurname();
        $order["client"]["address1"] = $client->getAddress1();
        $order["client"]["address2"] = $client->getAddress2();
        $order["client"]["phoneNumber"] = trim($client->getTel1() . $client->getTel2() . $client->getTel3());
        $order["client"]["postCode"] = $client->getPostCode();
        if (($client->getCountryId() == "HK") || ($client->getCountryId() == "IE")) {
            if ($client->getPostCode() == "") {
                $order["client"]["postCode"] = "NA";
            }
        }

        $order["client"]["city"] = $client->getCity();
        $order["client"]["state"] = $client->getState();
        $countryObj = $this->getService("SoFactory")->getDao("Country")->get(["country_id" => $client->getCountryId()]);
        $order["client"]["country"] = $countryObj->getId3Digit();

        $setting["notificationUrl"] = $this->getNotificationUrl($order["soNo"]);
        $setting["responseUrl"] = $this->getResponseUrl($order["soNo"]);
        $setting["cancelUrl"] = $this->getCancelUrl($order["soNo"]);
        $setting["returnText"] = _("Proceed to Order Confirmation");
        $setting["confirmationNote"] = _("Please proceed to order confirmation to complete your order");
        $setting["amount2Description"] = _("Delivery Cost");
        $setting["detail1Description"] = _("Order Number:");
        if (defined("LANG_ID"))
            $setting["langId"] = LANG_ID;
        else {
//won't come here for normal website checkout
            $setting["langId"] = "en";
        }
        if (defined("SITE_NAME"))
            $setting["siteName"] = SITE_NAME;
        else {
//won't come here for normal website checkout
            $setting["siteName"] = "DD";
        }
        $setting["logo_url"] = "https://" . SITE_DOMAIN . "/themes/default/asset/image/chatandvision_logo.jpg";

        $postData = $this->_mbRequest->formPaymentRequest($order, $setting);
        $requestData = @http_build_query($postData);
        return $postData;
    }

    private function _commonProcessStatus($data, &$soNo, &$soPara = [], &$sopsPara = [], &$soccPara = [], &$sorData = [], &$dataToPmgw, &$dataFromPmgw) {
        if (isset($data["mb_currency"])) {
            $currency = $data["mb_currency"];
            $this->_setAccount($currency);
            $dataFromPmgw = @http_build_query($data);
            if ($data["md5sig"] == strtoupper(md5($this->mbAccount->merchantId . $data["transaction_id"] . $this->mbAccount->swMd5 . $data["mb_amount"] . $data["mb_currency"] . $data["status"]))) {
                $soNumber = $data["transaction_id"];
                $this->so = $this->getSo($soNumber);
                if ($this->so) {
                    if (isset($data["mb_transaction_id"]))
                        $this->so->setTxnId($data["mb_transaction_id"]);
                    if (($data["mb_amount"] == 0)
                        || ($this->so->getAmount() != $data["mb_amount"])) {
                        $sopsPara[$remark] = "status:invalid amount" . $data["mb_amount"];
                        return PaymentGatewayRedirectService::PAYMENT_INVALID_AMOUNT;
                    }
                    switch ($data["status"]) {
                        case "-2":
                            $remark = "status:failed";
                            if ($data["failed_reason_code"]) {
                                $remark .= "\nfailed_reason_code:" . $data["failed_reason_code"];
                                $remark .= "\nfailed_reason:" . $this->getReason($data["failed_reason_code"]);
                            }
                            $sopsPara["remark"] = $remark;
                            return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
                        case "-1":
                            $sopsPara["remark"] = "status:cancelled";
                            return PaymentGatewayRedirectService::PAYMENT_STATUS_CANCEL;
                        case "0":
                            $sopsPara["remark"] = "status:pending";
                            return PaymentGatewayRedirectService::PAYMENT_STATUS_KEEP_PENDING;
                        case "2":
                            $sopsPara["remark"] = "status:processed";
                            return PaymentGatewayRedirectService::PAYMENT_STATUS_SUCCESS;
                        default:
                            $sopsPara["remark"] = "status:unknow";
                            return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
                    }
                } else {
                    $subject = "[Panther] " . $this->getPaymentGatewayName() . " invalid SoNo return " . __LINE__;
                    $this->sendAlert($subject, $dataFromPmgw, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
                    return PaymentGatewayRedirectService::PAYMENT_WITH_INVALID_RESPONSE;
                }
            } else {
//wrong digest message
                $subject = "[Panther] " . $this->getPaymentGatewayName() . " wrong digest, " . "Line:" .__LINE__;
                $this->sendAlert($subject, $dataFromPmgw, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
                return PaymentGatewayRedirectService::PAYMENT_WITH_INVALID_RESPONSE;
            }
        } else {
            $subject = "[Panther] " . $this->getPaymentGatewayName() . " no currency return, " . "Line:" .__LINE__;
            $this->sendAlert($subject, @http_build_query($data), $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
            return PaymentGatewayRedirectService::PAYMENT_WITH_INVALID_RESPONSE;        
        }
        return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
    }

    public function processNotification($data, &$soNo, &$soPara = [], &$sopsPara = [], &$soccPara = [], &$sorData = [], &$dataToPmgw, &$dataFromPmgw) {
        $this->_commonProcessStatus($data, $soNo, $soPara, $sopsPara, $soccPara, $sorData, $dataToPmgw, $dataFromPmgw);
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

        $getUrlResult = $this->_mbRequest->submitForm($requestData);
        if ($getUrlResult["result"])
        {
            $session = $getUrlResult["response"];
            if (($result["errorMessage"] != "") || (strlen($session) > 100) || ($session == "")) {
                $errorMessage = $result["errorMessage"] . ", info:" . @http_build_query($result["info"]);
                $siteDownErrorMessage = $errorMessage;
                $responseData = $errorMessage;
                $siteDown = true;
            } else {
                $redirectUrl = MoneybookersRequest::MONEYBOOKERS_SERVER_PAYMENT . "?sid=" . $session;
                $responseData = $redirectUrl;
                $callResult = true;
            }
        } else {
//need to pass the error
            $siteDownErrorMessage = "error:" . $response["erroNo"] . ", info:" . $response["errorMessage"];
            $siteDown = true;
        }
        return ["result" => $callResult
                ,"errorMessageToClient" => $errorMessage
                , "siteDown" => $siteDown
                , "siteDownErrorMessage" => $siteDownErrorMessage
                , "url" => $redirectUrl];
    }
/**********************************************
**  interface function processPaymentStatus
************************************************/
    public function processPaymentStatus($generalData = [], $getData = [], &$soNumber, &$dataFromPmgw, &$dataToPmgw, &$soData, &$sopsData, &$soccData, &$sorData)
    {
        if (isset($getData["soNo"])/* && isset($getData["transaction_id"])*/) {
            $soNumber = $getData["soNo"];
            $transactionId = $getData["soNo"];
            $input = ["soNo" => $soNumber, "transactionId" => $transactionId];
            $queryData = $this->_internalQueryTransaction($input, $queryFromData, $queryToData, $soData, $soccData, $sopsData);
            if ($queryToData)
                $this->getService("SoPaymentQueryLog")->addLog($soNumber, "O", $queryToData);
            if ($queryFromData) {
                $data = @http_build_query($queryFromData);
                if ($data == 0)
                    $data = $queryFromData;
                $this->getService("SoPaymentQueryLog")->addLog($soNumber, "I", $data);
            }
            if ($queryData !== false) {
                $dataFromPmgw = @http_build_query($getData);
                return $this->_commonProcessStatus($queryData, $soNumber, $soData, $sopsData, $soccData, $sorData, $dataToPmgw, $noUseData);
            }
        }
        return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
   }

    private function _queryOrderApi($transactionId)
    {
        $error = false;
        $queryResult = $this->_mbRequest->sumbitQuery($transactionId);
        if ($queryResult["result"]) {
            $rs = array();
            $arResult = @explode("&", $queryResult["response"]);
            foreach ($arResult as $data) {
                $arData = @explode("=", $data);
                $rs[trim($arData[0])] = trim($arData[1]);
            }
            if (isset($rs["mb_currency"]))
                return ["result" => $rs, "dataToPmgw" => $queryResult["url"], "errorMessage" => ""];
        }

        return ["result" => $queryResult["response"]
                , "dataToPmgw" => $queryResult["url"]
                , "errorMessage" => $queryResult["response"] . ", errorMessage:" . $queryResult["errorMessage"] . ", callInfo:" . $queryResult["callInfo"]];
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

    public function processReviewAction() {
    }

    public function isPaymentNeedCreditCheck($isFraud = false) {
        $this->sops = $this->getSoPaymentStatus();
        if ($this->sops->getCardId() == "IDL")
            return false;
        else if (array_key_exists($this->so->getDeliveryCountryId(), $this->_creditCheckAmountByCountry)) {
            if ($this->so->getAmount() < $this->_creditCheckAmountByCountry[$this->so->getDeliveryCountryId()])
                return FALSE;
        } elseif (array_key_exists($this->so->getCurrencyId(), $this->_creditCheckAmountByCurrency)) {
            if ($this->so->getAmount() < $this->_creditCheckAmountByCurrency[$this->so->getCurrencyId()])
                return FALSE;
        }
        return true;
    }

    public function isNeedDmService($isFraud = false) {
        return $this->isPaymentNeedCreditCheck($isFraud);
    }

    public function queryTransaction($inputParameters = [], &$dataFromPmgw, &$dataToPmgw, &$soData, &$soccData, &$sopsData) {
        return $this->processPaymentStatus([], ["transaction_id" => $inputParameters["transaction_id"], "soNo" => $inputParameters["so_no"]], $soNo, $noUse, $noUse, $soData, $sopsData, $soccData, $noUse);
    }

    private function _internalQueryTransaction($inputParameters = [], &$dataFromPmgw, &$dataToPmgw, &$soData, &$soccData, &$sopsData) {
        if ($this->so = $this->getService("SoFactory")->getDao("So")->get(["so_no" => $inputParameters["soNo"]])) {
            $this->_setAccount($this->so->getCurrencyId());
            $result = $this->_queryOrderApi($inputParameters["transactionId"]);
            $dataToPmgw = $result["dataToPmgw"];
            if ($result["errorMessage"] != "") {
                $dataFromPmgw = $result["errorMessage"];
            } elseif (($result["result"] != "")
                && ($result["errorMessage"] == "")) {
                $dataFromPmgw = $result["result"];
                return $result["result"];
            }
        }
        return false;
    }

    public function getPendingScheduleId() {
        return "MONEYBOOKERS_ORDERS_VERIFICATION";
    }

    public function updatePendingList() {
        $scheduleId = $this->getPendingScheduleId();
        $sjobObj = $this->getService("SoFactory")->getDao("ScheduleJob")->get(["schedule_job_id" => $scheduleId, "status" => "1"]);
        if ($sjobObj) {
            $lastAccess = $sjobObj->getLastAccessTime();
//shift 30mins
            $timeShift = 60 * 30;
//            $additionalShift = 30;
//we need the additionalShift=90mins because we need to query last 2 hours pending orders
            $startTime = strtotime($lastAccess) - $timeShift;
            $endTime = date('Y-m-d H:i:s');
            $shiftedEndTime = date("Y-m-d H:i:s", (strtotime($endTime) - $timeShift));
            $sopsList = $this->getService("SoFactory")->getDao("SoPaymentStatus")->getList(["payment_gateway_id" => $this->getPaymentGatewayName()
                , "payment_status" => "P"
                , "payment_status <> 'NA'" => null
                , "create_on >" => date("Y-m-d H:i:s", $startTime)
                , "create_on <=" => $shiftedEndTime]
                , ["limit" => -1]);
//print $this->getService("SoFactory")->getDao("SoPaymentStatus")->db->last_query();
            foreach ($sopsList as $sops) {
                $this->queryPaymentStatusInGeneral($sops->getSoNo());
            }
            $sjobObj->setLastAccessTime($shiftedEndTime);
            $this->getService("SoFactory")->getDao("ScheduleJob")->update($sjobObj);
        }
    }

    public function getReason($code)
    {
        $reason = [
            "01" => "Referred",
            "02" => "Invalid Merchant Number",
            "03" => "Pick-up card",
            "04" => "Authorisation Declined",
            "05" => "Other Error",
            "06" => "CVV is mandatory, but not set or invalid",
            "07" => "Approved authorisation, honour with identification",
            "08" => "Delayed Processing",
            "09" => "Invalid Transaction",
            "10" => "Invalid Currency",
            "11" => "Invalid Amount/Available Limit Exceeded/Amount too high",
            "12" => "Invalid credit card or bank account",
            "13" => "Invalid Card Issuer",
            "14" => "Annulation by client",
            "15" => "Duplicate transaction",
            "16" => "Acquirer Error",
            "17" => "Reversal not processed, matching authorisation not found",
            "18" => "File Transfer not available/unsuccessful",
            "19" => "Reference number error",
            "20" => "Access Denied",
            "21" => "File Transfer failed",
            "22" => "Format Error",
            "23" => "Unknown Acquirer",
            "24" => "Card expired",
            "25" => "Fraud Suspicion",
            "26" => "Security code expired",
            "27" => "Requested function not available",
            "28" => "Lost/Stolen card",
            "29" => "Stolen card, Pick up",
            "30" => "Duplicate Authorisation",
            "31" => "Limit Exceeded",
            "32" => "Invalid Security Code",
            "33" => "Unknown or Invalid Card/Bank account",
            "34" => "Illegal Transaction",
            "35" => "Transaction Not Permitted",
            "36" => "Card blocked in local blacklist",
            "37" => "Restricted card/bank account",
            "38" => "Security Rules Violation",
            "39" => "The transaction amount of the referencing transaction is higher than the transaction amount of the original transaction",
            "40" => "Transaction frequency limit exceeded, override is possible",
            "41" => "Incorrect usage count in the Authorisation System exceeded",
            "42" => "Card blocked",
            "43" => "Rejected by Credit Card Issuer",
            "44" => "Card Issuing Bank or Network is not available",
            "45" => "The card type is not processed by the authorisation centre / Authorisation System has determined incorrect Routing",
            "47" => "Processing temporarily not possible",
            "48" => "Security Breach",
            "49" => "Date / time not plausible, trace-no. not increasing",
            "50" => "Error in PAC encryption detected",
            "51" => "System Error",
            "52" => "MB Denied - potential fraud",
            "53" => "Mobile verification failed",
            "54" => "Failed due to internal security restrictions",
            "55" => "Communication or verification problem",
            "56" => "3D verification failed",
            "57" => "AVS check failed",
            "58" => "Invalid bank code",
            "59" => "Invalid account code",
            "60" => "Card not authorised",
            "61" => "No credit worthiness",
            "62" => "Communication error",
            "63" => "Transaction not allowed for cardholder",
            "64" => "Invalid Data in Request",
            "65" => "Blocked bank code",
            "66" => "CVV2/CVC2 Failure",
            "99" => "General error",
        ];
        return $reason[$code];
    }

    public function useIframe() {
        return false;
    }
}


