<?php
namespace ESG\Panther\Service;

//use ESG\Panther\Service\SoFactoryService
use ESG\Panther\Dao\CountryDao;
use ESG\Panther\Service\AffiliateService;

abstract class PaymentGatewayRedirectService extends BaseService
implements PaymentGatewayRedirectServiceInterface
{
    const PAYMENT_WITH_INVALID_RESPONSE = 999;
    const PAYMENT_STATUS_FAIL = 1;
    const PAYMENT_STATUS_CANCEL = 2;
    const PAYMENT_STATUS_SUCCESS = 3;
    const PAYMENT_STATUS_KEEP_PENDING = 4;
    const PAYMENT_STATUS_REVIEW = 5;
    const PAYMENT_INVALID_AMOUNT = 8;
    const PAYMENT_STATUS_REVERSE = 9;
    const PAYMENT_STATUS_REFUNDED = 10;
    const PAYMENT_NO_STATUS = 10;
    const ORDER_SUCCESS_TO_SUCESS = 100;
    const ORDER_SUCCESS_TO_FAIL = 101;
    const ORDER_FAIL_TO_SUCESS = 102;
    const ORDER_FAIL_TO_FAIL = 103;

    const REFUND_STATUS_SUCCESS = 1;
    const REFUND_STATUS_REQUIRE_RETRY = 2;
    const REFUND_STATUS_ERROR = 3;

    public $debug = 0;
    protected $sitedownEmail = "oswald-alert@eservicesgroup.com, jesslyn@eservicesgroup.com, compliance-alerts@eservicesgroup.net";

    public $so = null;
    public $client = null;
    public $soids = null;
    public $sops = null;
    public $soFactory = null;
    public $siteObj = null;

    public function __construct($soObj = null, $debug = 0)
    {
        parent::__construct();
        if ($debug == 1) {
            if ((getenv("APPLICATION_ENV") == "dev") || (php_sapi_name() === 'cli')) {
                $this->debug = $debug;
            }
        }
        if ($soObj != null)
            $this->so = $soObj;
       $this->affiliateService=new AffiliateService();
    }

    protected function getClient($id = null)
    {
        if (!$this->client) {
            if ((!$id) && ($this->so))
                $id = $this->so->getClientId();
            $this->client = $this->getService("SoFactory")->getDao("Client")->get(["id" => $id]);
        }
        return $this->client;
    }

    protected function getSo($soNo)
    {
        if (!$this->so)
            $this->so = $this->getService("SoFactory")->getDao("So")->get(["so_no" => $soNo]);
        return $this->so;
    }

    protected function getSoItemDetail($soNo = null)
    {
        if (!$this->soi)
        {
            if ($soNo)
                $this->soids = $this->getService("SoFactory")->getDao("SoItemDetail")->getList(["so_no" => $soNo], ["limit" => -1]);
            else
                $this->soids = $this->getService("SoFactory")->getDao("SoItemDetail")->getList(["so_no" => $this->so->getSoNo()], ["limit" => -1]);
        }
        return $this->soids;
    }

    protected function getSoPaymentStatus($soNo = null)
    {
        if (!$this->sops)
        {
            if ($soNo)
                $this->sops = $this->getService("SoFactory")->getDao("SoPaymentStatus")->get(["so_no" => $soNo]);
            else
                $this->sops = $this->getService("SoFactory")->getDao("SoPaymentStatus")->get(["so_no" => $this->so->getSoNo()]);
        }
        return $this->sops;
    }

    protected function getSoExt($soNo = null)
    {
        if (!$this->soext)
        {
            if ($soNo)
                $this->soext = $this->getService("SoFactory")->getDao("SoExtend")->get(["so_no" => $soNo]);
            else
                $this->soext = $this->getService("SoFactory")->getDao("SoExtend")->get(["so_no" => $this->so->getSoNo()]);
        }
        return $this->soext;
    }

    protected function getSoCreditChk($soNo = null)
    {
        if (!$this->socc)
        {
            if ($soNo)
                $this->socc = $this->getService("SoFactory")->getDao("SoCreditChk")->get(["so_no" => $soNo]);
            else
                $this->socc = $this->getService("SoFactory")->getDao("SoCreditChk")->get(["so_no" => $this->so->getSoNo()]);
        }
        return $this->socc;
    }

    public function checkout($orderFormInfo)
    {
        if ($this->so->getAmount()) {
            $urlRequest = $this->prepareGetUrlRequest($orderFormInfo, $requestData);
            if (($requestData != null) && (!empty($requestData)))
                $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "O", str_replace("&", "\n&", $requestData));
            $callResult = $this->getRedirectUrl($urlRequest, $responseData);

            $redirectUrl = $callResult["url"];
            if (($responseData != null) && (!empty($responseData)))
                $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "I", str_replace("&", "\n&", $responseData));
            if (!$callResult["result"]) {
                if (!$callResult["siteDown"]) {
                    $subject = "[Panther] fail to get " . $this->getPaymentGatewayName() . " return URL, so_no:(" . $this->so->getSoNo() . ") " . __METHOD__ . __LINE__;
                    $alertMessage = $responseData;
                    $messageToUser = $callResult["errorMessageToClient"];
                } else {
                    $subject = "[Panther] " . $this->getPaymentGatewayName() . " site down, so_no:(" . $this->so->getSoNo() . ") " . __METHOD__ . __LINE__;
                    $alertMessage = $callResult["siteDownErrorMessage"];
                    $messageToUser = _("Please contact our CS") . ", err:" . __LINE__;
                }
                $this->sendAlert($subject, $alertMessage, $this->getTechnicalSupportEmail(), BaseService::ALERT_HAZARD_LEVEL);
                return $this->checkoutFailureHandler($messageToUser, $callResult["siteDown"]);
            } else {
                $this->sops = $this->getSoPaymentStatus();
                if ($orderFormInfo["paymentGatewayId"] == "w_bank_transfer") {
                    $this->sops->setPaymentStatus("N");
                } else {
                    $this->sops->setPaymentStatus("P");
                }

                $updateResult = $this->getService("SoFactory")->getDao("SoPaymentStatus")->update($this->sops);
                if (!$updateResult) {
                    $subject = "[Panther] fail to update so payment status" . $this->getPaymentGatewayName() . ", so_no:(" . $this->so->getSoNo() . ") " . __METHOD__ . __LINE__;
                    $message = $this->getService("SoFactory")->getDao("SoPaymentStatus")->db->last_query() . "," . $this->getService("SoFactory")->getDao("SoPaymentStatus")->db->error()["message"];
                    $this->sendAlert($subject, $message, $this->getTechnicalSupportEmail(), BaseService::ALERT_HAZARD_LEVEL);
                    return $this->checkoutFailureHandler(_("Please contact our CS") . ", err:" . __LINE__);
                }
                return ["url" => $redirectUrl, "error" => 0, "useIframe" => $this->useIframe()];
            }
        }
        return $this->checkoutFailureHandler(_("Please contact our CS") . ", err:" . __LINE__);
    }

    public function isPaymentStatusCorrect($result, &$message) {
        $message = "";
        if (($result == self::PAYMENT_STATUS_SUCCESS)
            && ($this->so->getStatus() > 1)
            && ($this->sops->getPaymentStatus() == 'S')) {
            return self::ORDER_SUCCESS_TO_SUCESS;
        } else {
            if (($result == self::PAYMENT_STATUS_SUCCESS)
                && (($this->so->getStatus() <= 1) || ($this->sops->getPaymentStatus() != 'S'))) {
                $message = $this->so->getSoNo() . "\nPayment_gateway query return PAID, system return FAIL";
                return self::ORDER_FAIL_TO_SUCESS;
            } else if (($result != self::PAYMENT_STATUS_SUCCESS)
                && (($this->so->getStatus() > 1) || ($this->sops->getPaymentStatus() == 'S'))) {
//success to fail case
                $message = $this->so->getSoNo() . "\nPayment_gateway query return FAIL, system return PAID";
                return self::ORDER_SUCCESS_TO_FAIL;
            } else {
                return self::ORDER_FAIL_TO_FAIL;
            }
        }
    }

    public function queryPaymentStatusInGeneral($soNo)
    {
        $take_action = FALSE;
        $message = "";
        if ($this->so = $this->getService("SoFactory")->getDao("So")->get(["so_no" => $soNo])) {
            $this->sops = $this->getService("SoFactory")->getDao("SoPaymentStatus")->get(["so_no" => $soNo]);
            $result = $this->queryTransaction(["transaction_id" => $soNo, "so_no" => $soNo], $dataFromPmgw, $dataToPmgw, $soData, $soccData, $sopsData);
            if ($dataToPmgw)
                $this->getService("SoPaymentQueryLog")->addLog($this->so->getSoNo(), "O", str_replace("&", "\n&", $dataToPmgw));
            if ($dataFromPmgw)
                $this->getService("SoPaymentQueryLog")->addLog($this->so->getSoNo(), "I", str_replace("&", "\n&", $dataFromPmgw));
            if ($soccData)
                $this->createSocc($soccData);
//see if the status match
            $takeAction = $this->isPaymentStatusCorrect($result, $message);
            if ($takeAction == self::ORDER_FAIL_TO_SUCESS) {
//send email
//could also be correct payment status by each payment gateway service
                $this->paymentSuccessOperation($soData, $sopsData, $soccData);
                $this->sendConfirmationEmail($this->so);
                mail($this->getTechnicalSupportEmail(), "[Panther] " . $this->getPaymentGatewayName() . " order from fail to success, soNo:" . $soNo, $message, 'From: website@digitaldiscount.co.uk');
                return TRUE;
            } else if ($takeAction == self::ORDER_SUCCESS_TO_FAIL) {
                if ((($this->so->getStatus() >= 2) && ($this->so->getStatus() < 6)) && ($this->so->getRefundStatus() == 0)&& ($this->so->getHoldStatus() == 0)) {
                    $holdAuto = "Auto hold by system, from success to cancel";

                    $orderNoteObj = $this->getService("SoFactory")->getDao("OrderNotesDao")->get();
                    $orderNoteObj->setSoNo($this->so->getSoNo());
                    $orderNoteObj->setType('O');
                    $orderNoteObj->setNote('Order payment status changed from success to fail by ' . $this->get_payment_gateway_name() . ', need compliance to verify');
                    $this->getService("SoFactory")->getDao("OrderNotesDao")->insert($orderNoteObj);

                    $holdReasonObj = $this->getService("SoFactory")->getDao("soHoldReasonDao")->get();
                    $holdReasonObj->setSoNo($this->so->getSoNo());
                    $holdReasonObj->setReason("change_of_address");
                    $this->getService("SoFactory")->getDao("soHoldReasonDao")->insert($holdReasonObj);

                    $this->so->setStatus(2);
                    $this->so->setHoldStatus(1);
                    if (!$this->getService("SoFactory")->getDao("So")->update($this->so)) {
                        mail($this->getTechnicalSupportEmail(), '[Panther] ' . $this->getPaymentGatewayName() . ' order from success to fail:' . $this->so->getClientId() . '-' . $soNo, $holdAuto, 'From: website@digitaldiscount.co.uk');
                    }
                } else {
                    $holdAuto = "No change in status in the system, order was hold before";
                }
//if yes, put order on hold and send email to compliance to verify
                mail('compliance@digitaldiscount.co.uk', '[Panther] ' . $this->getPaymentGatewayName() . ' order from success to fail:' . $this->so->getClientId() . '-' . $soNo, $message, 'From: website@digitaldiscount.co.uk');
                return FALSE;
            } else
                return TRUE;
        } else {
            return FALSE;
        }
    }

    public function processSuccessAction() {
        header("Location:" . $this->getSuccessfulUrl($this->so->getSoNo()));
        exit;
    }

    public function processPaymentStatusInGeneral($generalData = [], $getData = [])
    {
        $result = $this->processPaymentStatus($generalData, $getData, $soNoFromPmgw, $dataFromPmgw, $dataToPmgw, $soData, $sopsData, $soccData, $sorData);
//        $so_srv = $this->get_so_srv();
        if ($this->so = $this->getSo($soNoFromPmgw)) {
            $this->sops = $this->getSoPaymentStatus();
//save the log first
            if (($dataFromPmgw != null) && (!empty($dataFromPmgw)))
                $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "I", str_replace("&", "\n&", $dataFromPmgw));
            if (($dataToPmgw != null) && (!empty($dataToPmgw)))
                $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "O", str_replace("&", "\n&", $dataToPmgw));
            if ($result == PaymentGatewayRedirectService::PAYMENT_STATUS_CANCEL) {
                $this->paymentCancelOperation($soData, $sopsData, $soccData);
                $this->processCancelAction();
            } else if ($result == PaymentGatewayRedirectService::PAYMENT_STATUS_SUCCESS) {
                $this->paymentSuccessOperation($soData, $sopsData, $soccData, $sorData);
                $this->processSuccessAction();
            } else if ($result == PaymentGatewayRedirectService::PAYMENT_STATUS_REVIEW) {
                $this->processReviewAction();
            } else if ($result == PaymentGatewayRedirectService::PAYMENT_STATUS_KEEP_PENDING) {
//failure first, but no fail operation, in this case, it will be still pending and wait for the cron to update the payment status
                $this->paymentPendingOperation($soData, $sopsData, $soccData);
                $this->processFailureAction();
            } else {
                if ($result == PaymentGatewayRedirectService::PAYMENT_INVALID_AMOUNT) {
                    $subject = "[Panther] - " . $this->getPaymentGatewayName() . " invalid amount";
                    $message = $dataFromPmgw;
                    mail($this->getTechnicalSupportEmail(), $subject, $message);
                }
                $this->paymentFailOperation($soData, $sopsData, $soccData);
                $this->processFailureAction();
            }
        } else {
//probably invalid so number, so, cannot update database
            $this->_sendFatalEmail($generalData, $getData);
            $this->processFailureAction($generalData, $getData);
        }
    }

    private function _sendFatalEmail($generalData, $getData) {
//email to technical team
        $subject = "[Panther] - " . $this->getPaymentGatewayName() . " fatal error";
        $message = "";
        if (is_array($generalData))
            $message .= $this->arrayImplode('=', ',', $generalData);
        else if (!empty($generalData))
            $message .= $generalData;
        if (is_array($getData))
            $message .= "," . $this->arrayImplode('=', ',', $getData);
        else if (!empty($getData))
            $message .= $getData;
        $message .= $this->getService("SoFactory")->getDao("So")->db->error()["message"];
        mail($this->getTechnicalSupportEmail(), $subject, $message);
    }
