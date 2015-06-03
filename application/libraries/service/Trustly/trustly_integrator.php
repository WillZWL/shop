<?php

class Trustly_integrator
{
	const TRUSTLY_PAYMENT_TEST_SERVER = "test.trustly.com/api/1";
	const TRUSTLY_PAYMENT_SERVER = "trustly.com/api/1";
	const TRUSTLY_PAYMENT_TEST_USERNAME = "valuebasket";
	const TRUSTLY_PAYMENT_USERNAME = "valuebasket";
	const TRUSTLY_PAYMENT_TEST_PASSWORD = "change_this_xYH5fxUcnL";
	const TRUSTLY_PAYMENT_PASSWORD = "valueTrustly1!";
	const TRUSTLY_PAYMENT_TEST_PRIVATE_KEY = "private_valuebasket_test.pem";
	const TRUSTLY_PAYMENT_PRIVATE_KEY = "private_valuebasket_live.pem";
	const TRUSTLY_PAYMENT_TEST_PUBLIC_KEY = "trustly_public_key_test.pem";
	const TRUSTLY_PAYMENT_PUBLIC_KEY = "trustly_public_key.pem";
	const KEY_STORE_PATH = "/var/www/html/valuebasket_key/";

	public $debug = 0;
	private $_username;
	private $_password;
	private $_server;
	private $_vb_private_key;
	private $_trustly_public_key;

	public function Trustly_integrator($debug = 0)
	{
		$this->debug = $debug;
		if ($this->debug)
		{
			$this->_username = self::TRUSTLY_PAYMENT_TEST_USERNAME;
			$this->_password = self::TRUSTLY_PAYMENT_TEST_PASSWORD;
			$this->_server = self::TRUSTLY_PAYMENT_TEST_SERVER;
			$this->_vb_private_key = self::TRUSTLY_PAYMENT_TEST_PRIVATE_KEY;
			$this->_trustly_public_key = self::TRUSTLY_PAYMENT_TEST_PUBLIC_KEY;
		}
		else
		{
			$this->_username = self::TRUSTLY_PAYMENT_USERNAME;
			$this->_password = self::TRUSTLY_PAYMENT_PASSWORD;
			$this->_server = self::TRUSTLY_PAYMENT_SERVER;
			$this->_vb_private_key = self::TRUSTLY_PAYMENT_PRIVATE_KEY;
			$this->_trustly_public_key = self::TRUSTLY_PAYMENT_PUBLIC_KEY;
		}
	}

	public function form_refund_array($params)
	{
		$method = "Refund";
		$uuid = $this->generate_uuid($params["orderId"], 'f');
	    $dataRequest = array (
            "Username" => $this->_username,
            "Password" => $this->_password,
            "OrderID" => $params["trustly_order_id"],
            "Amount" => $params["refundAmount"],
            "Currency" => $params["currency"],
            "Attributes" => null
        );
		return $this->_get_request_json($method, $uuid, $dataRequest);
	}

	public function form_payment_array($params)
	{
		$method = "Deposit";

		$uuid = $this->generate_uuid($params["orderId"]);
	    $dataRequest = array (
            "Username" => $this->_username,
            "Password" => $this->_password,
            "NotificationURL" => $params["notificationUrl"],
            "EndUserID" => $params["clientId"],
            "MessageID" => $params["orderId"],
            "Attributes" => array (
                "Locale" => $params["language"] . "_" . $params["countryId"],
                "Amount" => $params["totalAmount"],
                "Currency" => $params["currency"],
				"Country" => $params["countryId"],
                "IP" => $_SERVER["REMOTE_ADDR"],
                "SuccessURL" => $params["successUrl"],
				"FailURL" => $params["failUrl"],
                "URLTarget" => "_top",
                "FirstName" => $params["firstName"],
                "LastName" => $params["lastName"],
                "MobilePhone" => $params["tel"]
            )
        );

		return $this->_get_request_json($method, $uuid, $dataRequest);
/*
		$signature = $this->getSignature($method, $params["uuid"], $dataRequest, $this->_vb_private_key);

		if ($signature)
		{
			$trustlyRequest = array (
				"method" => $method,
				"params" => array (
					"Signature" => $signature,
					"UUID" => $params["uuid"],
					"Data" => $dataRequest,
				),
				"version" => "1.1",
			);
			return json_encode($trustlyRequest);
		}
		else
		{
			return false;
		}
*/
	}

	public function get_submit_result($server_result)
	{
		return json_decode($server_result);
	}

	public function send_data_to_trustly($data, &$server_result, &$server_error, &$server_info)
	{
        $ch = curl_init("https://" . $this->_server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json", "Content-Length: " . strlen($data)));

        $server_result = curl_exec($ch);
		$server_error = curl_error($ch);
		$server_info = curl_getinfo($ch);
        curl_close($ch);

		//return $server_result;
        return json_decode($server_result, true);
	}

/**************************
**	The type parameter will be
**	d = Deposit
**	f = Refund
***************************/
	public function generate_uuid($so_no, $type = 'd')
	{
		$random_uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),

			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,

			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
		$so_no_len = strlen($so_no);
		return substr($random_uuid, 0, (strlen($random_uuid) - $so_no_len - 1)) . $type . $so_no;
	}

    public function getSignature($method, $uuid, $data, $key) 
    {
        if (!$merchant_key = openssl_get_privatekey("file://" . self::KEY_STORE_PATH . $key))
        {
            return false;
        }
        
        $plaintext = $method . $uuid . $this->_serialize_data($data);
        openssl_sign($plaintext, $signature, $merchant_key);
        return base64_encode($signature);
    }

    public function verifySignature($method, $uuid, $data, $signature_from_trustly) 
    {
        if (!$trustly_public_key = openssl_get_publickey("file://" . self::KEY_STORE_PATH . $this->_trustly_public_key))
		{
			error_log(__METHOD__ . __LINE__);
			return false;
		}

        $plaintext = $method . $uuid . $this->_serialize_data($data);
        return openssl_verify($plaintext, base64_decode($signature_from_trustly), $trustly_public_key);
    }

	public function send_notification_response($method, $uuid, $status = "OK")
	{
	    $data = array (
	        "status" => $status,
	    );

		$signature = $this->getSignature($method, $uuid, $data, $this->_vb_private_key);

	    $trustlyRequest = array (
	        "result" => array (
	            "signature" => $signature,
	            "uuid" => $uuid,
	            "data" => $data,
	            "method" => $method,
	        ),
	        "version" => "1.1",
	    );

		$response = json_encode($trustlyRequest);
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-type: application/json');
		echo $response;
		return $response;
	}

    private function _serialize_data($object) 
    {
        $serialized = '';
        if(is_array($object)) 
        {
            ksort($object);
            foreach($object as $key => $value) 
            {
                if(is_numeric($key)) 
                {
                    $serialized .= $this->_serialize_data($value);
                }
				else
				{
                    $serialized .= $key . $this->_serialize_data($value);
                }
            }
        }
		else
			return $object;
        return $serialized;
    }
	
	public function setServer($server)
	{
		$this->_server = $server;
	}
	
	private function _get_request_json($method, $uuid, $dataRequest)
	{
		$signature = $this->getSignature($method, $uuid, $dataRequest, $this->_vb_private_key);

		if ($signature)
		{
			$trustlyRequest = array (
				"method" => $method,
				"params" => array (
					"Signature" => $signature,
					"UUID" => $uuid,
					"Data" => $dataRequest,
				),
				"version" => "1.1",
			);
			return json_encode($trustlyRequest);
		}
		else
		{
			return false;
		}
	}
}
