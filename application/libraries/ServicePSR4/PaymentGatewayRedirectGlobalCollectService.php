<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\GlobalCollect\GlobalCollectRequest;

class PaymentGatewayRedirectGlobalCollectService extends PaymentGatewayRedirectService
{
    const PAYMENT_PENDING = 600;
    const PAYMENT_CAPTURE = 800;
    const PAYMENT_CANCEL = 99999;

    private $_gcRequest;

    public function __construct($soObj = null, $debug = 0)
    {
        parent::__construct($soObj, $debug);
        $this->_gcRequest = new GlobalCollectRequest($debug);
    }

    private $_creditCheckAmountByCurrency = [
        "EUR" => 1500
        , "GBP" => 1500
    ];

    public function getPaymentGatewayName()
    {
        return "global_collect";
    }

    public function getTechnicalSupportEmail()
    {
        return "oswald-alert@eservicesgroup.com,brave.liu@eservicesgroup.com";
    }

    public function prepareGetUrlRequest($paymentInfo = [], &$requestData)
    {
        $orderObj = $this->so;
        $this->client = $this->getClient();
        $soiList = $this->getDao('SoItemDetail')->getList(["so_no" => $orderObj->getSoNo()], ["limit" => -1]);
        $this->_gcRequest->setMerchantId($this->so->getBillCountryId());
        $requestData = $this->_gcRequest->formPaymentXml(
                                                $this->so,
                                                $soiList,
                                                $this->client,
                                                $paymentInfo['paymentCardId'],
                                                $this->getResponseUrl($orderObj->getSoNo())
                                            );

        return $requestData;
    }

    public function xmlResponse($vars)
    {
        $xml = simplexml_load_string($vars);
        switch ((string)$xml->REQUEST->ACTION)
        {
            case "INSERT_ORDERWITHPAYMENT":
                return $this->_extractRedirectUrl($xml);
                break;
            case "GET_ORDERSTATUS":
                return $xml;
                break;
        }
    }

    private function _extractRedirectUrl($xml)
    {
        return [
            "response_result" => (string) $xml->REQUEST->RESPONSE->RESULT
            , "txn_id" => (string) $xml->REQUEST->RESPONSE->ROW->REF
            , "mac_token" => (string) $xml->REQUEST->RESPONSE->ROW->RETURNMAC
            , "redirectUrl" => (string) $xml->REQUEST->RESPONSE->ROW->FORMACTION
        ];
    }

    public function processNotification($data, &$soNo, &$soPara = [], &$sopsPara = [], &$soccPara = [], &$sorData = [], &$dataToPmgw, &$dataFromPmgw) {
        $this->_commonProcessStatus($data, $soNo, $soPara, $sopsPara, $soccPara, $sorData, $dataToPmgw, $dataFromPmgw);
    }

    public function getRedirectUrl($requestData, &$responseData)
    {
        $redirectUrl = "";
        $errorMessage = "";
        $callResult = false;
        $siteDown = false;

        $trycount = 0;
        do {
            $output = $this->_gcRequest->submitRequest($requestData);
            $trycount++;
        } while (($trycount < 2) && (!empty($output["error"])));

        $this->sops = $this->getSoPaymentStatus();
        $extractedData = $this->xmlResponse($output["result"]);
        $this->sops->setMacToken($extractedData["mac_token"]);
        $this->so->setTxnId($extractedData["txn_id"] );
        $this->getDao('So')->update($this->so);

        if (($output != "") && ($output["error"] == "") && isset($extractedData["response_result"]) && $extractedData["response_result"] == "OK") {
            $responseData = $output["result"];
            $callResult = true;
        } elseif (isset($extractedData["response_result"]) && $extractedData["response_result"] != "OK") {
            $errorMessage = "response result:" . $output["result"] . $output["error"] . " "
                            . $this->arrayImplode('=', ',', $output["info"]);
            $siteDownErrorMessage = $errorMessage;
            $responseData = $errorMessage;
            $siteDown = true;
        } else {
            $responseData = $output["error"] . " " . $this->arrayImplode('=', ',', $output["info"]);
            $siteDownErrorMessage = "Session: " . $session . "Please contact " . $this->getPaymentGatewayName()
                                    . ", IT please consider to switch payment gateway."
                                    . "O:". $requestData . ", I:" . $responseData;
            $siteDown = true;
        }

        $redirectUrl = $extractedData["redirectUrl"];
        return ["result" => $callResult
                ,"errorMessageToClient" => $errorMessage
                , "siteDown" => $siteDown
                , "siteDownErrorMessage" => $siteDownErrorMessage
                , "url" => $redirectUrl
        ];
    }

