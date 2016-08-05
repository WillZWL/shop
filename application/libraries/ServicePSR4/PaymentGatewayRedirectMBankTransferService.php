<?php
namespace ESG\Panther\Service;
use ESG\Panther\Service\Paypal\PaypalRequest;

class PaymentGatewayRedirectMBankTransferService extends PaymentGatewayRedirectService
{
    public function __construct($soObj, $debug = 0)
    {
        if ($debug == 1) {
            if ((getenv("APPLICATION_ENV") == "dev") || (php_sapi_name() === 'cli')) {
                $this->debug = $debug;
            }
        }
        if ($soObj != null)
            $this->so = $soObj;
    }

    public function checkout($orderFormInfo)
    {

        if ($this->so->getAmount() && $orderFormInfo["paymentGatewayId"] == "m_bank_transfer") {
            $this->sops = $this->getSoPaymentStatus();
            $this->sops->setPaymentStatus("N");
            $updateResult = $this->getService("SoFactory")->getDao("SoPaymentStatus")->update($this->sops);
            if (!$updateResult) {
                $subject = "[Panther] fail to update so payment status" . $this->getPaymentGatewayName() . ", so_no:(" . $this->so->getSoNo() . ") " . __METHOD__ . __LINE__;
                $message = $this->getService("SoFactory")->getDao("SoPaymentStatus")->db->last_query() . "," . $this->getService("SoFactory")->getDao("SoPaymentStatus")->db->error()["message"];
                $this->sendAlert($subject, $message, $this->getTechnicalSupportEmail(), BaseService::ALERT_HAZARD_LEVEL);
                return $this->checkoutFailureHandler(_("Please contact our CS") . ", err:" . __LINE__);
            }
            return ["url" => $this->getBankTransferUrl(), "error" => 0, "useIframe" => false];
        }
        return $this->checkoutFailureHandler(_("Please contact our CS") . ", err:" . __LINE__);
    }

    public function prepareGetUrlRequest($paymentInfo = [], &$requestData){

    }

    public function getRedirectUrl($requestData, &$responseData){

    }

    public function getPaymentGatewayName()
    {
        return "m_bank_transfer";
    }

    public function processPaymentStatus($generalData = [], $getData = [], &$soNumber, &$dataFromPmgw, &$dataToPmgw, &$soData, &$sopsData, &$soccData, &$sorData){

    }

    public function getReprocessPaymentStatusdirectUrl(){

    }

    public function getTechnicalSupportEmail()
    {
        return "feeling.liu@eservicesgroup.com";
    }

    public function processFailureAction(){

    }

    public function processCancelAction(){

    }

    public function processSuccessAction(){

    }

    public function processReviewAction(){

    }

    public function isPaymentNeedCreditCheck($isFraud = false){

    }

    public function queryTransaction($inputParameters = [], &$dataFromPmgw, &$dataToPmgw, &$soData, &$soccData, &$sopsData){

    }

    public function isNeedDmService($isFraud = false){

    }

    public function processNotification($data, &$soNo, &$soPara = [], &$sopsPara = [], &$soccPara = [], &$sorData = [], &$dataToPmgw, &$dataFromPmgw){

    }

    public function useIframe(){

    }
}


