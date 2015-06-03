<?php

class Global_collect_integrator
{
	const MERCHANTID = "8364";
	const OURSERVERIP = "94.236.11.179";
	const DEBUG_OURSERVERIP = "219.76.178.234";
	const PAYMENT_SERVER = "ps.gcsip.com/wdl/wdl";
	const DEBUG_PAYMENT_SERVER = "ps.gcsip.nl/wdl/wdl";
	
	public $debug;
	public $_merchantId;
	public $_ourServerIp;
	public $_server;

	private $curlResult;
	private $_curlError;
	private $_curlInfo;

	public function Global_collect_integrator($debug = 0)
	{
		$this->debug = $debug;
		$this->_merchantId = self::MERCHANTID;

		if ($this->debug)
		{
			$this->_ourServerIp = self::DEBUG_OURSERVERIP;
			$this->_server = self::DEBUG_PAYMENT_SERVER;
		}
		else
		{
			$this->_ourServerIp = self::OURSERVERIP;
			$this->_server = self::PAYMENT_SERVER;
		}

//		$this->_ourServerIp = self::OURSERVERIP;
//error_log($this->_ourServerIp);
//error_log($this->_server);

	}
/*
	public function submitPaymentForm($data)
	{
		return $this->_connect($data);
	}
*/
	public function submitRequest($data)
	{
		return $this->_connect($data);
	}

	public function _connect($data)
	{
//		error_log($data);
		$ch = curl_init("https://" . $this->_server);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
//		curl_setopt($ch, CURLOPT_CAINFO, "/Secure_trust.cer");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);

		$this->curlResult = curl_exec($ch);
		$this->_curlError = curl_error($ch);
		$this->_curlInfo = curl_getinfo($ch);
		curl_close($ch);
/*
		var_dump($this->curlResult);
		var_dump($this->_curlError);
		var_dump($this->_curlInfo);
*/
		return array("error" => $this->_curlError, "info" => $this->_curlInfo, "result" => $this->curlResult);
	}

	public function form_payment_xml($soObj, $soiList, $clientObj, $card_type, $responseUrl)
	{
		$bill_street = $soObj->get_bill_address();
		$del_street = $soObj->get_delivery_address();
		$bill_add_address = $del_add_address = "";

		$this->check_split_address($bill_street, $bill_add_address);
		$this->check_split_address($del_street, $del_add_address);

		$bill_street = str_replace("|", " ", $bill_street);
		$del_street = str_replace("|", " ", $del_street);

		$bill_firstname = $soObj->get_bill_name();
		$del_firstname = $soObj->get_delivery_name();
		$bill_surname = $bill_prefixsurname = $del_surname = $del_prefixsurname = "";

		$this->check_split_name($bill_firstname, $bill_prefixsurname, $bill_surname);
		$this->check_split_name($del_firstname, $del_prefixsurname, $del_surname);

		switch (get_lang_id())
		{
			case "pt-br":
				$gc_lang = "pt";
				break;
			case "zh-tw":
				$gc_lang = "tc";
				break;
			case "zh-cn":
				$gc_lang = "sc";
				break;
			default:
				$gc_lang = get_lang_id();
		}
$xml  =
"<XML>
	<REQUEST>
		<ACTION>INSERT_ORDERWITHPAYMENT</ACTION>
		<META>
			<IPADDRESS>{$this->_ourServerIp}</IPADDRESS>
			<MERCHANTID>{$this->_merchantId}</MERCHANTID>
			<VERSION>1.0</VERSION>
		</META>
		<PARAMS>
			<ORDER>
				<ORDERID>" . $soObj->get_so_no() . "</ORDERID>
				<AMOUNT>" . ($soObj->get_amount() * 100) . "</AMOUNT>
				<CURRENCYCODE>{$soObj->get_currency_id()}</CURRENCYCODE>
				<CUSTOMERID>{$soObj->get_client_id()}</CUSTOMERID>
				<IPADDRESSCUSTOMER>{$_SERVER["REMOTE_ADDR"]}</IPADDRESSCUSTOMER>
				<FIRSTNAME>".xmlspecialchars($bill_firstname)."</FIRSTNAME>
				<PREFIXSURNAME>".xmlspecialchars($bill_prefixsurname)."</PREFIXSURNAME>
				<SURNAME>".xmlspecialchars($bill_surname)."</SURNAME>
				<STREET>".xmlspecialchars($bill_street)."</STREET>
				<ADDITIONALADDRESSINFO>".xmlspecialchars($bill_add_address)."</ADDITIONALADDRESSINFO>
				<ZIP>".xmlspecialchars($soObj->get_bill_postcode())."</ZIP>
				<CITY>".xmlspecialchars($soObj->get_bill_city())."</CITY>
				<STATE>".xmlspecialchars($soObj->get_bill_state())."</STATE>
				<COUNTRYCODE>{$soObj->get_bill_country_id()}</COUNTRYCODE>
				<EMAIL>".xmlspecialchars($clientObj->get_email())."</EMAIL>
				<PHONENUMBER>".xmlspecialchars(trim($clientObj->get_tel_1()." ".$clientObj->get_tel_2()." ".$clientObj->get_tel_3()))."</PHONENUMBER>
				<SHIPPINGFIRSTNAME>".xmlspecialchars($del_firstname)."</SHIPPINGFIRSTNAME>
				<SHIPPINGPREFIXSURNAME>".xmlspecialchars($del_prefixsurname)."</SHIPPINGPREFIXSURNAME>
				<SHIPPINGSURNAME>".xmlspecialchars($del_surname)."</SHIPPINGSURNAME>
				<SHIPPINGSTREET>".xmlspecialchars($del_street)."</SHIPPINGSTREET>
				<SHIPPINGADDITIONALADDRESSINFO>".xmlspecialchars($del_add_address)."</SHIPPINGADDITIONALADDRESSINFO>
				<SHIPPINGZIP>".xmlspecialchars($soObj->get_delivery_postcode())."</SHIPPINGZIP>
				<SHIPPINGCITY>".xmlspecialchars($soObj->get_delivery_city())."</SHIPPINGCITY>
				<SHIPPINGSTATE>".xmlspecialchars($soObj->get_delivery_state())."</SHIPPINGSTATE>
				<SHIPPINGCOUNTRYCODE>{$soObj->get_delivery_country_id()}</SHIPPINGCOUNTRYCODE>
				<COMPANYNAME>".xmlspecialchars($soObj->get_bill_company())."</COMPANYNAME>
				<LANGUAGECODE>" . $gc_lang . "</LANGUAGECODE>
				<MERCHANTREFERENCE>{$soObj->get_client_id()}-{$soObj->get_so_no()}</MERCHANTREFERENCE>
			</ORDER>
			<ORDERLINES>
";

		foreach ($soiList as $soi)
		{
$xml .=
"				<ORDERLINE>
					<LINENUMBER>{$soi->get_line_no()}</LINENUMBER>
					<LINEAMOUNT>".($soi->get_amount()*100)."</LINEAMOUNT>
				</ORDERLINE>
";
		}

$xml .=
"			</ORDERLINES>
			<PAYMENT>
				<CVVINDICATOR>1</CVVINDICATOR>
				<RETURNURL>" . xmlspecialchars($responseUrl) . "</RETURNURL>
				<PAYMENTPRODUCTID>{$card_type}</PAYMENTPRODUCTID>
				<AMOUNT>".($soObj->get_amount()*100)."</AMOUNT>
				<CURRENCYCODE>{$soObj->get_currency_id()}</CURRENCYCODE>
				<COUNTRYCODE>{$soObj->get_bill_country_id()}</COUNTRYCODE>
				<LANGUAGECODE>{$gc_lang}</LANGUAGECODE>
				<HOSTEDINDICATOR>1</HOSTEDINDICATOR>
			</PAYMENT>
		</PARAMS>
	</REQUEST>
</XML>";

		return $xml;
	}