    public function getPendingScheduleId()
    {
        return "GLOBAL_COLLECT_ORDERS_VERIFICATION";
    }

    public function updatePendingList()
    {
        $scheduleId = $this->getPendingScheduleId();
        $sjobObj = $this->getDao("ScheduleJob")->get(["schedule_job_id" => $scheduleId, "status" => "1"]);
        if ($sjobObj)
        {
            $last_access = $sjobObj->getLastAccessTime();
//shift 30mins
            $timeShift = 60 * 30;
            $additionalShift = 60 * 90;
//we need the additionalShift=90mins because we need to query last 2 hours pending orders
            $startTime = strtotime($last_access) - $timeShift - $additionalShift;
            $endTime = date('Y-m-d H:i:s');
            $shiftedEndTime = date("Y-m-d H:i:s", (strtotime($endTime) - $timeShift));

//$shiftedEndTime = date("Y-m-d H:i:s");
            $sopsList = $this->getDao("SoPaymentStatus")->getList([
                                        "payment_gateway_id" => $this->getPaymentGatewayName()
                                        , "payment_status" => "P"
                                        , "payment_status <> 'NA'" => null
                                        , "create_on >" => date("Y-m-d H:i:s", $startTime)
                                        , "create_on <=" => $shiftedEndTime
                                    ],
                                    [
                                        "limit" => -1
                                    ]);

            foreach($sopsList as $sops)
            {
//                var_dump($sops->getSoNo());
                $this->queryPaymentStatusInGeneral($sops->getSoNo());
            }
            $sjobObj->setLastAccessTime($endTime);
            $this->getDao("ScheduleJob")->update($sjobObj);
        }
    }

