<?php
class Bibit {
  var $merchantCode;
  var $merchantPassword;

  var $xml;
  var $ExtPaymentInfo;
  var $orderId;
  var $totalammount;
  var $currcode;
  var $shopperDetails;
  var $description;
  var $cookie;

  function Bibitstart($debug) {
    $this->debug = $debug;
    if($this->debug)
      $this->url = "https://CHATANDVISION:testpw^*@secure-test.bibit.com/jsp/merchant/xml/paymentService.jsp";
    else
      $this->url = "https://" . $this->merchantCode . ":" . $this->merchantPassword . "@secure.bibit.com/jsp/merchant/xml/paymentService.jsp";
  }

  function CreateConnection() {
    $ch = curl_init ($this->url);
    curl_setopt($ch, CURLOPT_POST,1);
    if(file_exists($this->cookie)) curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
    else curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->xml); //$xml is the xml string
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_NOPROGRESS, 0);

    // echo "ch: $ch<HR>" ;

    $result = curl_exec ($ch); // result will contain XML reply from Bibit
    curl_close ($ch);
    if ( $result == false )
      print "Curl could not retrieve page '$this->url', curl_exec returns false";
    return $result;
  }

  function CancelOrderXML() {
  	$this->xml = <<<EOT
<?xml version='1.0' encoding='UTF-8'<!DOCTYPE paymentService PUBLIC '-//Bibit//DTD Bibit PaymentService v1//EN' 'http://dtd.bibit.com/paymentService_v1.dtd'>
		<paymentService version='1.4' merchantCode='{$this->merchantCode}'>
		<modify>
		<orderModification orderCode ='{$this->orderId}'>
		<cancel/>
		</orderModification>
		</modify>
		</paymentService>
EOT;
}


  function StartXML() {
    $this->xml = <<<EOT
<?xml version='1.0' encoding='UTF-8'<!DOCTYPE paymentService PUBLIC '-//Bibit//DTD Bibit PaymentService v1//EN' 'http://dtd.bibit.com/paymentService_v1.dtd'>
<paymentService version='1.4' merchantCode='{$this->merchantCode}'>
  <submit>
    <order orderCode = '{$this->orderId}'>
      <description>{$this->description}</description>
      <amount value='{$this->totalammount}' currencyCode = '{$this->currcode}' exponent = '2'/>\n
EOT;
}

function FillPaymentFormXML($invoiceData) {
    $this->xml .= <<<EOT
      <orderContent>
        <![CDATA[{$invoiceData}]]>
      </orderContent>
EOT;
  }

function FillExtPaymentXML($paymentArray){
	$this->ExtPaymentInfo="";
  if ($paymentArray['cardtype']== "SOLO_GB-SSL" || $paymentArray['cardtype']=="SWITCH-SSL" || $paymentArray['cardtype']=="MAESTRO-SSL"){
  	if($paymentArray['issuenum']!=''){
  	$this->ExtPaymentInfo .= "<issueNumber>".$paymentArray['issuenum']."</issueNumber>";
  	}
  	if ($paymentArray['start_month']!='' && $paymentArray['start_year']!=''){
  	$this->ExtPaymentInfo .= "<startDate><date month='".$paymentArray['start_month']."' year='".$paymentArray['start_year']."'/></startDate>";
  }

}
}

  function FillPaymentXML($paymentArray,$shopperArray){
   if(strlen($paymentArray['pares'])!=0){
       $info3d ="<info3DSecure><paResponse>".$paymentArray['pares']."</paResponse></info3DSecure>";
   }else $info3d="";

  $this->xml .= <<<EOT
  <paymentDetails>
  <{$paymentArray['cardtype']}>
  <cardNumber>{$paymentArray['cardnum']}</cardNumber>
  <expiryDate>
  <date month='{$paymentArray['exp_month']}' year='{$paymentArray['exp_year']}'/>
  </expiryDate>
  <cardHolderName>{$paymentArray['holdername']}</cardHolderName>
  {$this->ExtPaymentInfo}
  <cvc>{$paymentArray['cvc']}</cvc>
    <cardAddress>
    <address>
  				<firstName>{$shopperArray['firstname']}</firstName>
          <lastName>{$shopperArray['lastname']}</lastName>
          <street>{$shopperArray['street']}</street>
          <postalCode>{$shopperArray['postcode']}</postalCode>
          <city>{$shopperArray['city']}</city>
          <countryCode>{$shopperArray['countrycode']}</countryCode>
         <telephoneNumber>{$shopperArray['telephone']}</telephoneNumber>
          </address>
  </cardAddress>
  </{$paymentArray['cardtype']}>
  <session shopperIPAddress="{$shopperArray['ip']}" id="{$shopperArray['sid']}"/>
  {$info3d}
  </paymentDetails>\n
EOT;
  }

  function FillShopperXML($shopperArray) {
    $this->xml .= <<<EOT
      <shopper>
        <shopperEmailAddress>{$shopperArray['email']}</shopperEmailAddress>
      <browser>
        <acceptHeader>{$shopperArray['acceptheader']}</acceptHeader>
	<userAgentHeader>{$shopperArray['useragentheader']}</userAgentHeader>
        </browser>
	</shopper>
      <shippingAddress>
        <address>
          <firstName>{$shopperArray['firstname']}</firstName>
          <lastName>{$shopperArray['lastname']}</lastName>
          <street>{$shopperArray['street']}</street>
          <postalCode>{$shopperArray['postcode']}</postalCode>
          <city>{$shopperArray['city']}</city>
          <countryCode>{$shopperArray['countrycode']}</countryCode>
          <telephoneNumber>{$shopperArray['telephone']}</telephoneNumber>
        </address>
	</shippingAddress>\n
EOT;
  }

  function EndXML($paymentArray) {
       if(strlen($paymentArray['echodata'])!=0){
	  $this->xml .="<echoData>".$paymentArray['echodata']."</echoData>";
       }

    $this->xml .= <<<EOT
    </order>
  </submit>
</paymentService>
EOT;
  }
}