/********************************************************************************
 *   paymentSuccessOperation could be overrided to do special sucess operation
 *********************************************************************************/
    protected function paymentSuccessOperation($soPara = [], $sopsPara = [], $soccPara = [], $sorData = [])
    {
//Sops
        if ($sopsPara)
            set_value($this->sops, $sopsPara);
        $this->sops->setPaymentStatus("S");
        if ((!$this->sops->getPayDate())
            || ($this->sops->getPayDate() == "0000-00-00 00:00:00"))
            $this->sops->setPayDate(date('Y-m-d H:i:s'));
        $this->getService("SoFactory")->getDao("SoPaymentStatus")->update($this->sops);
        $this->createSocc($soccPara);

#2494 do the fraud oder checking
        //$this->get_so_srv()->process_fraud_order($this->so);
/*
        if ($isFraud = $this->getService("SoFactory")->isFraudOrder($this->so)) {
            $this->getService("SoFactory")->processFraudOrder($this->so);
        } else
*/
//temporary set to no fraud order
        $isFraud = false;
        {
//check if this order pass before
            if ($this->so->getStatus() <= 1) {
//need credit check handling
                $this->so->setStatus((($this->isPaymentNeedCreditCheck($isFraud))?2:3));
                if ($soPara)
                    set_value($this->so, $soPara);
                $this->getService("SoFactory")->getDao("So")->update($this->so);
                $this->getService("So")->updateWebsiteDisplayQty($this->so);
//                $this->get_so_srv()->update_website_display_qty($this->so);
//CYBS decision manager
                if ($this->isNeedDmService($isFraud)) {
                    if (sizeof($sorData) > 0) {
                        $insertData = $sorData;
                    } else {
                        $insertData = ["risk_requested" => 0];
                    }
                    $this->createSor($insertData);
                }
//update promotion code
                $this->updatePromo($this->so->getPromotionCode());
                $this->sendConfirmationEmail($this->so);
            } else if ($this->so->getStatus() == 2) {
//status from 2 to 3 because of 3D info
                if (!$this->isPaymentNeedCreditCheck($isFraud)) {
                    $this->so->setStatus(3);
                    set_value($this->so, $soPara);
                    $this->getService("SoFactory")->getDao("So")->update($this->so);
//cc and dm are related
                    if (!$this->isNeedDmService($isFraud)) {
                        if (sizeof($sorData) > 0) {
                            $sorData["risk_requested"] = 2;
                            $updateData = $sorData;
                        } else {
                            $updateData = array("risk_requested" => 2);
                        }
//we don't add, update only, before if no record, meaning that no dm
                        $this->createSor($updateData);
                    }
                }
            }
        }
        $this->_unsetVariable();
//debug
//    print $this->sendConfirmationEmail($this->so, true);
    }

    public function isEciLevelOne($eci)
    {
        if (($eci == "05") || ($eci == "02")
            || ($eci == "5") || ($eci == "2"))
            return true;
        return false;
    }

    public function isEciLevelTwo($eci)
    {
        if (($eci == "06")	|| ($eci == "01")
            || ($eci == "6")	|| ($eci == "1"))
            return true;
        return false;
    }

    public function isEciLevelThree($eci)
    {
        if (($eci == "07")	|| ($eci == "00")
            || ($eci == "7") || ($eci == "0")
            || is_null($eci) || ($eci == ""))
            return true;
        return false;
    }

    private function _unsetVariable()
    {
        unset($_SESSION["cart"]);
//        unset($_SESSION["ra_items"]);
//        unset($_SESSION["warranty"]);
        $this->affiliateService->removeAfRecord();
        unset($_SESSION["promotion_code"]);
        unset($_SESSION["CART_QUICK_INFO"]);
    }

