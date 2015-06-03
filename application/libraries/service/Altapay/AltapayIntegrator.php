<?php

class Altapay_integrator
{
	const PAYMENT_SERVER = "valuebasket.pensio.com";
	const PAYMENT_SERVER_DEBUG = "testgateway.pensio.com";
	const GATEWAY_LOGIN = "altapayapi@valuebasket.com";
	const GATEWAY_LOGIN_DEBUG = "oswald@eservicesgroup.com";
    const VERIFY_SECRET_WORD = "MKLOPBVFRTGedcfr";
    const VERIFY_SECRET_WORD_DEBUG = "DUSFVBEERsio";
	const GATEWAY_PASSWORD = "PeN8SYaVHnfj";
	const GATEWAY_PASSWORD_DEBUG = "Wta?xygd4bsb";
	const GATEWAY_TERMINAL = "Valuebasket CC";	// the tail (Currency) will be appended by function below
	const GATEWAY_TERMINAL_DEBUG = "ValueBasket Test Terminal";
	const API_CREATE_PAYMENT = "API/createPaymentRequest";
    const API_QUERY_PAYMENT = "API/payments";

	public $debug;

	private $_server;
	private $_login;
	private $_password;
	private $_terminal;
    private $_sharedSecret;

	private $_curlResult;
	private $_curlError;
	private $_curlInfo;

	public function Altapay_integrator($debug = 0)
	{
		$this->debug = $debug;

		if ($this->debug)
		{
			$this->_server = self::PAYMENT_SERVER_DEBUG;
			$this->_login = self::GATEWAY_LOGIN_DEBUG;
			$this->_password = self::GATEWAY_PASSWORD_DEBUG;
			$this->_terminal = self::GATEWAY_TERMINAL_DEBUG;
            $this->_sharedSecret = self::VERIFY_SECRET_WORD_DEBUG;
		}
		else
		{
			$this->_server = self::PAYMENT_SERVER;
			$this->_login = self::GATEWAY_LOGIN;
			$this->_password = self::GATEWAY_PASSWORD;
//			$this->_terminal = self::GATEWAY_TERMINAL;
            $this->_sharedSecret = self::VERIFY_SECRET_WORD;
		}
	}

	private function _selectTerminal($currency)
	{
		if (!$this->debug)
			$this->_terminal = self::GATEWAY_TERMINAL . " " . $currency;
	}

	public function submitCreatePaymentRequest($data)
	{
		return $this->_connect($data, self::API_CREATE_PAYMENT);
	}

	public function submitCreateQueryPaymentRequest($data)
	{
		return $this->_connect($data, self::API_QUERY_PAYMENT);
	}

	public function _connect($data, $api)
	{
//		error_log($data);
		$ch = curl_init("https://" . $this->_server . "/merchant/" . $api);
		curl_setopt($ch, CURLOPT_USERPWD, $this->_login . ":" . $this->_password);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, @http_build_query($data));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);

		$this->_curlResult = curl_exec($ch);
		$this->_curlError = curl_error($ch);
		$this->_curlInfo = curl_getinfo($ch);
		curl_close($ch);
/*
		var_dump($this->_curlResult);
		var_dump($this->_curlError);
		var_dump($this->_curlInfo);
*/
		return array("error" => $this->_curlError, "info" => $this->_curlInfo, "result" => $this->_curlResult);
	}

    public function form_transaction_query($input_params, $currency = "EUR")
    {
        $request = array();
		$this->_selectTerminal($currency);
        $request["shop_orderid"] = $input_params["so_no"];
        $request["terminal"] = $this->_terminal;
        return $request;
    }

	public function form_payment_request($soObj, $clientObj, $processPaymentUrl)
	{
		$request = array();
		$this->_selectTerminal($soObj->get_currency_id());
		$request["terminal"] = $this->_terminal;
		$request["shop_orderid"] = $soObj->get_so_no();
		$request["amount"] = $soObj->get_amount();
		$request["currency"] = $soObj->get_currency_id();
		$request["language"] = $soObj->get_lang_id();
//        $request["language"] = "en";
		$request["type"] = "paymentAndCapture";

        if ($clientObj)
        {
            $request["customer_info"] = array();
            $request["customer_info"]["billing_city"] = $clientObj->get_city();
            $request["customer_info"]["billing_region"] = null;
            $request["customer_info"]["billing_postal"] = $clientObj->get_postcode();
            $request["customer_info"]["billing_country"] = $clientObj->get_country_id();
            
            $request["customer_info"]["email"] = $clientObj->get_email();
            $request["customer_info"]["customer_phone"] = (($clientObj->get_tel_1())?$clientObj->get_tel_1():"") . (($clientObj->get_tel_2())?$clientObj->get_tel_2():"") . (($clientObj->get_tel_3())?$clientObj->get_tel_3():"");
            $request["customer_info"]["billing_firstname"] = $clientObj->get_forename();
            $request["customer_info"]["billing_lastname"] = $clientObj->get_surname();
            $request["customer_info"]["billing_address"] = str_replace("|" ,  " ", $soObj->get_bill_address());
            
            $delivery_name = explode(" ", $soObj->get_delivery_name());
            $request["customer_info"]["shipping_firstname"] = $delivery_name[0];
            $request["customer_info"]["shipping_lastname"] = ((sizeof($delivery_name) > 1)?$delivery_name[1]:"");
            $request["customer_info"]["shipping_address"] = str_replace("|" ,  " ", $soObj->get_delivery_address());
            $request["customer_info"]["shipping_city"] = $soObj->get_delivery_city();
            $request["customer_info"]["shipping_region"] = null;
            $request["customer_info"]["shipping_postal"] = $soObj->get_delivery_postcode();
            $request["customer_info"]["shipping_country"] = $soObj->get_delivery_country_id();
        }

        $httpUrl = str_replace("https://", "http://", base_url());
        $request["config"] = array();
		$request["config"]["callback_ok"] = $processPaymentUrl;
		$request["config"]["callback_fail"] = $processPaymentUrl;
        $request["config"]["callback_redirect"] = $httpUrl . "checkout_redirect_method/payment_redirect_form";
        $request["config"]["callback_form"] = $httpUrl . "checkout_redirect_method/payment_form";
//no open state for credit card
        $request["config"]["callback_open"] = "";
//notification is only for chargeback
        $request["config"]["callback_notification"] = "";

		return $request;
	}

    public function calculateChecksum(Array $inputData)
    {
       $inputData['secret'] = $this->_sharedSecret;       
       ksort($inputData);
       $data = array();
       foreach($inputData as $name => $value)
       {
          $data[] = $name . "=" . $value;
       }
       return md5(join(',', $data));
    }
}
