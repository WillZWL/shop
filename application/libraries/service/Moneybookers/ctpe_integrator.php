<?php

/*
  wpf-integrator.php version 0.8

  cb@ctpe.net

  post connection method with ctpe.net.

*/

class Ctpe_integrator
{
    const CTPE_TEST_QUERY_SERVER = "test.nextgenpay.com";
    const CTPE_QUERY_SERVER = "";
    const CTPE_QUERY_ACTION_PAGE = "/payment/query";
    const CTPE_TEST_PAYMENT_SERVER = "test.nextgenpay.com";
    const CTPE_PAYMENT_SERVER = "nextgenpay.com";
    const CTPE_TEST_SECURITY_SENDER = "8a8294172cee5427012cefb2969a00e7";
    const CTPE_SECURITY_SENDER = "";
    const CTPE_TEST_TRANSACTION_CHANNEL = "8a8294172cee5427012cefb3be3b00ed";
    const CTPE_TRANSACTION_CHANNEL = "";
    const CTPE_TEST_USER_LOGIN_ID = "8a8294172cee5427012cefb2969c00eb";
    const CTPE_USER_LOGIN_ID = "";
    const CTPE_TEST_USER_PASSWORD = "6KrDGdE7";
    const CTPE_USER_PASSWORD = "";
    const CTPE_TEST_SECURITY_TOKEN = "demomerchant";
    const CTPE_SECURITY_TOKEN = "";
    const CTPE_TEST_TRANSACTION_MODE = "INTEGRATOR_TEST";
    const CTPE_TRANSACTION_MODE = "LIVE";
    const CTPE_ACTION_PAGE = "/frontend/payment.prc";
    const CTPE_QUERY_REQUEST_VERSION = "1.0";

    var $params = array();
    var $server = "";
    var $path = Ctpe_integrator::CTPE_ACTION_PAGE;
    var $error;
    var $resultUrl;
    var $info;

    function Ctpe_integrator($server, $path, $sender, $channel, $userid, $userpwd, $token, $transaction_mode, $transaction_response)
    {
        $this->server = $server;
        $this->path = $path;
        $this->user_agent = "php ctpepost";
        $this->params["request.version"] = "1.0";
        $this->params["security.token"] = $token;
        $this->params["server"] = $server;
        $this->params["path"] = $path;
        $this->params["security.sender"] = $sender;
        $this->params["transaction.channel"] = $channel;
        $this->params["user.login"] = $userid;
        $this->params["user.pwd"] = $userpwd;
        $this->params["transaction.mode"] = $transaction_mode;
        $this->params["transaction.response"] = $transaction_response;
    }

    public function form_xml_query($request_params = array())
    {
        if (isset($request_params['user_id']))
            $userLogin = $request_params['user_id'];
        else
            $userLogin = $this->params["user.login"];
        if (isset($request_params['user_password']))
            $password = $request_params['user_password'];
        else
            $password = $this->params['user.pwd'];

        $strXML = "<?xml version='1.0' ?>\n";
        $strXML .= "<Request version=\"" . Ctpe_integrator::CTPE_QUERY_REQUEST_VERSION . "\">\n";
        $strXML .= "<Header>\n";
        $strXML .= "<Security sender=\"{$this->params['security.sender']}\" />\n";
        $strXML .= "</Header>\n";
        $strXML .= "<Query mode=\"{$this->params['transaction.mode']}\" level=\"CHANNEL\" entity=\"{$this->params['transaction.channel']}\" type=\"STANDARD\">\n";
        $strXML .= "<User login=\"{$userLogin}\" pwd=\"{$password}\" />\n";
        $strXML .= "<Identification>\n";
        $strXML .= "<TransactionID>" . $request_params['transaction_id'] . "</TransactionID>\n";
        $strXML .= "</Identification>\n";
        $strXML .= "</Query>\n";
        $strXML .= "</Request>\n";
        return $strXML;
        /*
                $this->sendToCTPE($this->server, $this->path, $strXML);
                if ($this->resultURL)
                {
                    return $this->resultURL;
                }
                else
                {
                    return false;
                }
        */
    }

    function setServer($server, $path)
    {
        $this->server = $server;
        $this->path = $path;
    }

    function queryToCtpe($postdata)
    {
        $cpt = curl_init();

        $xmlpost = "load=" . urlencode($postdata);
        curl_setopt($cpt, CURLOPT_URL, "https://$this->server$this->path");
        curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($cpt, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 35);
        curl_setopt($cpt, CURLOPT_TIMEOUT, 50);

        //curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, 1);

        curl_setopt($cpt, CURLOPT_POST, 1);
        curl_setopt($cpt, CURLOPT_POSTFIELDS, $xmlpost);

        $this->resultURL = curl_exec($cpt);
        $this->error = curl_error($cpt);
        $this->info = curl_getinfo($cpt);

        curl_close($cpt);
        return $this->resultURL;
    }

    /*
      using HTTP/POST send message to ctpe server
    */

    function setPaymentInformation($payment_amount, $payment_usage, $identification_transactionid, $payment_currency)
    {
        $this->params["presentation.amount"] = $payment_amount;
        $this->params["presentation.usage"] = $payment_usage;
        $this->params["identification.transactionID"] = $identification_transactionid;
        $this->params["presentation.currency"] = $payment_currency;
    }

    function setPaymentCode($payment_code)
    {
        $this->params["payment.code"] = $payment_code;
    }

