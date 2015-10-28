<?php
namespace ESG\Panther\Service;

class PaymentGatewayRedirectAdapter extends PaymentGatewayRedirectService {
    public function prepareGetUrlRequest($paymentInfo = array(), &$requestData) {
    }
    public function getRedirectUrl($requestData, &$responseData) {
    }
    public function getPaymentGatewayName() {
    }
    public function processPaymentStatus($generalData = array(), $getData = array(), &$soNumber, &$dataFromPmgw, &$dataToPmgw, &$soData, &$sopsData, &$soccData, &$sorData) {
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
    public function queryTransaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data) {
    }
    public function isNeedDmService($isFraud = false) {
    }
    public function processNotification($data, &$soNo, &$soPara = array(), &$sopsPara = array(), &$soccPara = array(), &$sorData = array(), &$dataToPmgw, &$dataFromPmgw) {
    }
}