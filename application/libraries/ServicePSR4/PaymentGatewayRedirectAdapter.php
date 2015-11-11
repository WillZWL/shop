<?php
namespace ESG\Panther\Service;

class PaymentGatewayRedirectAdapter extends PaymentGatewayRedirectService {
    public function prepareGetUrlRequest($paymentInfo = [], &$requestData) {
    }
    public function getRedirectUrl($requestData, &$responseData) {
    }
    public function getPaymentGatewayName() {
    }
    public function processPaymentStatus($generalData = [], $getData = [], &$soNumber, &$dataFromPmgw, &$dataToPmgw, &$soData, &$sopsData, &$soccData, &$sorData) {
    }
    public function getTechnicalSupportEmail() {
    }
    public function processFailureAction() {
    }
    public function processCancelAction() {
    }
    public function processSuccessAction() {
    }
    public function processReviewAction() {
    }
    public function isPaymentNeedCreditCheck($isFraud = false) {
    }
    public function queryTransaction($inputParameters = [], &$dataFromPmgw, &$dataToPmgw, &$soData, &$soccData, &$sopsData) {
    }
    public function isNeedDmService($isFraud = false) {
    }
    public function processNotification($data, &$soNo, &$soPara = [], &$sopsPara = [], &$soccPara = [], &$sorData = [], &$dataToPmgw, &$dataFromPmgw) {
    }
}