    function setPaymentMethod($card_type)
    {
        $method = "";
        $subtypes = "";
//set card type
        if (($card_type == "VISADEBIT")
            || ($card_type == "VISAELECTRON")
            || ($card_type == "MAESTRO")
            || ($card_type == "DEBIT")
        ) {
            $method = "DC";
        } else if (($card_type == "MASTER")
            || ($card_type == "VISA")
            || ($card_type == "CREDIT")
        ) {
            $method = "CC";
        }

//set brand
        if ($card_type == "CREDIT")
            $subtypes = "VISA,MASTER";
        else if ($card_type == "DEBIT")
            $subtypes = "VISADEBIT,VISAELECTRON,MAESTRO";
        else if ($card_type == "VISADEBIT")
            $subtypes = "VISADEBIT";
        else if ($card_type == "VISAELECTRON")
            $subtypes = "VISAELECTRON";
        else if ($card_type == "MAESTRO")
            $subtypes = "MAESTRO";
        else if ($card_type == "VISA")
            $subtypes = "VISA";
        else if ($card_type == "MASTER")
            $subtypes = "MASTER";

        if (($subtypes != "") && ($method != "")) {
            if ($method != "") {
                $this->params["FRONTEND.PM.DEFAULT_DISABLE_ALL"] = "true";
                $this->params["FRONTEND.PM.1.METHOD"] = $method;
                $this->params["FRONTEND.PM.1.ENABLED"] = "true";
                $this->params["FRONTEND.PM.1.SUBTYPES"] = $subtypes;
            }
        }
    }

    function setCustomerContact($contact_email, $contact_mobile, $contact_phone, $contact_ip)
    {
        $this->params["contact.email"] = $contact_email;
        $this->params["contact.mobile"] = $contact_mobile;
        $this->params["contact.ip"] = $contact_ip;
        $this->params["contact.phone"] = $contact_phone;
    }

    function setCustomerAddress($address_street, $address_zip, $address_city, $address_state, $address_country)
    {
        $this->params["address.street"] = $address_street;
        $this->params["address.zip"] = $address_zip;
        $this->params["address.city"] = $address_city;
        $this->params["address.state"] = $address_state;
        $this->params["address.country"] = $address_country;
    }

    function setCustomerName($name_salutation, $name_title, $name_give, $name_family, $name_company)
    {
        $this->params["name.salutation"] = $name_salutation;
        $this->params["name.title"] = $name_title;
        $this->params["name.given"] = $name_give;
        $this->params["name.family"] = $name_family;
        $this->params["name.company"] = $name_company;
    }

    function setWPFparams($frontend_enabled, $frontend_popup, $frontend_mode, $frontend_lang, $frontend_response_url, $frontend_banner = null)
    {
//      $this->wpf=$frontend_enabled;
        $this->params["FRONTEND.ENABLED"] = $frontend_enabled;
        $this->params["FRONTEND.POPUP"] = $frontend_popup;
        $this->params["FRONTEND.MODE"] = $frontend_mode;
        $this->params["FRONTEND.LANGUAGE"] = $frontend_lang;
        $this->params["FRONTEND.RESPONSE_URL"] = $frontend_response_url;
        if ($frontend_banner != null) {
            $this->params["FRONTEND.BANNER.1.LINK"] = $frontend_banner;
            $this->params["FRONTEND.BANNER.1.AREA"] = "TOP";
            $this->params["FRONTEND.BANNER.1.HEIGHT"] = 72;
        }
    }

    function setUiCustomization($cssPath, $javascriptPath = null)
    {
        if ($cssPath != null)
            $this->params["FRONTEND.CSS_PATH"] = $cssPath;
        if ($javascriptPath != null)
            $this->params["FRONTEND.JSCRIPT_PATH"] = $javascriptPath;
    }

    function commitPOSTPayment()
    {
        foreach (array_keys($this->params) AS $key) {
            $$key .= $this->params[$key];
            $$key = urlencode($$key);
            $$key .= "&";
            $var = strtoupper($key);
            $value = $$key;
            $result .= "$var=$value";
        }
//      print $result;
        $strPOST = stripslashes($result);
        $this->sendToCtpe($strPOST);

//      print $this->resultURL;
        if ($this->resultURL) {
            return $this->parserResult($this->resultURL);
        } else {
            return false;
        }
    }

    function sendToCtpe($postdata)
    {
//      var_dump($postdata);
        $cpt = curl_init();

        curl_setopt($cpt, CURLOPT_URL, "https://$this->server$this->path");
        curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($cpt, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 35);
        curl_setopt($cpt, CURLOPT_TIMEOUT, 50);

        //curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, 1);

        curl_setopt($cpt, CURLOPT_POST, 1);
        curl_setopt($cpt, CURLOPT_POSTFIELDS, $postdata);

        $this->resultURL = curl_exec($cpt);
        $this->error = curl_error($cpt);
        $this->info = curl_getinfo($cpt);

        curl_close($cpt);
        return $this->resultURL;
    }

    /*
      Parse POST message returned by CTPE server.
    */

    function parserResult($resultURL)
    {
        $r_arr = explode("&", $resultURL);

//      $this->wpf=strtolower($this->wpf);

        foreach ($r_arr AS $buf) {
            $temp = urldecode($buf);
            $temp = split("=", $temp, 2);

            $postatt = $temp[0];
            $postvar = $temp[1];

            $returnvalue[$postatt] = $postvar;
        }
        return ($returnvalue);
        /*
           uncomment the following line for debug output (whole XML)
         */
        //print "<br>$resultXML";
    }

}