    private function _commonProcessStatus($soNumber, &$sops, &$socc)
    {
        $this->so = $this->getSo($soNumber);
        $this->_gcRequest->setMerchantId($this->so->getBillCountryId());
        $requestXml = $this->_gcRequest->formOrderStatusXml($soNumber);
        $this->getService("SoPaymentQueryLog")->addLog($soNumber, "O", $requestXml);
        $orderReuslt = $this->_gcRequest->submitRequest($requestXml);
        $this->getService("SoPaymentQueryLog")->addLog($soNumber, "I", $orderReuslt["result"]);

        if ($orderReuslt["error"] != "") {
            $subject = $this->getPaymentGatewayName() . " line:" . __LINE__ . " cannot get order status so_no:" . $soNumber;

            $this->sendAlert($subject, $requestXml, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
        } else {
            $xml = $this->xmlResponse($orderReuslt["result"]);

            $resp_result = (string)$xml->REQUEST->RESPONSE->RESULT;
            $resp_avsresult = (string)$xml->REQUEST->RESPONSE->STATUS->AVSRESULT;
            $resp_fraudresult = (string)$xml->REQUEST->RESPONSE->STATUS->FRAUDRESULT;
            $resp_statusid = (int)$xml->REQUEST->RESPONSE->STATUS->STATUSID;
            $resp_ccno = (string)$xml->REQUEST->RESPONSE->STATUS->CREDITCARDNUMBER;
            $resp_eci = (string)$xml->REQUEST->RESPONSE->STATUS->ECI;
            $resp_cavv = (string)$xml->REQUEST->RESPONSE->STATUS->CAVV;

            if ($resp_result == "OK")
            {
                if ($resp_avsresult != "")
                    $sops["risk_ref_1"] = $resp_avsresult;
                if ($resp_fraudresult != "")
                    $sops["risk_ref_2"] = $resp_fraudresult;
                if ($resp_eci != "")
                    $sops["risk_ref_4"] = $resp_eci;
                if ($resp_cavv != "")
                    $sops["risk_ref_3"] = $resp_cavv;
                if ($resp_ccno != "")
                    $socc["card_last4"] = ltrim($resp_ccno, "*");

                if (($resp_statusid >= self::PAYMENT_CAPTURE) && ($resp_statusid != self::PAYMENT_CANCEL)) {
                    $sops["pending_action"] = "NA";
                    $sops["remark"] = "status:processed";

                    return PaymentGatewayRedirectService::PAYMENT_STATUS_SUCCESS;
                } elseif (!($resp_statusid == 50 || $resp_statusid == 650 || $resp_statusid == 20 || $resp_statusid == 25)) {
                    $sops["pending_action"] = "NA";
                    $sops["remark"] = $remark;

                    return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
                }
            } else {
                $subject = $this->getPaymentGatewayName() . " line:" . __LINE__ . " cannot get order status so_no:" . $soNumber;

                $this->sendAlert($subject, $xml, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
            }
        }

        return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
    }

    public function queryTransaction($inputParameters = [], &$dataFromPmgw, &$dataToPmgw, &$soData, &$soccData, &$sopsData) {
        return $this->processPaymentStatus(
            []
            , ["transaction_id" => $inputParameters["transaction_id"], "soNo" => $inputParameters["so_no"]]
            , $soNo
            , $noUse
            , $noUse
            , $soData
            , $sopsData
            , $soccData
            , $noUse
        );
    }

    public function processPaymentStatus($generalData = [], $getData = [], &$soNumber, &$dataFromPmgw, &$dataToPmgw, &$soData, &$sopsData, &$soccData, &$sorData)
    {
        $txn_id = $getData["REF"];
        $mac = $getData["RETURNMAC"];
        if ($getData["transaction_id"]) {
            $this->so = $this->getSo($getData["soNo"]);
        } else {
            $this->so = $this->getDao('So')->getSoWithPmgw(["so.txn_id" => $txn_id, "sops.mac_token" => $mac], ["limit"=>1]);
        }

        $dataFromPmgw = $this->arrayImplode('=', ',', $getData);
        if ($this->so) {
            $soNumber = $this->so->getSoNo();
            return $this->_commonProcessStatus($soNumber, $sopsData, $soccData);
        } else {
            $message = $dataFromPmgw;
            $subject = $this->getPaymentGatewayName() . " Cannot get so_no";
            $this->sendAlert($subject, $message, $this->getTechnicalSupportEmail(), BaseService::ALERT_GENERAL_LEVEL);
        }
        return PaymentGatewayRedirectService::PAYMENT_STATUS_FAIL;
    }

    public function processFailureAction()
    {
        header("Location:" . $this->getFailUrl());
        exit;
    }

    public function processCancelAction()
    {
        header("Location:" . $this->getCancelUrl());
        exit;
    }

    public function processReviewAction() {
    }

    public function isPaymentNeedCreditCheck3D($isFraud = false)
    {
        $amount = $this->so->getAmount();
        $eci = $this->sops->getRiskRef4();
        $currency = $this->so->getCurrencyId();

        if (array_key_exists($currency, $this->_creditCheckAmountByCurrency)
            && $this->isEciLevelOne($eci)
        ) {
            if ($amount >= $this->_creditCheckAmountByCurrency[$currency]) {
                return true;
            } else {
                return false;
            }
        } else if ($this->isEciLevelTwo($eci) || $this->isEciLevelThree($eci)) {
            return true;
        }

        return true;
    }
/*
    public function isEciLevelOne($eci)
    {
        $eciArr = ['2', '02', '5', '05'];

        if (in_array($eci, $eciArr)) {
            return true;
        }

        return false;
    }

    public function isEciLevelTwo($eci)
    {
        $eciArr = ['0', '00', '1', '01', '6', '06', '7', '07', '', null];

        if (in_array($eci, $eciArr)) {
            return true;
        }

        return false;
    }
*/
    public function isPaymentNeedCreditCheck($isFraud = false)
    {
        return $this->isPaymentNeedCreditCheck3D($isFraud);
    }

    public function isNeedDmService($isFraud = false)
    {
        return $this->isPaymentNeedCreditCheck($isFraud);
    }

    public function useIframe() {
        return true;
    }
}

