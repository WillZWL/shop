<?php
namespace ESG\Panther\Service\GlobalCollect;

class GlobalCollectRequest
{
//    const MERCHANTID = "8364";
//    const MOTO_MERCHANTID = "8365";
    const OURSERVERIP = "78.136.35.99";
    const DEBUG_OURSERVERIP = "219.76.190.140";
    const PAYMENT_SERVER = "ps.gcsip.com/wdl/wdl";
    const DEBUG_PAYMENT_SERVER = "ps.gcsip.nl/wdl/wdl";

    public $_acct = ["GB" => "510"
                    , "ES" => "509"
                    , "FR" => "511"];
    public $debug;
    public $_merchantId;
    public $_ourServerIp;
    public $_server;

    private $curlResult;
    private $_curlError;
    private $_curlInfo;

    public function __construct($debug = 0)
    {
        $this->debug = $debug;

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

//      $this->_ourServerIp = self::OURSERVERIP;
//error_log($this->_ourServerIp);
//error_log($this->_server);
    }
/*
    public function useMotoMerchantId()
    {
        $this->_merchantId = self::MOTO_MERCHANTID;
    }
*/
    public function setMerchantId($countryId)
    {
        if ($this->debug)
            $this->_merchantId = $this->_acct["ES"];
        else
            $this->_merchantId = $this->_acct[$countryId];
    }

    public function submitRequest($data)
    {
        return $this->_connect($data);
    }