/********************************************************************************
 *   paymentKeepPendingOperation could be overrided to do special pending operation
 *********************************************************************************/
    protected function paymentPendingOperation($soPara = [], $sopsPara = [], $soccPara = [])
    {
        if ($this->so->getStatus() >= 2) {
            mail($this->getTechnicalSupportEmail() . ", compliance@digitaldiscount.co.uk", '[Panther] ' . $this->getPaymentGatewayName() . ' Order try to come from success to pending:' . $this->so->getClientId() . '-' . $this->so->getSoNo(), "Please check the payment and notify IT to manually update the status", 'From: website@digitaldiscount.co.uk');
        } else {
            if ($sopsPara)
                set_value($this->sops, $sopsPara);
            $this->sops->setPaymentStatus("P");
            $this->getService("SoFactory")->getDao("SoPaymentStatus")->update($this->sops);

            $this->so->setStatus(0);
            $this->getService("SoFactory")->getDao("So")->update($this->so);

            if ($soccPara) {
                $this->createSocc($soccPara);
            }
        }
    }

/********************************************************************************
 *   paymentFailOperation could be overrided to do special fail operation
 *********************************************************************************/
    protected function paymentFailOperation($soPara = [], $sopsPara = [], $soccPara = [])
    {
        if ($this->so->getStatus() >= 2) {
            mail($this->getTechnicalSupportEmail() . ",compliance@digitaldiscount.co.uk", '[Panther] ' . $this->getPaymentGatewayName() . ' Order try to come from success to fail:' . $this->so->getClientId() . '-' . $this->so->getSoNo(), "Please check the payment and notify IT to manually update the status", 'From: website@digitaldiscount.co.uk');
        } else {
            if ($sopsPara)
                set_value($this->sops, $sopsPara);
            $this->sops->setPaymentStatus("F");
            $this->getService("SoFactory")->getDao("SoPaymentStatus")->update($this->sops);

            $this->so->setStatus(0);
            $this->getService("SoFactory")->getDao("So")->update($this->so);

            if ($soccPara) {
                $this->createSocc($soccPara);
            }
        }
    }

