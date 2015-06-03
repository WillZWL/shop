<?php

class Yandex_integrator
{

	const SHOPID = "15355";
	const DEBUG_SHOPID = "15355";
	const SCID = "7324";
	const DEBUG_SCID = "51549";

//Yandex server for Web Service
	const YANDEX_WS_SERVER = "penelope.yamoney.ru";
	const DEBUG_YANDEX_WS_SERVER = "penelope-demo.yamoney.ru:8083";
	const YANDEX_WS_REFUND_API_PATH = "webservice/mws/api/returnPayment";

	const YANDEX_SERVER = "money.yandex.ru/eshop.xml";
	const DEBUG_YANDEX_SERVER = "demomoney.yandex.ru/eshop.xml";
	const DIGEST_PASSWORD = "udyh%Usd4d";
// private key pass phrase
	const YANDEX_PRIVATE_KEY_PASS_PHRASE = "JDISJjskdsjfdjskfiosudiuuiUUUII";
	const CERT_CHALLENGE_PASSWORD = "";
	const YANDEX_PAYMENT_PRIVATE_KEY = "private_valuebasket_yandex.key";
//	const YANDEX_PAYMENT_PRIVATE_PEM = "private_valuebasket_yandex.pem";
	const YANDEX_PROVIDED_CERT = "yandex/valuebasket.cer";
	const YANDEX_PROVIDED_CERT_BUNDLE_WITH_PRIVATE_IN_PKCS12 = "yandex/valuebasket.p12";

	const YANDEX_CA_CERT = "yandex/ym.p7b";

	const KEY_STORE_PATH = "/var/www/html/valuebasket_key/";

	const API_TYPE_REFUND = "refund_request";
	const API_TYPE_REFUND_RESPONSE = "refund_response";

	const CHECK_ORDER_SUCCESS = 0;
	const CHECK_ORDER_FAIL_MD5 = 1;
	const CHECK_ORDER_REJECT_PAYMENT = 100;
	const CHECK_ORDER_UNABLE_PROCESS = 200;
	const CHECK_ORDER_TECHNICAL_ERROR = 1000;
	const RESPONSE_MESSAGE_TYPE_CHECKORDER = "CHECKORDER";
	const RESPONSE_MESSAGE_TYPE_AVISO = "AVISO";

	const REFUND_STATUS_SUCCESS = 1;
	const REFUND_STATUS_REQUIRE_RETRY = 2;
	const REFUND_STATUS_ERROR = 3;

	public $curlResult;
	private $_shopId;
	private $_scid;
	private $_server;
	private $_apiServer;
	private $_curlError;
	private $_curlInfo;
	private $_requestType;
	private $_data_path = "";
	private $currecny_mapping = array("RUB" => 643);

	public function Yandex_integrator($debug = 0, $save_path)
	{
		$this->debug = $debug;
		$this->_data_path = $save_path;
		if ($this->debug)
		{
			$this->_shopId = self::DEBUG_SHOPID;
			$this->_scid = self::DEBUG_SCID;
			$this->_server = self::DEBUG_YANDEX_SERVER;
			$this->_apiServer = self::DEBUG_YANDEX_WS_SERVER;
		}
		else
		{
			$this->_shopId = self::SHOPID;
			$this->_scid = self::SCID;
			$this->_server = self::YANDEX_SERVER;
			$this->_apiServer = self::YANDEX_WS_SERVER;
		}
	}

	public function form_payment_array($params)
	{
		$requestParameters = array();
		$requestParameters["ShopID"] = $this->_shopId;
		$requestParameters["scid"] = $this->_scid;
		$requestParameters["sum"] = $params["totalAmount"];

		if ($this->debug)
			$requestParameters["CustomerNumber"] = $params["clientId"] . "||debug";
		else
			$requestParameters["CustomerNumber"] = $params["clientId"];
		$requestParameters["orderNumber"] = $params["orderId"];
		$requestParameters["shopSuccessURL"] = $params["successUrl"];
		$requestParameters["shopFailURL"] = $params["failUrl"];
		$requestParameters["cps_email"] = $params["email"];
		if ($params["card_code"] != "WM")
			$requestParameters["cps_phone"] = $params["tel"];

		$requestParameters["paymentType"] = $params["card_code"];

		return $requestParameters;
	}

