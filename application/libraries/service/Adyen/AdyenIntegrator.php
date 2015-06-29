<?php

class Adyen_integrator
{
    const PAYMENT_SERVER = "live.adyen.com/";
    const PAYMENT_SERVER_DEBUG = "test.adyen.com/";

    const HMAC_KEY = "L0eDu89#";
    const HMAC_KEY_DEBUG = "0Xy8yRt#";
    const API_CREATE_PAYMENT = "hpp/details.shtml";
    const API_CREATE_PAYMENT_NO_BRANDCODE = "hpp/pay.shtml";


    public $debug;

    private $_server;
    private $_login;
    private $_password;
    // private $_terminal;
    private $_sharedSecret;

    private $_curlResult;
    private $_curlError;
    private $_curlInfo;

    public function Adyen_integrator($debug = 0)
    {
        $debug = 1;
        $this->debug = $debug;

        if(strpos($_SERVER["HTTP_HOST"], "dev") === FALSE)
        {
            $this->_server = self::PAYMENT_SERVER;
            $this->_hmac_key = self::HMAC_KEY;

        }
        else
        {
            $this->_server = self::PAYMENT_SERVER_DEBUG;
            $this->_hmac_key = self::HMAC_KEY_DEBUG;
        }
    }

    public function submitCreatePaymentRequest($data)
    {
        if($data["brandCode"] != "")
            return $this->_connect($data, self::API_CREATE_PAYMENT);
        else
            return $this->_connect($data, self::API_CREATE_PAYMENT_NO_BRANDCODE);   # this case should not be happening
    }

    public function submitCreateQueryPaymentRequest($data)
    {
        return TRUE;
        // return $this->_connect($data, self::API_QUERY_PAYMENT);
    }

    public function _connect($data, $api)
    {
        $query_string = json_encode($data);
        $ret = array();
        $ret["result"] = "https://" . $this->_server . $api . "?{$query_string}";
        if(empty($data))
        {
            $ret["error"] = "Empty data in calling {$ret["result"]}";
        }
        // $query = http_build_query($data);
        return $ret;
    }

