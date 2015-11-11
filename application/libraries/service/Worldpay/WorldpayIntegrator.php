<?php

class Worldpay_integrator
{
    const WORLD_PAY_TEST_PAYMENT_SERVER = "secure-test.worldpay.com/jsp/merchant/xml/paymentService.jsp";
    const WORLD_PAY_PAYMENT_SERVER = "secure.worldpay.com/jsp/merchant/xml/paymentService.jsp";
    const WORLD_PAY_MERCHANT_CODE_AUD = "VABAUD";
    const WORLD_PAY_MERCHANT_CODE_NZD = "VABNZD";
    const WORLD_PAY_MERCHANT_CODE_GBP = "VABGBP";
    const WORLD_PAY_MERCHANT_CODE_EUR = "VABEUR";
    const WORLD_PAY_MERCHANT_CODE_HKD = "VABHKD";
    const WORLD_PAY_MERCHANT_CODE_USD = "VABUSD";
    const WORLD_PAY_MERCHANT_CODE_SGD = "VABSGD";
    const WORLD_PAY_MERCHANT_PASSWORD_AUD = 'ft^yhji(ol000';
    const WORLD_PAY_MERCHANT_PASSWORD_GBP = '##00madf3$ff9';
    const WORLD_PAY_MERCHANT_PASSWORD_EUR = '123v^_^vwnojl';
    const WORLD_PAY_MERCHANT_PASSWORD_HKD = 'sd__dr$fsd^88';
    const WORLD_PAY_MERCHANT_PASSWORD_USD = 'v$.$vhaha1234';
    const WORLD_PAY_MERCHANT_PASSWORD_NZD = 'dyusjfmx43xx##';
    const WORLD_PAY_MERCHANT_PASSWORD_SGD = 'u3isgd(oo)mkhj';
//  const WORLD_PAY_INSTALLATION_ID_GBP = "276474";
    const WORLD_PAY_INSTALLATION_ID_OTHER = "279000";
    const DEBUG_EMAIL_ADDRESS = "oswald@eservicesgroup.net";

    public $server;
    public $merchant_code;
    public $password;
    public $security_token;
    public $debug = 0;
    public $url_parameters = '';

    public $server_response_result;
    public $server_error;
    public $server_information;


    public function Worldpay_integrator($server = '', $merchant_code = '', $password = '', $debug = 0)
    {
        $this->worldpay_config($server, $merchant_code, $password, $debug);
    }

    public function worldpay_config($server = '', $merchant_code = '', $password = '', $debug = 0)
    {
        if (empty($server)) {
            if ($debug)
                $this->server = Worldpay_integrator::WORLD_PAY_TEST_PAYMENT_SERVER;
            else
                $this->server = Worldpay_integrator::WORLD_PAY_PAYMENT_SERVER;
        } else
            $this->server = $server;
        $this->merchant_code = $merchant_code;
        $this->password = $password;
        $this->debug = $debug;
    }

    public function get_server()
    {
        return $this->server;
    }

    public function get_merchant_code()
    {
        return $this->server;
    }

    public function form_query_xml()
    {

    }

    public function form_payment_xml($xmlInformation = array(), $merchantCode, $installationId)
    {
        $decimal = 0;
        $amount_conversion = $this->amount_conversion($xmlInformation["amount"], $decimal);
        $totalAmountWithCurrency = base64_decode($xmlInformation["totalAmountWithCurrency"]);
        $delivery = base64_decode($xmlInformation["deliveryCharge"]);
        $discount = base64_decode($xmlInformation["discount"]);
        $costOfItems = base64_decode($xmlInformation["costOfItems"]);

        if (trim($xmlInformation["postalCode"]) == '') {
            $postalCode = 'NA';
        } else {
            $postalCode = $xmlInformation["postalCode"];
        }

        $xmlString = <<<EOT
<?xml version='1.0' encoding='UTF-8'<!DOCTYPE paymentService PUBLIC '-//WorldPay//DTD WorldPayPaymentService v1//EN' 'http://dtd.worldpay.com/paymentService_v1.dtd'>
<paymentService version='1.4' merchantCode='{$merchantCode}'>
  <submit>
    <order orderCode='{$xmlInformation["orderId"]}' installationId='{$installationId}'>
      <description>{$xmlInformation["description"]}</description>
      <amount value='{$amount_conversion}' currencyCode='{$xmlInformation["currency"]}' exponent='{$decimal}' />
      <orderContent>
        <![CDATA[
        ]]>
      </orderContent>
      <paymentMethodMask>
        <include code='ALL' />
      </paymentMethodMask>
      <shopper>
        <shopperEmailAddress>{$xmlInformation["email"]}</shopperEmailAddress>
      </shopper>
            <shippingAddress>
                <address>
                  <firstName><![CDATA[{$xmlInformation["firstName"]}]]></firstName>
                  <lastName><![CDATA[{$xmlInformation["lastName"]}]]></lastName>
                    <address1><![CDATA[{$xmlInformation["address1"]}]]></address1>
                    <address2><![CDATA[{$xmlInformation["address2"]}]]></address2>
                    <address3><![CDATA[{$xmlInformation["address3"]}]]></address3>
                  <postalCode>{$postalCode}</postalCode>
                  <city>{$xmlInformation["city"]}</city>
                  <countryCode>{$xmlInformation["countryCode"]}</countryCode>
                  <telephoneNumber>{$xmlInformation["tel"]}</telephoneNumber>
                </address>
            </shippingAddress>
    </order>
  </submit>
</paymentService>
EOT;
        return utf8_encode($xmlString);
    }