	public function formRefundRequest($requestParas)
	{
		$this->_requestType = self::API_TYPE_REFUND;
		$dateTime = date("c");
		$currency_id = $this->currecny_mapping[$requestParas["currency_id"]];

$xmlString = <<<EOT
<?xml version='1.0' encoding='UTF-8'<returnPaymentRequest clientOrderId="{$requestParas["so_no"]}"
        requestDT="{$dateTime}"
        invoiceId="{$requestParas["transaction_id"]}"
        shopId="{$this->_shopId}"
        amount="{$requestParas["amount"]}"
        currency="{$currency_id}"
		cause="refund requested by customer"
/>
EOT;
//		print $xmlString;
		return utf8_encode($xmlString);
	}

	private function _save_request_file($refundRequestXml, $so_no)
	{
		$currentTime = date("YmdHis");
		$filePath = $this->_data_path . "orders/yandex/" . $this->_requestType;
		$filePath .= "/" . $currentTime . "_" . $so_no . ".txt";
		$fileName = $currentTime . "_" . $so_no . ".txt";

		$fh = fopen($filePath, "w");
		fwrite($fh, $refundRequestXml);
		fclose($fh);
		return array("filePath" => $filePath, "fileName" => $fileName, "requestXml" => $refundRequestXml);
	}

	public function form_refund_request_and_save($requestParas)
	{
		$requestStr = $this->formRefundRequest($requestParas);
		return $requestStr;
//		return $this->_save_request_file($requestStr, $requestParas["so_no"]);
	}

	private function _create_signed_message($message)
	{
		$signedMessagePath = $this->_data_path . "orders/yandex/" . self::API_TYPE_REFUND_RESPONSE . "/" . $requestFileName;
		$privateKeyPath = self::KEY_STORE_PATH . self::YANDEX_PAYMENT_PRIVATE_KEY;
		$yandexCerPath = self::KEY_STORE_PATH . self::YANDEX_PROVIDED_CERT;

		$descriptorspec = array( 0 => array("pipe", "r"),
									1 => array("pipe", "w"),
									2 => array("pipe", "w"));

		$process = proc_open("openssl smime -sign -signer " . $yandexCerPath .
							" -inkey " . $privateKeyPath .
							 " -nochain -nocerts -outform PEM -nodetach -passin pass:" . self::YANDEX_PRIVATE_KEY_PASS_PHRASE
							 , $descriptorspec, $pipes);
		if (is_resource($process))
		{
			fwrite($pipes[0], $message);
			fclose($pipes[0]);

			$pkcs7 = stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			$resCode = proc_close($process);
			if ($resCode != 0)
			{
				throw new Exception("OpenSSL call failed:" . $resCode . '\n' . $pkcs7);
			}
			return $pkcs7;
		}
		return false;
	}

	public function submitRefund($refundRequestMessage)
	{
//		$pkcs7SignResult = openssl_pkcs7_sign($requestPath, $signedMessagePath, $yandexCerPath, array($privateKeyPath, self::YANDEX_PRIVATE_KEY_PASS_PHRASE), array());
		try
		{
			$pkcs7SignResult = $this->_create_signed_message($refundRequestMessage);
		}
		catch(Exception $ex)
		{
			throw new Exception($ex->getMessage());
			return false;
		}

		if ($pkcs7SignResult)
		{
			$submitResult = $this->sendApiRquestToYandex($pkcs7SignResult);
			$responseXml = $submitResult["result"];
			$xmlResult = simplexml_load_string($responseXml);

			$so_no = (string) $xmlResult["clientOrderId"];
			$responseStatus = (string) $xmlResult["status"];
			$responseError = (string) $xmlResult["error"];
			$techMessage = (string) $xmlResult->techMessage;

			$response = array();
			$response["error"] = $responseError;
			$response["status"] = $responseStatus;
			$response["responseXml"] = $responseXml;
//			var_dump($responseError);
//			var_dump($responseStatus);

			if (($responseStatus == 0) && ($responseError == 0))
			{
				$response["result"] = self::REFUND_STATUS_SUCCESS;
			}
			else if ($responseStatus == 1)
			{
				$response["result"] = self::REFUND_STATUS_REQUIRE_RETRY;
			}
			else
			{
//refund instruction fail with error
				$response["result"] = self::REFUND_STATUS_ERROR;
			}
			return $response;
		}
		else
			return FALSE;
	}