    public function _connect($xml_data)
    {
        $header[] = "Content-type: text/xml";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, "https://" . $this->_server);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);

        $this->curlResult = curl_exec($ch);
        $this->_curlError = curl_error($ch);
        $this->_curlInfo = curl_getinfo($ch);
        curl_close($ch);
        return ["error" => $this->_curlError, "info" => $this->_curlInfo, "result" => $this->curlResult];
    }

    public function formPaymentXml($soObj, $soiList, $clientObj, $paymentCardId, $responseUrl)
    {
        $billStreet = $soObj->getBillAddress();
        $delStreet = $soObj->getDeliveryAddress();
        $billAddAddress = $delAddAddress = "";

        $this->checkSplitAddress($billStreet, $billAddAddress);
        $this->checkSplitAddress($delStreet, $delAddAddress);

        $billStreet = str_replace("||", " ", $billStreet);
        $delStreet = str_replace("||", " ", $delStreet);

        $billFirstname = $soObj->getBillName();
        $delFirstname = $soObj->getDeliveryName();
        $billSurname = $billPrefixsurname = $delSurname = $delPrefixsurname = "";

        $this->checkSplitName($billFirstname, $billPrefixsurname, $billSurname);
        $this->checkSplitName($delFirstname, $delPrefixsurname, $delSurname);

        if (defined("LANG_ID")) {
            $gcLang = LANG_ID;
        } else {
            $gcLang = "en";
        }

        if ($soObj->getBillCompany()) {
            $companyName = substr($soObj->getBillCompany(), 0, 39);
        } else {
            $companyName = "";
        }

        $phone = trim($clientObj->getTel1()." ".$clientObj->getTel2()." ".$clientObj->getTel3());
        if (strlen($phone) > 20) {
            $phone = $clientObj->getTel3();
            if (strlen($phone) > 20) {
                $phone = substr($phone, 0, 20);
            }
        }

$xml  = "<XML>
    <REQUEST>
        <ACTION>INSERT_ORDERWITHPAYMENT</ACTION>
        <META>
            <IPADDRESS>" . $this->_ourServerIp . "</IPADDRESS>
            <MERCHANTID>" . $this->_merchantId . "</MERCHANTID>
            <VERSION>1.0</VERSION>
        </META>
        <PARAMS>
            <ORDER>
                <ORDERID>" . $soObj->getSoNo() . "</ORDERID>
                <AMOUNT>" . ($soObj->getAmount() * 100) . "</AMOUNT>
                <CURRENCYCODE>" . $soObj->getCurrencyId() . "</CURRENCYCODE>
                <CUSTOMERID>" . $soObj->getClientId() . "</CUSTOMERID>
                <IPADDRESSCUSTOMER>" . $this->_ourServerIp . "</IPADDRESSCUSTOMER>
                <FIRSTNAME>" . xmlspecialchars($billFirstname) . "</FIRSTNAME>
                <PREFIXSURNAME>" . xmlspecialchars($billPrefixsurname) . "</PREFIXSURNAME>
                <SURNAME>" . xmlspecialchars($billSurname) . "</SURNAME>
                <STREET>" . xmlspecialchars($billStreet) . "</STREET>
                <ADDITIONALADDRESSINFO>" . xmlspecialchars($billAddAddress) . "</ADDITIONALADDRESSINFO>
                <ZIP>" . xmlspecialchars($soObj->getBillPostcode()) . "</ZIP>
                <CITY>" . xmlspecialchars($soObj->getBillCity()) . "</CITY>
                <STATE>" . xmlspecialchars($soObj->getBillState()) . "</STATE>
                <COUNTRYCODE>" . $soObj->getBillCountryId() . "</COUNTRYCODE>
                <EMAIL>" . xmlspecialchars($clientObj->getEmail()) . "</EMAIL>
                <PHONENUMBER>" . xmlspecialchars($phone) . "</PHONENUMBER>
                <SHIPPINGFIRSTNAME>" . xmlspecialchars($delFirstname) . "</SHIPPINGFIRSTNAME>
                <SHIPPINGPREFIXSURNAME>" . xmlspecialchars($delPrefixsurname) . "</SHIPPINGPREFIXSURNAME>
                <SHIPPINGSURNAME>" . xmlspecialchars($delSurname) . "</SHIPPINGSURNAME>
                <SHIPPINGSTREET>" . xmlspecialchars($delStreet) . "</SHIPPINGSTREET>
                <SHIPPINGADDITIONALADDRESSINFO>" . xmlspecialchars($delAddAddress) . "</SHIPPINGADDITIONALADDRESSINFO>
                <SHIPPINGZIP>" . xmlspecialchars($soObj->getDeliveryPostcode()) . "</SHIPPINGZIP>
                <SHIPPINGCITY>" . xmlspecialchars($soObj->getDeliveryCity()) . "</SHIPPINGCITY>
                <SHIPPINGSTATE>".xmlspecialchars($soObj->getDeliveryState())."</SHIPPINGSTATE>
                <SHIPPINGCOUNTRYCODE>" . $soObj->getDeliveryCountryId() ."</SHIPPINGCOUNTRYCODE>
                <COMPANYNAME>" . xmlspecialchars($companyName) . "</COMPANYNAME>
                <LANGUAGECODE>" . $gcLang . "</LANGUAGECODE>
                <MERCHANTREFERENCE>" . $soObj->getClientId() . "-" . $soObj->getSoNo() ."</MERCHANTREFERENCE>
            </ORDER>
            <ORDERLINES>
            ";

            foreach ($soiList as $soi) {
            $xml .= "
                <ORDERLINE>
                    <LINENUMBER>" . $soi->getLineNo() ."</LINENUMBER>
                    <LINEAMOUNT>" . ($soi->getAmount()*100) . "</LINEAMOUNT>
                </ORDERLINE>
               ";
            }

            $xml .= "
            </ORDERLINES>
            <PAYMENT>
                <CVVINDICATOR>1</CVVINDICATOR>
                <RETURNURL>" . xmlspecialchars($responseUrl) . "</RETURNURL>
                <PAYMENTPRODUCTID>" . $paymentCardId ."</PAYMENTPRODUCTID>
                <AMOUNT>" . ($soObj->getAmount()*100) . "</AMOUNT>
                <CURRENCYCODE>" . $soObj->getCurrencyId() ."</CURRENCYCODE>
                <COUNTRYCODE>" . $soObj->getBillCountryId() . "</COUNTRYCODE>
                <LANGUAGECODE>" . $gcLang . "</LANGUAGECODE>
                <HOSTEDINDICATOR>1</HOSTEDINDICATOR>
            </PAYMENT>
        </PARAMS>
    </REQUEST>
</XML>
";

        return $xml;
/*
        $xml = new \SimpleXMLElement('<XML/>');

        $request = $xml->addChild('REQUEST');
        $request->addChild('ACTION', "INSERT_ORDERWITHPAYMENT");

        $meta = $request->addChild('META');
        $meta->addChild('IPADDRESS', $this->_ourServerIp);
        $meta->addChild('MERCHANTID', $this->_merchantId);
        $meta->addChild('VERSION', '1.0');

        $params = $request->addChild('PARAMS');

        $order = $params->addChild('ORDER');
        $order->addChild('ORDERID', $soObj->getSoNo());
        $order->addChild('AMOUNT', $soObj->getAmount() * 100);
        $order->addChild('CURRENCYCODE', $soObj->getCurrencyId());
        $order->addChild('CUSTOMERID', $soObj->getClientId());
        $order->addChild('IPADDRESSCUSTOMER', $_SERVER["REMOTE_ADDR"]);
        $order->addChild('FIRSTNAME', xmlspecialchars($billFirstname));
        $order->addChild('PREFIXSURNAME', xmlspecialchars($billPrefixsurname));
        $order->addChild('SURNAME', xmlspecialchars($billSurname));
        $order->addChild('STREET', xmlspecialchars($billStreet));
        $order->addChild('ADDITIONALADDRESSINFO', xmlspecialchars($billAddAddress));
        $order->addChild('ZIP', xmlspecialchars($soObj->getBillPostcode()));
        $order->addChild('CITY', xmlspecialchars($soObj->getBillCity()));
        $order->addChild('STATE', xmlspecialchars($soObj->getBillState()));
        $order->addChild('COUNTRYCODE', $soObj->getBillCountryId());
        $order->addChild('EMAIL', xmlspecialchars($clientObj->getEmail()));
        $order->addChild('PHONENUMBER', xmlspecialchars($phone));
        $order->addChild('SHIPPINGFIRSTNAME', xmlspecialchars($delFirstname));
        $order->addChild('SHIPPINGPREFIXSURNAME', xmlspecialchars($delPrefixsurname));
        $order->addChild('SHIPPINGSURNAME', xmlspecialchars($delSurname));
        $order->addChild('SHIPPINGSTREET', xmlspecialchars($delStreet));
        $order->addChild('SHIPPINGADDITIONALADDRESSINFO', xmlspecialchars($delAddAddress));
        $order->addChild('SHIPPINGZIP', xmlspecialchars($soObj->getDeliveryPostcode()));
        $order->addChild('SHIPPINGCITY', xmlspecialchars($soObj->getDeliveryCity()));
        $order->addChild('SHIPPINGSTATE', xmlspecialchars($soObj->getDeliveryState()));
        $order->addChild('SHIPPINGCOUNTRYCODE', $soObj->getDeliveryCountryId());
        $order->addChild('COMPANYNAME', xmlspecialchars($companyName));
        $order->addChild('LANGUAGECODE', $gcLang);
        $order->addChild('MERCHANTREFERENCE', $soObj->getClientId(). '-'. $soObj->getSoNo());

        $orderlines = $params->addChild('ORDERLINES');
        foreach ($soiList as $soi) {
            $orderline = $orderlines->addChild('ORDERLINE');
            $orderline->addChild('LINENUMBER', $soi->getLineNo());
            $orderline->addChild('LINEAMOUNT', $soi->getAmount()*100);
        }

        $payment = $params->addChild('PAYMENT');
        $payment->addChild('CVVINDICATOR', 1);
        $payment->addChild('RETURNURL', xmlspecialchars($responseUrl));
        $payment->addChild('PAYMENTPRODUCTID', $paymentCardId);
        $payment->addChild('AMOUNT', $soObj->getAmount()*100);
        $payment->addChild('CURRENCYCODE', $soObj->getCurrencyId());
        $payment->addChild('COUNTRYCODE', $soObj->getBillCountryId());
        $payment->addChild('LANGUAGECODE', $gcLang);
        $payment->addChild('HOSTEDINDICATOR', 1);

        return $xml->asXML();
*/
    }

    public function formSetPaymentXml($soNo, $cardId)
    {
        $xml =
"<XML>
	<REQUEST>
		<ACTION>SET_PAYMENT</ACTION>
		<META>
			<MERCHANTID>{$this->_merchantId}</MERCHANTID>
			<IPADDRESS>{$this->_ourServerIp}</IPADDRESS>
			<VERSION>1.0</VERSION>
		</META>
		<PARAMS>
			<PAYMENT>
				<ORDERID>" . $soNo . "</ORDERID>
				<PAYMENTPRODUCTID>" . $cardId . "</PAYMENTPRODUCTID>
				<EFFORTID>1</EFFORTID>
			</PAYMENT>
		</PARAMS>
	</REQUEST>
</XML>";
        return $xml;
    }

    public function formOrderStatusXml($soNo)
    {
        $xml =
"<XML>
    <REQUEST>
        <ACTION>GET_ORDERSTATUS</ACTION>
        <META>
            <MERCHANTID>" . $this->_merchantId . "</MERCHANTID>
            <IPADDRESS>" . $this->_ourServerIp . "</IPADDRESS>
            <VERSION>2.0</VERSION>
        </META>
        <PARAMS>
            <ORDER>
                <ORDERID>" . $soNo . "</ORDERID>
            </ORDER>
        </PARAMS>
    </REQUEST>
</XML>";

        return $xml;

        // $xml = new \SimpleXMLElement('<XML/>');

        // $request = $xml->addChild('REQUEST');
        // $request->addChild('ACTION', "GET_ORDERSTATUS");

        // $meta = $request->addChild('META');
        // $meta->addChild('MERCHANTID', $this->_merchantId);
        // $meta->addChild('IPADDRESS', $this->_ourServerIp);
        // $meta->addChild('VERSION', '2.0');

        // $params = $request->addChild('PARAMS');

        // $order = $params->addChild('ORDER');
        // $order->addChild('ORDERID', $soNo);

        // return $xml->asXML();
    }

    public function checkSplitAddress(&$address, &$addAddress)
    {
        if (strlen($address) > 50) {
            $arAddress = @explode("|", $address);
            $arLen[0] = strlen($arAddress[0]);
            $arLen[1] = strlen($arAddress[1]);
            $arLen[2] = strlen($arAddress[2]);
            if (($arLen[0]+$arLen[1])<50) {
                $address = $arAddress[0]." ".$arAddress[1];
                if ($arLen[2]<51) {
                    $addAddress = $arAddress[2];
                }
            } elseif ($arLen[0] < 51) {
                $address = $arAddress[0];
                if (($arLen[1]+$arLen[2])<50) {
                    $addAddress = $arAddress[1]." ".$arAddress[2];
                } elseif($arLen[1] <51) {
                    $addAddress = $arAddress[1];
                }
            } else {
                $address = substr($address, 0, 50);
            }
        }
    }

    public function checkSplitName(&$firstname, &$prefixsurname, &$surname)
    {
        $arFirstname = explode(" ", $firstname);

        if (($name_count = count($arFirstname)) > 1) {
            switch ($name_count) {
                case 3:
                    $firstname = $arFirstname[0];
                    $prefixsurname = substr($arFirstname[1], 0, 15);
                    $surname = substr($arFirstname[2], 0, 35);
                    break;
                case 2:
                    $firstname = $arFirstname[0];
                    $surname = substr($arFirstname[1], 0, 35);
                    break;
                default:
                    $firstname = $arFirstname[0];
                    $prefixsurname = substr($arFirstname[1], 0, 15);
                    array_shift($arFirstname);
                    array_shift($arFirstname);
                    $surname = substr(@implode(" ", $arFirstname), 0, 35);
                    break;

            }
        }

        if (strlen($firstname) > 15) {
            $firstname = substr($firstname, 0, 15);
        }
    }
}