    public function amount_conversion($input_amount, &$decimal)
    {
        $amount_conversion = $input_amount;
        $dot_position = strrchr($input_amount, ".");
        $total_string_length = strlen($input_amount);
        if (!strrchr($input_amount, ".")) {
            $decimal = 0;
        } else {
            $separatedNumber = explode(".", $input_amount);
            if (sizeof($separatedNumber) > 2) {
//prevent hacking cookie, should be fraud order
                $amount_conversion = "100000";
                $decimal = 0;
            } else {
                $decimal = strlen($separatedNumber[1]);
                if ($decimal == 1) {
                    $decimal = 2;
                    $amount_conversion = $separatedNumber[0] . $separatedNumber[1] . '0';
                } else {
                    $amount_conversion = $separatedNumber[0] . $separatedNumber[1];
                }
            }
        }
        return $amount_conversion;
    }

    public function send_data_to_wp($data, $merchant_code = '', $password = '', &$server_result, &$server_error, &$server_info)
    {
        if (empty($merchant_code))
            $localMerchant = $this->merchant_code;
        else
            $localMerchant = $merchant_code;
        if (empty($password))
            $localPassword = $this->password;
        else
            $localPassword = $password;
        $cpt = curl_init("https://$localMerchant:$localPassword@$this->server");

//      print "https://$localMerchant:$localPassword@$this->server";

        curl_setopt($cpt, CURLOPT_POST, 1);
        curl_setopt($cpt, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cpt, CURLOPT_NOPROGRESS, 0);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cpt, CURLOPT_CONNECTTIMEOUT, 45);
        curl_setopt($cpt, CURLOPT_TIMEOUT, 50);

        curl_setopt($cpt, CURLOPT_POSTFIELDS, $data);

        $server_result = curl_exec($cpt);
        $server_error = curl_error($cpt);
        $server_info = curl_getinfo($cpt);

        curl_close($cpt);
    }

    public function get_url_parameters()
    {
        return $this->url_parameters;
    }

    public function worldpay_set_attribute($appearance_parameter = array())
    {
        if (isset($appearance_parameter['successUrl']))
            $this->url_parameters .= "&successURL=" . urlencode($appearance_parameter['successUrl']);
        if (isset($appearance_parameter['failureUrl']))
            $this->url_parameters .= "&failureURL=" . urlencode($appearance_parameter['failureUrl']);
        if (isset($appearance_parameter['cancelUrl']))
            $this->url_parameters .= "&cancelURL=" . urlencode($appearance_parameter['cancelUrl']);
        if (isset($appearance_parameter['preferredPaymentMethod']))
            $this->url_parameters .= "&preferredPaymentMethod=" . urlencode($appearance_parameter['preferredPaymentMethod']);;
        if (isset($appearance_parameter['language_id']))
            $this->url_parameters .= "&language=" . urlencode($appearance_parameter['language_id']);;
        if (isset($appearance_parameter['country_id']))
            $this->url_parameters .= "&country=" . urlencode($appearance_parameter['country_id']);;
    }

    public function get_submit_result($inputXml)
    {
        $simpleXml = new SimpleXMLElement($inputXml);
        return $simpleXml;
    }

    public function send_notification_to_vb($data, &$server_result, &$server_error, &$server_info)
    {
        $cpt = curl_init("http://dev.valuebasket.com/checkout_redirect_method/payment_notification.php?payment_type=worldpay");

        curl_setopt($cpt, CURLOPT_POST, 1);
        curl_setopt($cpt, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cpt, CURLOPT_NOPROGRESS, 0);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($cpt, CURLOPT_POSTFIELDS, $data);

        $server_result = curl_exec($cpt);
        $server_error = curl_error($cpt);
        $server_info = curl_getinfo($cpt);

        curl_close($cpt);
    }
}