	public function form_order_status_xml($so_no)
	{
$xml =
"<XML>
	<REQUEST>
		<ACTION>GET_ORDERSTATUS</ACTION>
		<META>
			<MERCHANTID>{$this->_merchantId}</MERCHANTID>
			<IPADDRESS>{$this->_ourServerIp}</IPADDRESS>
			<VERSION>2.0</VERSION>
		</META>
		<PARAMS>
			<ORDER>
				<ORDERID>" . $so_no . "</ORDERID>
			</ORDER>
		</PARAMS>
	</REQUEST>
</XML>";
		return $xml;
	}

	public function check_split_address(&$address, &$add_address)
	{
		if (strlen($address) > 50)
		{
			$ar_address = @explode("|", $address);
			$ar_len[0] = strlen($ar_address[0]);
			$ar_len[1] = strlen($ar_address[1]);
			$ar_len[2] = strlen($ar_address[2]);
			if (($ar_len[0]+$ar_len[1])<50)
			{
				$address = $ar_address[0]." ".$ar_address[1];
				if ($ar_len[2]<51)
				{
					$add_address = $ar_address[2];
				}
			}
			elseif ($ar_len[0] < 51)
			{
				$address = $ar_address[0];
				if (($ar_len[1]+$ar_len[2])<50)
				{
					$add_address = $ar_address[1]." ".$ar_address[2];
				}
				elseif($ar_len[1] <51)
				{
					$add_address = $ar_address[1];
				}
			}
			else
			{
				$address = substr($address, 0, 50);
			}
		}
	}

	public function check_split_name(&$firstname, &$prefixsurname, &$surname)
	{
		$ar_firstname = explode(" ", $firstname);

		if (($name_count = count($ar_firstname)) > 1)
		{
			switch ($name_count)
			{
				case 3:
					$firstname = $ar_firstname[0];
					$prefixsurname = substr($ar_firstname[1], 0, 15);
					$surname = substr($ar_firstname[2], 0, 35);
					break;
				case 2:
					$firstname = $ar_firstname[0];
					$surname = substr($ar_firstname[1], 0, 35);
					break;
				default:
					$firstname = $ar_firstname[0];
					$prefixsurname = substr($ar_firstname[1], 0, 15);
					array_shift($ar_firstname);
					array_shift($ar_firstname);
					$surname = substr(@implode(" ", $ar_firstname), 0, 35);
					break;

			}
		}

		if (strlen($firstname) > 15)
		{
			$firstname = substr($firstname, 0, 15);
		}
	}
}