    public function form_payment_request($soObj, $clientObj, $card_id, $card_code, $processPaymentUrl)
    {
        // this is mainly adapted from https://github.com/adyenpayments/php/blob/master/1.HPP/create-payment-on-hpp-advanced.php#L233

        // By providing the brandCode and issuerId the HPP will redirect the shopper
        // directly to the redirect payment method. Please note: the form should be posted
        // to https://test.adyen.com/hpp/details.shtml rather than pay.shtml. While posting
        // to details.shtml countryCode becomes a required as well.
        
        $request = array();
        switch ($card_code) 
        {
            case 'ay_VSA':
                $brandCode = "visa";
                break;
            
            case 'ay_MSC':
                $brandCode = "mc";
                break;

            case 'ay_MRC':  #mister cash
                $brandCode = "bcmc";
                break;

            # visa debit - sending in "visadebit" will not work
            case 'ay_VSD':
                $brandCode = "visa";
                break;

            # carte bleue 
            case 'ay_CAB':
                $brandCode = "cartebancaire";
                break;

            default:
                $brandCode = "";
                break;
        }

        $request["brandCode"] = $brandCode;

        $ship_days_timeframe = rtrim($soObj->get_expect_ship_days());

        // get the higher end
        if($ship_days_timeframe)
            $expect_ship_day = substr($ship_days_timeframe, -1);
        else
            $expect_ship_day = 15;
        
        $request["merchantReference"] = $merchantReference = $soObj->get_so_no();
        $request["paymentAmount"] = $paymentAmount = number_format($soObj->get_amount(), 2 , '', '');   // cannot have decimal separator
        $request["currencyCode"] = $currencyCode = $soObj->get_currency_id();   
        $request["shipBeforeDate"] = $shipBeforeDate = date("Y-m-d",strtotime("+$expect_ship_day days"));
        $request["skinCode"] = $skinCode = $card_id;
        $bill_country_id = $soObj->get_bill_country_id();
        switch ($bill_country_id)
        {
            case 'BE':
                $merchantAccount = "ValueBasketBE";
                break;
            
            case 'FR':
                $merchantAccount = "ValueBasketFR";
                break;

            case 'IE':
                $merchantAccount = "ValueBasketIE";
                break;

            case 'GB':
                $merchantAccount = "ValueBasketUK";
                break;
            default:
                $merchantAccount = "";
                break;
        }

        $request["merchantAccount"] = $merchantAccount;
        $request["sessionValidity"] = $sessionValidity = date("c",strtotime("+1 days"));

        // only certain languages allow extra country parameters
        switch ($bill_country_id) 
        {
            case 'GB':
                $shopperLocale = "en_GB";
                break;          
            case 'CA':
                if($soObj->get_lang_id() == "fr")
                    $shopperLocale = "fr_CA";
                else
                    $shopperLocale = "en_CA";
                break;
            case 'US':
                $shopperLocale = "en_US";
                break;
            case 'BE':
                $shopperLocale = "fr_BE";
                break;
            case 'CH':
                $shopperLocale = "fr_CH";
                break;
            default:
                if($soObj->get_lang_id() == "en")
                    $shopperLocale = "en_GB";
                else
                    $shopperLocale = $soObj->get_lang_id();
                break;
        }
        $request["shopperLocale"]  = $shopperLocale;
        // $orderData = base64_encode(gzencode("Orderdata to display on the HPP can be put here"));
        $request["orderData"] = $orderData = "";
        $request["countryCode"] = $countryCode = $bill_country_id;
        $request["shopperEmail"] = $shopperEmail =  $clientObj->get_email();
        $request["shopperReference"] = $shopperReference = $clientObj->get_id();
        $request["recurringContract"] = $recurringContract = "";
        $request["allowedMethods"] = $allowedMethods = "";
        $request["blockedMethods"] = $blockedMethods = "";
        $request["shopperStatement"] = $shopperStatement = "";
        $request["merchantReturnData"] = $merchantReturnData = "";
        $request["offset"] = $offset = "";
        $request["issuerId"] = $issuerId = "";


        // HMAC Key is a shared secret KEY used to encrypt the signature. Set up the HMAC
        // key: Adyen Test CA >> Skins >> Choose your Skin >> Edit Tab >> Edit HMAC key for Test and Live
        $hmacKey = $this->_hmac_key;
        // Compute the merchantSig
        $merchantSig = base64_encode(
                            pack("H*",
                                hash_hmac(
                                    'sha1',
                                    $paymentAmount . $currencyCode . $shipBeforeDate . $merchantReference . $skinCode . $merchantAccount .
                                    $sessionValidity . $shopperEmail . $shopperReference . $recurringContract .
                                    $allowedMethods . $blockedMethods . $shopperStatement . $merchantReturnData .
                                    $request["billingAddressType"] . $request["deliveryAddressType"] .
                                    $request["shopperType"] . $offset,
                                    $hmacKey
                                )
                            )
                        );
        $request["merchantSig"] = $merchantSig;
        return $request;

/*
* Optional fields below to display on payment form
        if($clientObj)
        {

            $request["billingAddress.street"]               = $shopperInfo["billing"]["billingAddress.street"]  = str_replace("|" ,  " ", $soObj->get_bill_address());
            $request["billingAddress.houseNumberOrName"]    = $shopperInfo["billing"]["billingAddress.houseNumberOrName"]  = "";
            $request["billingAddress.city"]                 = $shopperInfo["billing"]["billingAddress.city"]  = $clientObj->get_city();
            $request["billingAddress.postalCode"]           = $shopperInfo["billing"]["billingAddress.postalCode"]  = $clientObj->get_postcode();
            $request["billingAddress.stateOrProvince"]      = $shopperInfo["billing"]["billingAddress.stateOrProvince"]  = $clientObj->get_state();
            $request["billingAddress.country"]              = $shopperInfo["billing"]["billingAddress.country"]  = $clientObj->get_country_id();
            $request["billingAddressType"]                  = $shopperInfo["billing"]["billingAddressType"] = "";
            

            $request["deliveryAddress.street"]              = $shopperInfo["delivery"]["deliveryAddress.street"] = str_replace("|" ,  " ", $soObj->get_delivery_address());
            $request["deliveryAddress.houseNumberOrName"]   = $shopperInfo["delivery"]["deliveryAddress.houseNumberOrName"] = "";
            $request["deliveryAddress.city"]                = $shopperInfo["delivery"]["deliveryAddress.city"] = $soObj->get_delivery_city();
            $request["deliveryAddress.postalCode"]          = $shopperInfo["delivery"]["deliveryAddress.postalCode"] = $soObj->get_delivery_postcode();
            $request["deliveryAddress.stateOrProvince"]     = $shopperInfo["delivery"]["deliveryAddress.stateOrProvince"] = $soObj->get_delivery_state();
            $request["deliveryAddress.country"]             = $shopperInfo["delivery"]["deliveryAddress.country"] = $soObj->get_delivery_country_id();
            $request["deliveryAddressType"]                 = "1";  // not supplied: modifiable, 1: unmodifiable, visible, 2: unmodifiable, invisible

            // $request["shopper.firstName"] = $clientObj->get_forename();
            // $request["shopper.infix"] = "";
            // $request["shopper.lastName"] = $clientObj->get_surname();
            // $request["shopper.gender"] = "";
            // $request["shopper.dateOfBirthDayOfMonth"] = "";
            // $request["shopper.dateOfBirthMonth"] = "";
            // $request["shopper.dateOfBirthYear"] = "";
            // $request["shopper.telephoneNumber"] = (($clientObj->get_tel_1())?$clientObj->get_tel_1():"") . (($clientObj->get_tel_2())?$clientObj->get_tel_2():"") . (($clientObj->get_tel_3())?$clientObj->get_tel_3():"");
            // $request["shopperType"] = "1";
            $request["shopperType"] = "";
        }

        // Compute the billingAddressSig
        $billingAddressSig = base64_encode(
                                pack("H*", hash_hmac(
                                        'sha1',
                                        $request["billingAddress.street"] .
                                        $request["billingAddress.houseNumberOrName"] .
                                        $request["billingAddress.city"] .
                                        $request["billingAddress.postalCode"] .
                                        $request["billingAddress.stateOrProvince"] .
                                        $request["billingAddress.country"],
                                        $hmacKey
                                )));
        // Compute the deliveryAddressSig
        $deliveryAddressSig = base64_encode(
                                pack("H*", hash_hmac(
                                        'sha1',
                                        $request["deliveryAddress.street"] .
                                        $request["deliveryAddress.houseNumberOrName"] .
                                        $request["deliveryAddress.city"] .
                                        $request["deliveryAddress.postalCode"] .
                                        $request["deliveryAddress.stateOrProvince"] .
                                        $request["deliveryAddress.country"],
                                        $hmacKey
                                )));
        // Compute the shopperSig
        $shopperSig = base64_encode(
                        pack("H*", hash_hmac(
                                'sha1',
                                $request["shopper.firstName"] .
                                $request["shopper.infix"] .
                                $request["shopper.lastName"] .
                                $request["shopper.gender"] .
                                $request["shopper.dateOfBirthDayOfMonth"] .
                                $request["shopper.dateOfBirthMonth"] .
                                $request["shopper.dateOfBirthYear"] .
                                $request["shopper.telephoneNumber"],
                                $hmacKey
                        )));

        $request["billingAddressSig"] = $billingAddressSig;
        $request["deliveryAddressSig"] = $deliveryAddressSig;
        $request["shopperSig"] = $shopperSig = "";
*/

        
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
