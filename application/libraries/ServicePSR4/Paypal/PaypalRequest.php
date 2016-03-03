<?php
namespace ESG\Panther\Service\Paypal;

class PaypalRequest
{
    private $_debug = 0;
    private $_account;
    private $_apiUrl;
    private $_paypalUrl;
    private $_paypalHost;

    public function __construct($debug)
    {
        $this->_debug = $debug;
    }

    public function setAccount($account, $apiUrl, $paypalUrl, $paypalHost)
    {
        $this->_account = $account;
        $this->_apiUrl = $apiUrl;
        $this->_paypalUrl = $paypalUrl;
        $this->_paypalHost = $paypalHost;
    }

    public function formPaymentRequest($order = array(), $setting = array(), &$postData = array())
    {
		$postData["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";
		if (getenv("APPLICATION_ENV") == "dev")
			$invoiceNumber = "D" . $order["soNo"];
		else
            $invoiceNumber = $order["soNo"];
		$postData["PAYMENTREQUEST_0_INVNUM"] = $invoiceNumber;
		$postData["PAYMENTREQUEST_0_AMT"] = $order["amount"];
        if (isset($order["vat"]))
            $vat = $order["vat"];
        else
            $vat = 0;
		$postData["PAYMENTREQUEST_0_TAXAMT"] = $vat;
		$postData["PAYMENTREQUEST_0_CURRENCYCODE"] = $order["currency"];
		$postData["PAYMENTREQUEST_0_SHIPPINGAMT"] = $order["deliveryCharge"];
        if ($order["paymentMethods"] != "paypal")
		{
			$postData["LANDINGPAGE"] = "Billing";
		}

		if ($order["item"])
		{
			foreach ($order["item"] as $item)
			{
				$lineNo = $item["lineNo"] - 1;
				$postData["L_PAYMENTREQUEST_0_NAME" . $lineNo] = $item["name"];
				$postData["L_PAYMENTREQUEST_0_NUMBER" . $lineNo] = $item["sku"];
				$postData["L_PAYMENTREQUEST_0_QTY" . $lineNo] = $item["qty"];
				$postData["L_PAYMENTREQUEST_0_AMT" . $lineNo] = $item["unitPrice"];
			}
		}
/*
		if ($this->promo["valid"])
		{
			$line_no++;
			$postData["L_PAYMENTREQUEST_0_NAME".$line_no] = $this->promo["promotion_code_obj"]->get_code();
			$postData["L_PAYMENTREQUEST_0_DESC".$line_no] = substr($this->promo["promotion_code_obj"]->get_description(), 0, 125);
			$postData["L_PAYMENTREQUEST_0_AMT".$line_no] = $this->promo["disc_amount"]*-1;
		}
*/
		$postData["PAYMENTREQUEST_0_ITEMAMT"] = $order["amount"] - $order["deliveryCharge"] - $vat;
        $postData["PAYMENTREQUEST_0_NOTIFYURL"] = $setting["notificationUrl"];
		$postData["RETURNURL"] = $setting["responseUrl"];
		$postData["CANCELURL"] = $setting["cancelUrl"];
        if (isset($setting["siteName"]))
            $postData["BRANDNAME"] = $setting["siteName"];

		$postData["LOCALECODE"] = $order["countryId"];
		$postData["HDRIMG"] = $setting["siteLogo"];

		$postData["ADDROVERRIDE"] = 1;
		$postData["PAYMENTREQUEST_0_SHIPTONAME"] = $order["deliveryName"];
		$postData["PAYMENTREQUEST_0_SHIPTOSTREET"] = $order["deliveryAddress1"];
		$postData["PAYMENTREQUEST_0_SHIPTOSTREET2"] = $order["deliveryAddress2"];
		$postData["PAYMENTREQUEST_0_SHIPTOCITY"] = $order["deliveryCity"];
        if ($order["deliveryState"])
            $postData["PAYMENTREQUEST_0_SHIPTOSTATE"] = $order["deliveryState"];
		$postData["PAYMENTREQUEST_0_SHIPTOZIP"] = $order["deliveryPostal"];
		$postData["PAYMENTREQUEST_0_SHIPTOCOUNTRY"] = $order["deliveryCountry"];    
        $this->setExpressChecout($postData);
        return $postData;
    }

    public function setExpressChecout(&$postData = array())
    {
		$postData["METHOD"] = "SetExpressCheckout";
    }

    public function executeSetExpressChecout($postData = array())
    {
		$postData["METHOD"] = "SetExpressCheckout";

        $result = $this->_submit($postData);
        if ($result["response"])
        {
            parse_str($result["response"], $result["parseResult"]);
        }
        else
        {
            $result["parseResult"] = "";
        }

        return $result;
    }

    public function getExpressCheckoutDetail(&$postData = array(), $data = array())
    {
		$postData["METHOD"] = "GetExpressCheckoutDetails";
		$postData["TOKEN"] = $data["token"];
        return $this->_submit($postData);
    }

    public function doExpressCheckout(&$postData = array(), $data = array())
    {
		$postData["METHOD"] = "DoExpressCheckoutPayment";
		$postData["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";
		$postData["PAYERID"] = $data["payerId"];
		$postData["PAYMENTREQUEST_0_AMT"] = $data["amount"];
		$postData["PAYMENTREQUEST_0_CURRENCYCODE"] = $data["currency"];
		$postData["TOKEN"] = $data["token"];
		$postData["RETURNFMFDETAILS"] = 1;
        return $this->_submit($postData);
    }

    public function verifyNotification($data)
    {
        return $this->_curlPost($data);
    }

    private function _curlPost($encodedData)
    {
        $url = $this->_paypalUrl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//temporary disable
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);

//        curl_setopt($ch, CURLOPT_HEADER, true);
//      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: " . "www.sandbox.paypal.com" , "Connection: Close"));

/*
            curl_setopt($ch, CURLOPT_SSLVERSION, 3);
*/
        $response = curl_exec($ch);
        $responseStatus = strval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        
        $errorNo = "";
        $errorMessage = "";

        if ($response === false || $responseStatus == '0')
        {
            $errorNo = curl_errno($ch);
            $errorMessage = curl_error($ch);
        }

        return array("response" => $response, "errorNo" => $errorNo, "errorMessage" => $errorMessage);
    }

    private function _submit($postData)
    {
        $result = true;
        $url = $this->_apiUrl;
        $postData["USER"] = $this->_account["userName"];
        $postData["PWD"] = $this->_account["password"];
        $postData["SIGNATURE"] = $this->_account["signature"];
        $postData["VERSION"] = "95";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, @http_build_query($postData));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
//        curl_setopt($ch, CURLOPT_HEADER, true);
//		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: " . $this->_account["paypalHost"] , "Connection: Close"));
//        curl_setopt($ch, CURLOPT_USERAGENT, "cURL/PHP");

        $response = curl_exec($ch);      
        $errorNo = "";
        $errorMessage = "";

        if (!$response)
        {
            $errorNo = curl_errno($ch);
            $errorMessage = curl_error($ch);
            $info = curl_getinfo($ch);
            $result = false;
        }
        curl_close($ch);
        return array("result" => $result
                    , "response" => $response
                    , "errorNo" => $errorNo
                    , "errorMessage" => $errorMessage
                    , "callInfo" => $info);
    }
}