	public function sendApiRquestToYandex($signedData)
	{
		$yandexCerPath = self::KEY_STORE_PATH . self::YANDEX_PROVIDED_CERT;
		$ourPrivateKey = self::KEY_STORE_PATH . self::YANDEX_PAYMENT_PRIVATE_KEY;
		$privateKeyPassword = self::YANDEX_PRIVATE_KEY_PASS_PHRASE;
		$refundServerApi = "https://" . $this->_apiServer . "/" . self::YANDEX_WS_REFUND_API_PATH;

	 	$cpt = curl_init();
		curl_setopt($cpt, CURLOPT_URL, $refundServerApi);
		curl_setopt($cpt, CURLOPT_HTTPHEADER, array("Content-Type: application/pkcs7-mime"));
		curl_setopt($cpt, CURLOPT_POST, true);
		curl_setopt($cpt, CURLOPT_POSTFIELDS, $signedData);
        curl_setopt($cpt, CURLOPT_HEADER, 0);
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($cpt, CURLOPT_SSLCERT, $yandexCerPath);
        curl_setopt($cpt, CURLOPT_SSLKEY, $ourPrivateKey);
        curl_setopt($cpt, CURLOPT_SSLKEYPASSWD, $privateKeyPassword);
/*
        curl_setopt($cpt, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($cpt, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($cpt, CURLOPT_TIMEOUT, 30);
		curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($cpt, CURLOPT_MAXREDIRS, 5);
*/
		$this->curlResult = curl_exec($cpt);
		$this->_curlError = curl_error($cpt);
		$this->_curlInfo = curl_getinfo($cpt);

/*		var_dump($refundServerApi);
		var_dump($this->curlResult);
		var_dump($this->_curlError);
		var_dump($this->_curlInfo);
*/
		curl_close($cpt);
		return array("result" => $this->curlResult, "error" => $this->_curlError, "info" => $this->_curlInfo);
	}

	public function submitPaymentForm($postData)
	{
		$result = $this->sendToYandex($postData);
		return $result["redirectUrl"];
	}

	public function createCheckOrderReply($status = self::CHECK_ORDER_TECHNICAL_ERROR, $invoiceId, $type)
	{
		$dateTime = date("c");
		if ($type == self::RESPONSE_MESSAGE_TYPE_AVISO)
			$tag = "paymentAvisoResponse";
		else
			$tag = "checkOrderResponse";
$xmlString = <<<EOT
<?xml version="1.0" encoding="UTF-8"<{$tag} performedDatetime="{$dateTime}"
					code="{$status}" invoiceId="{$invoiceId}"
					shopId="{$this->_shopId}"
EOT;
		if ($status == self::CHECK_ORDER_FAIL_MD5)
		{
			$xmlString .= " message=\"Message Digest is wrong.\"";
		}
		elseif ($status == self::CHECK_ORDER_REJECT_PAYMENT)
		{
			$xmlString .= " message=\"Amount not correct.\"";
		}
		$xmlString .= " />";

		return $xmlString;
	}

	public function sendToYandex($postData)
	{
		$redirectUrl = "";

	 	$cpt = curl_init();
		curl_setopt($cpt, CURLOPT_URL, "https://$this->_server");
		curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($cpt, CURLOPT_HEADER, FALSE);
		curl_setopt($cpt, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 40);
		curl_setopt($cpt, CURLOPT_TIMEOUT, 40);
		curl_setopt($cpt, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cpt, CURLOPT_MAXREDIRS, 5);
		//curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, 1);

		curl_setopt($cpt, CURLOPT_POST, 1);
		curl_setopt($cpt, CURLOPT_POSTFIELDS, $postData);

		$this->curlResult = curl_exec($cpt);
		$redirectUrl = curl_getinfo($cpt, CURLINFO_EFFECTIVE_URL);
		$this->_curlError = curl_error($cpt);
		$this->_curlInfo = curl_getinfo($cpt);

//		var_dump($this->curlResult);
//		var_dump($this->error);
//		var_dump($this->info);

		curl_close($cpt);
		return array("redirectUrl" => $redirectUrl, "curlResult" => $this->curlResult);
	}

	public function getCurlResult()
	{
		return $this->curlResult;
	}

	public function getCurlError()
	{
		return $this->_curlError;
	}

	public function getCurlInfo()
	{
		return $this->_curlInfo;
	}

	public function setDebug($value)
	{
		$this->debug = $value;
	}
}
