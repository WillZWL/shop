<?php
namespace ESG\Panther\Service\Moneybookers;

class MoneybookersRequest
{
    const MONEYBOOKERS_SERVER_PAYMENT = "https://www.moneybookers.com/app/payment.pl";
    const MONEYBOOKERS_SERVER_QUERY = "https://www.moneybookers.com/app/query.pl";

    private $_debug;
    private $_mbAccount;

    public function __construct($debug)
    {
        $this->_debug = $debug;
    }

    public function setAccount($mbAccount) {
        $this->_mbAccount = $mbAccount;
    }

    public function formPaymentRequest($order, $setting) {
        $postData = [];

        $postData["pay_to_email"] = $this->_mbAccount->payToEmail;
        $postData["recipient_description"] = $setting["siteName"];
        $postData["transaction_id"] = $order["soNo"];
        $postData["return_url"] = $setting["responseUrl"];
        $postData["return_url_text"] = $setting["returnText"];
        $postData["cancel_url"] = $setting["cancelUrl"];
        $postData["status_url"] = $setting["notificationUrl"];
        $postData["status_url2"] = $this->_mbAccount->status2Email;
        $postData["hide_login"] = 1;
        $postData["prepare_only"] = 1;
        $postData["new_window_redirect"] = 1;
        $postData["language"] = $setting["langId"];
        $postData["merchant_fields"] = "";
        $postData["confirmation_note"] = $setting["confirmationNote"];
        $postData["pay_from_email"] = $order["email"];
        $postData["title"] = $order["client"]["title"];
        $postData["firstname"] = $order["client"]["firstname"];
        $postData["lastname"] = $order["client"]["lastname"];
        $postData["address"] = $order["client"]["address1"];
        $postData["address2"] = $order["client"]["address2"];
        $postData["phone_number"] = $order["client"]["phoneNumber"];
        $postData["postal_code"] = $order["client"]["postCode"];

        $postData["city"] = $order["client"]["city"];
        $postData["state"] = $order["client"]["state"];
        $postData["country"] = $order["client"]["country"];
        $postData["amount"] = $order["amount"];
        $postData["currency"] = $order["currency"];
        $postData["amount2_description"] = $setting["amount2Description"];
        $postData["detail1_description"] = $setting["detail1Description"];
        $postData["detail1_text"] = $order["client"]["id"] . "-" . $order["soNo"];
        $postData["payment_methods"] = $order["paymentMethods"];
        return $postData;
    }

    public function sumbitQuery($transactionId) {
        $result = true;
        $url = self::MONEYBOOKERS_SERVER_QUERY . "?action=status_trn&email=" . $this->_mbAccount->payToEmail . "&password=" . $this->_mbAccount->queryPassword . "&trn_id=" . $transactionId;

        $cpt = curl_init();
        curl_setopt($cpt, CURLOPT_URL, $url);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($cpt, CURLOPT_USERAGENT, "php moneybooker post");
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 45);
        curl_setopt($cpt, CURLOPT_TIMEOUT, 45);
        curl_setopt($cpt, CURLOPT_POST, 0);

        $response = curl_exec($cpt);
        if (!$response) {
            $errorNo = curl_errno($cpt);
            $errorMessage = curl_error($cpt);
            $info = curl_getinfo($cpt);
            $result = false;
        }
        curl_close($cpt);
        return array("result" => $result
                    , "response" => $response
                    , "errorNo" => $errorNo
                    , "errorMessage" => $errorMessage
                    , "callInfo" => $info
                    , "url" => $url);

    }

    public function submitForm($postdata) {
        $result = true;
        $cpt = curl_init();

        curl_setopt($cpt, CURLOPT_URL, self::MONEYBOOKERS_SERVER_PAYMENT);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($cpt, CURLOPT_USERAGENT, "php moneybooker post");
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 45);
        curl_setopt($cpt, CURLOPT_TIMEOUT, 45);

        curl_setopt($cpt, CURLOPT_POST, 1);
        curl_setopt($cpt, CURLOPT_POSTFIELDS, $postdata);

        $response = curl_exec($cpt);
        if (!$response) {
            $errorNo = curl_errno($cpt);
            $errorMessage = curl_error($cpt);
            $info = curl_getinfo($cpt);
            $result = false;
        }
        curl_close($cpt);
        return array("result" => $result
                    , "response" => $response
                    , "errorNo" => $errorNo
                    , "errorMessage" => $errorMessage
                    , "callInfo" => $info);
    }
}