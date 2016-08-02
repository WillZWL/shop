<?php 
namespace ESG\Panther\Service;

interface PaymentGatewayRedirectServiceInterface
{
    /**************************************************
     *   return the string/xml that will be sent to payment gateway and pass to $this->getRedirectUrl()
     *   $requestData will store the data for logging into database only.
     ***************************************************/
    public function prepareGetUrlRequest($paymentInfo = [], &$requestData);

    /**************************************************
     *   return the url if success, otherwise, return false
     *   $responseData will store the data for logging into database only.
     ***************************************************/
    public function getRedirectUrl($requestData, &$responseData);

    /****************************************************
     *   return the standard payment gateway name, unique id name
     *****************************************************/
    public function getPaymentGatewayName();

    /***************************************
     *   processPaymentStatus, just need to decide if the result is pass or fail.
     *   $general_data will usually be $_POST
     *   $get_data will usually be $_GET
     *   $so_number: so_number from payment gateway
     *   $data_from_pmgw, $data_to_pmgw, for logging into database
     *   $so_data: an output from the function that contain any update to so table
     *   $sops_data: an output from the function that contain any update to so_payment_status table
     *   $socc_data: an output from the function that contain any update to so_credit_chk table
     *   $sor_data: an output from the function that contain any update to so_risk table
     ****************************************/
    public function processPaymentStatus($generalData = [], $getData = [], &$soNumber, &$dataFromPmgw, &$dataToPmgw, &$soData, &$sopsData, &$soccData, &$sorData);

    /*********************************************************
     *   return the technical support email of this payment gateway
     **********************************************************/
    public function getTechnicalSupportEmail();

    /**********************************************************
     *   follow up action after payment failure for different payment gateway
     *   do nothing, if no follow up action
     ***********************************************************/
    public function processFailureAction();

    /**********************************************************
     *   follow up action after payment cancel for different payment gateway
     *   do nothing, if no follow up action
     ***********************************************************/
    public function processCancelAction();

    /**********************************************************
     *   follow up action after payment success for different payment gateway
     *   e.g. moneybookers ctpe will need to ACK from our server
     *   do nothing, if no follow up action
     *   every payment gateway need to decide when to send order confirmation email,
     *   this is the best place to do this
     ***********************************************************/
    public function processSuccessAction();

    /**********************************************************
     *   follow up action after payment review for different payment gateway
     *   e.g. paypal 
     *   do nothing, if no follow up action
     *   every payment gateway need to decide when to send order confirmation email,
     *   this is the best place to do this
     ***********************************************************/
    public function processReviewAction();

    /**************************************************************
     *   return TRUE if need credit check, otherwise, return false
     ***************************************************************/
    public function isPaymentNeedCreditCheck($isFraud = false);

    /*************************************************************
     *   queryTransaction, the input would be a transaction id client_id-so_no
     *   return would be success, fail or cancel
     *   return PAYMENT_NO_STATUS would means no implementation on that function
     *   inside this function each payment gateway is free to update payment status
     *   $this->so, $this->sops is ready before this function call
     **************************************************************/
    public function queryTransaction($inputParameters = [], &$dataFromPmgw, &$dataToPmgw, &$soData, &$soccData, &$sopsData);

    /*************************************************************
     *   isNeedDmService
     *   return true or false
     **************************************************************/
    public function isNeedDmService($isFraud = false);
    
    public function processNotification($data, &$soNo, &$soPara = [], &$sopsPara = [], &$soccPara = [], &$sorData = [], &$dataToPmgw, &$dataFromPmgw);
    /***********************************
    *   useIframe
    *   return true or false
    ************************************/
    public function useIframe();
}