/********************************************************************************
 *   paymentCancelOperation could be overrided to do special cancel operation
 *********************************************************************************/
    protected function paymentCancelOperation($soPara = [], $sopsPara = [], $soccPara = [])
    {
        if ($sopsPara)
            set_value($this->sops, $sopsPara);
        $this->sops->setPaymentStatus("C");
        $this->getService("SoFactory")->getDao("SoPaymentStatus")->update($this->sops);

        if ($soPara)
            set_value($this->so, $soPara);
        $this->so->setStatus(0);
        $this->getService("SoFactory")->getDao("So")->update($this->so);

        if ($soccPara)
            $this->createSocc($soccPara);
    }

    public function createSor($vars = [], $soNo = null)
    {
        if ($soNo)
            $this->sor = $this->getService("SoFactory")->getDao("SoRisk")->get(array("so_no" => $soNo));
        else
            $this->sor = $this->getService("SoFactory")->getDao("SoRisk")->get(array("so_no" => $this->so->getSoNo()));
        if (!$this->sor) {
            $sorObj = $this->getService("SoFactory")->getDao("SoRisk")->get();
            $action = "insert";
        } else {
            $sorObj = $this->sor;
            $action = "update";
        }

        set_value($sorObj, $vars);
        $sorObj->setSoNo($this->so->getSoNo());
        $dbResult = $this->getService("SoFactory")->getDao("SoRisk")->$action($sorObj);
        if ($dbResult === false)
        {
            $subject = "[Panther] fail to insert/update so_rsik" . $this->getPaymentGatewayName() . ", so_no:(" . $this->so->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getService("SoFactory")->getDao("SoRisk")->db->last_query() . "," . $this->getService("SoFactory")->getDao("SoRisk")->db->error()["message"];
            $this->sendAlert($subject, $message, $this->getTechnicalSupportEmail(), BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
    }

    protected function createSocc($soccData)
    {
        if (($socc_data) && is_array($socc_data)) {
            $this->socc = $this->getSoCreditChk();

            if (empty($this->socc)) {
                $this->socc = $this->getService("SoFactory")->getDao("SoCreditChk")->get();
                $this->socc->setSoNo($this->so->getSoNo());
                $insert = true;
            } else {
                $insert = false;
            }

            set_value($this->socc, $soccData);
            if ($insert) {
                $dbResult = $this->getService("SoFactory")->getDao("SoCreditChk")->insert($this->socc);
            } else {
                $dbResult = $this->getService("SoFactory")->getDao("SoCreditChk")->update($this->socc);
            }
            if ($dbResult === false)
            {
                $subject = "[Panther] fail to add so_credit_chk" . $this->getPaymentGatewayName() . ", so_no:(" . $this->so->getSoNo() . ") " . __METHOD__ . __LINE__;
                $message = $this->getService("SoFactory")->getDao("SoCreditChk")->db->last_query() . "," . $this->getService("SoFactory")->getDao("SoCreditChk")->db->error()["message"];
                $this->sendAlert($subject, $message, $this->getTechnicalSupportEmail(), BaseService::ALERT_HAZARD_LEVEL);
                return false;
            }
        }
    }

    public function notification($data)
    {
        $fullResult = $this->processNotification($data, $soNo, $soPara, $sopsPara, $soccPara, $sorData, $dataToPmgw, $dataFromPmgw);

        if ($fullResult)
        {
            if ($this->so = $this->getSo($soNo)) {
                $this->sops = $this->getSoPaymentStatus($soNo);
                if ($dataToPmgw)
                    $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "O", $dataToPmgw);
                if ($dataFromPmgw)
                    $this->getService("SoPaymentLog")->addLog($this->so->getSoNo(), "I", $this->arrayImplode("=", ",", $dataFromPmgw));
                if ($fullResult == PaymentGatewayRedirectService::PAYMENT_STATUS_SUCCESS)
                {
                    $this->paymentSuccessOperation($soData, $sopsData, $soccData, $sorData);
                }
                else if ($fullResult == PaymentGatewayRedirectService::PAYMENT_STATUS_REVERSE) {
                    $message = "Payment Reversed";
                    if ($this->so->getStatus() >= 2)
                    {
                        $subject = "[Panther] Order from Paid to reverse so_no:" . $this->so->getSoNo();
                        $message = "Order auto hold";
                        $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com, compliance@eservicesgroup.net");
    //the idea is to do auto hold and set a reason to so.hold_status in common for this case
                        if (($this->so->getStatus() == 3) && ($this->so->getHoldStatus() == 0)) {
    //auto hold
    //                    $this->getService("SoFactory")->holdOrder($this->so, $message);
                        }
                        if ($this->so->getStatus() == 5) {
    //need to send alert to logistics + compliance
                        }
                    }
                }
            }
        }
    }

    public function sendInternalPaymentSuccessEmail($soObj)
    {
        if ($soObj->getPaymentGatewayId() == "global_collect") {
            $email = "globalcollect@chatandvision.com";
            $subject = "GlobalCollect Payment received";
            $content = "Platform Id:" . $soObj->getPlatformId() . "\r\n";
            $content .= "Order number:" . $soObj->getSoNo() . "\r\n";
            $content .= "Gateway:" . $soObj->getPaymentGatewayId() . "\r\n";
            $content .= "Amount:" . $soObj->getAmount() . " " . $soObj->getCurrencyId() . "\r\n";
            mail($email, $subject, $content, "From: website@digitaldiscount.co.uk\r\n");
        }
    }

    public function sendConfirmationEmail($soObj = null, $getEmailHtml = FALSE)
    {
        $eventService = new EventService();
//can put so obj into this function for the future to build function to re-send order confirmation
        if (!$soObj)
        {
            $soObj = $this->so;
        }
        $this->sendInternalPaymentSuccessEmail($soObj);
        $platformId = $soObj->getPlatformId();
//        $pbvObj = $this->platformBizVarService->get(array("selling_platform_id" => $platformId));
        $soSrv = $this->getService("SoFactory");
        $deliveryCountryObj = $this->getService("SoFactory")->getDao("Country")->get(["country_id" => $soObj->getDeliveryCountryId()]);
        $billingCountryObj = $this->getService("SoFactory")->getDao("Country")->get(["country_id" => $soObj->getBillCountryId()]);
        $client = $soSrv->getService("Client")->getDao("Client")->get(["id" => $soObj->getClientId()]);

        $replace["so_no"] = $soObj->getSoNo();
        $replace["client_id"] = $soObj->getClientId();
        $replace["forename"] = $client->getForename();
        $replace["delivery_name"] = $soObj->getDeliveryName();
        $replace["delivery_address_text"] = ($soObj->getDeliveryCompany() ? $soObj->getDeliveryCompany() . "\n" : "") . trim(str_replace("||", "\n", $soObj->getDeliveryAddress())) . "\n" . $soObj->getDeliveryCity() . " " . $soObj->getDeliveryState() . " " . $soObj->getDeliveryPostcode() . "\n" . $deliveryCountryObj->getName();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_name"] = $soObj->getBillName();
        $replace["billing_address_text"] = ($soObj->getBillCompany() ? $soObj->getBillCompany() . "\n" : "") . trim(str_replace("||", "\n", $soObj->getBillAddress())) . "\n" . $soObj->getBillCity() . " " . $soObj->getBillState() . " " . $soObj->getBillPostcode() . "\n" . $billingCountryObj->getName();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);
        $replace["promotion_code"] = $soObj->getPromotionCode();
        $replace["currency_id"] = $soObj->getCurrencyId();
        $siteObj = $this->_getSiteObj($platformId);
        $replace['currency_sign'] = $siteObj->getSign();
        $currencySign = $replace['currency_sign'];
        $replace["order_create_date"] = date("d/m/Y", strtotime($soObj->getOrderCreateDate()));
        $replace["amount"] = platform_curr_format($soObj->getAmount(), 0);
        $replace["expect_ship_days"] = "";//$soObj->get_expect_ship_days();
        $replace["expect_del_days"] = "";//$soObj->get_expect_del_days();
        $replace['site_url'] = base_url();

        $this->soids = $this->getSoItemDetail($soObj->getSoNo());

        $replace["so_items_text"] = '<table>';
        $isPreorder = false;
        // $priceObj = "";
        foreach($this->soids as $item) {
            $websiteStatus = $item->getWebsiteStatus();
            if (($websiteStatus == "P") || ($websiteStatus == "A")) {
                $isPreorder = true;
            }
            // if ($priceObj == "")
            //     $priceObj = $this->getService("Price")->getDao()->get(["platform_id" => $soObj->getPlatformId(), "sku" => $item->getItemSku()]);
            $total += $item->getAmount();
            $replace["so_items_text"] .=
                "<tr>
                    <td style='padding:4px 20px; color:#444; font-family:Arial; font-size: 12px;'>" . $item->getProdName() . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $item->getQty() . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currencySign . " " . platform_curr_format($item->getUnitPrice(), 0) . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currencySign . " " . platform_curr_format(($item->getUnitPrice() * $item->getQty()), 0) . "</td>
                </tr>\n";
        }
        $replace["so_items_text"] .= '</table>';

        // if ($priceObj) {
        //     $deliveryScenerioObj = $this->getService("DeliveryTime")->getDeliverytimeObj($soObj->getDeliveryCountryId(), $priceObj->getDeliveryScenarioid());
        //     if ($deliveryScenerioObj->getShipMinDay())
        //         $replace["expect_ship_days"] = $deliveryScenerioObj->getShipMinDay() . " - " . $deliveryScenerioObj->getShipMaxDay();
        //     if ($deliveryScenerioObj->getDelMinDay())
        //         $replace["expect_del_days"] = $deliveryScenerioObj->getDelMinDay() . " - " . $deliveryScenerioObj->getDelMaxDay();
        // }
        $replace["expect_ship_days"] = $soObj->getExpectShipDays();
        $replace["expect_del_days"] = $soObj->getExpectDelDays();

        $replace["total"] = platform_curr_format($total, 0);

        $replace["delivery_charge"] = platform_curr_format($soObj->getDeliveryCharge(), 0);
        $replace["email"] = $client->getEmail();
        $encrypt = new \CI_Encrypt();
        $replace["password"] = $encrypt->decode($client->getPassword());
        $dto = new \EventEmailDto();

        if (defined("SITE_NAME"))
            $replace["site_name"] = SITE_NAME;
        else {
            $replace["site_name"] = $siteObj->getSiteName();
        }
        $from_email = "no-reply@" . strtolower($replace["site_name"]);

        $replace["from_email"] = $from_email;
        $dto->setMailFrom($from_email);
        $dto->setMailTo($client->getEmail());
        $dto->setPlatformId($platformId);
        $dto->setEventId("payment_success");
        $dto->setTplId("payment_success");
        if ($this->soext = $this->getSoExt($soObj->getSoNo())) {
            $processingFee = $this->soext->getOfflineFee();
        }
        if (is_null($processingFee)) {
            $processingFee = 0;
        }

        $replace['processing_fee'] = platform_curr_format($processingFee, 0);

        $dto->setReplace($replace);
        if ($getEmailHtml === FALSE) {
            $eventService->fireEvent($dto, FALSE);
        } else {
            # debug for email_test.php
            $emailMsg = $eventService->fireEvent($dto, TRUE);
            return $emailMsg;
        }
    }

    private function _getSiteObj($platformId)
    {
        if (defined("SITE_NAME"))
            return \PUB_Controller::$siteInfo;
        else {
            if (!$this->siteObj)
            {
                $loadSiteService = new \ESG\Panther\Service\LoadSiteParameterService();
                $this->siteObj = $loadSiteService->loadSiteByPlatform($platformId);
            }
            return $this->siteObj;
        }
    }

    public function updatePromo($code)
    {
        if ($code) {
        }
    }

    public function stdObjToString($input, $array_key = "")
    {
        $string_to_return = "";
        foreach ($input as $key => $value) {
            if (is_object($value)) {
                $string_to_return .= $this->stdObjToString($value, "    " . $array_key . ($key . "_"));
            } else if (is_array($value)) {
                foreach ($value as $second_key => $second_value) {
                    $string_to_return .= $this->stdObjToString($second_value, "    " . $array_key . ($key . "_" . $second_key . "_"));
                }
            } else {
                $string_to_return .= $array_key . $key . "=" . $value . "\n";
            }
        }
        return $string_to_return;
    }

	protected function arrayImplode($glue, $separator, $array)
	{
		if (!is_array($array))
			return $array;
		$string = [];
		foreach ($array as $key => $val)
		{
			if (is_array($val))
			$val = implode( ',', $val );
			$string[] = "{$key}{$glue}{$val}";
		}
		return implode($separator, $string);
	}

    protected function getSuccessfulUrlTop($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/payment-result-top/1/" . $soNo . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getSuccessfulUrl($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/payment-result/1/" . $soNo . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getReviewUrl($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/payment-result/4/" . $soNo . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getFailUrlTop($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/payment-result-top/0/" . $soNo . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getFailUrl($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/payment-result/0/" . $soNo . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getBankTransferUrl($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/payment-result/5/" . $soNo . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getCancelUrlTop($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout" . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getCancelUrl($soNo = null)
    {
        if ((!$soNo) && ($this->so))
            $soNo = $this->so->getSoNo();
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout" . (($this->debug) ? "?debug=1" : "");
        return $url;
    }

    protected function getNotificationUrl($soNo = null)
    {
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/notification/" . strtolower($this->getPaymentGatewayName()) . (($this->debug) ? "?debug=1" : "");
        if ($soNo != null)
        {
            if ($this->debug)
                $url .= "&soNo=" . $soNo;
            else
                $url .= "?soNo=" . $soNo;
        }
        return $url;
    }

    protected function getResponseUrl($soNo = null)
    {
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/response/" . strtolower($this->getPaymentGatewayName()) . (($this->debug) ? "?debug=1" : "");
        if ($soNo != null)
        {
            if ($this->debug)
                $url .= "&soNo=" . $soNo;
            else
                $url .= "?soNo=" . $soNo;
        }
        return $url;
    }

/***************************************************************
*   checkout_failure_handler could be overrided to set different error reponse during checkout
*   this handler will only be suitable
****************************************************************/
    protected function checkoutFailureHandler($message = "", $siteDown = false)
    {
        return ["error" => -2, "errorMessage" => $message, "siteDown" => $siteDown, "url" => $this->getFailUrl((($this->so)?$this->so->getSoNo():""))];
    }

    protected function getSiteLogo()
    {
        $url = "https://" . $_SERVER['HTTP_HOST'] . "/images/logo/" . SITE_LOGO;
        return $url;
    }
}



