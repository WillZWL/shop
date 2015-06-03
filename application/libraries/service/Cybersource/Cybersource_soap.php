<?php

interface Cybersource_soap_interface
{
	public function set_dm_request_data($result);
}

class Cybersource_soap extends SoapClient
{
	private $_merchantAccount;
	private $_cybersource_soap_interface_obj = null;

	public function set_merchantId($merchantInfo = array())
	{
		$this->_merchantAccount = $merchantInfo;
	}

    /**
     * XPaths that should be replaced in debug with '***'
     *
     * @var array
     */
    protected $_debugReplacePrivateDataXPaths = array(
        '//*[contains(name(),\'merchantID\')]/text()',
        '//*[contains(name(),\'card\')]/*/text()',
        '//*[contains(name(),\'UsernameToken\')]/*/text()'
    );

    public function __construct($wsdl, $options = array())
    {
        parent::__construct($wsdl, $options);
    }

	public function addRequestListener($request_listener_obj)
	{
		$this->_cybersource_soap_interface_obj = $request_listener_obj;
	}

    public function __doRequest($request, $location, $action, $version, $oneWay = 0)
    {
        $user = $this->_merchantAccount["merchantId"];
		$password = $this->_merchantAccount["transaction_key"];
        $soapHeader = "<SOAP-ENV:Header xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\"><wsse:Security SOAP-ENV:mustUnderstand=\"1\"><wsse:UsernameToken><wsse:Username>$user</wsse:Username><wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText\">$password</wsse:Password></wsse:UsernameToken></wsse:Security></SOAP-ENV:Header>";

        $requestDOM = new DOMDocument('1.0');
        $soapHeaderDOM = new DOMDocument('1.0');
        $requestDOM->loadXML($request);
        $soapHeaderDOM->loadXML($soapHeader);

        $node = $requestDOM->importNode($soapHeaderDOM->firstChild, true);
        $requestDOM->firstChild->insertBefore(
        $node, $requestDOM->firstChild->firstChild);

        $request = $requestDOM->saveXML();
        $requestDOMXPath = new DOMXPath($requestDOM);
        foreach ($this->_debugReplacePrivateDataXPaths as $xPath) {
            foreach ($requestDOMXPath->query($xPath) as $element) {
                $element->data = '***';
            }
        }

//        $debugData = array('request' => $requestDOM->saveXML());
		if ($this->_cybersource_soap_interface_obj != null)
			$this->_cybersource_soap_interface_obj->set_dm_request_data($request);
//		var_dump($debugData);
        try
		{
            $response = parent::__doRequest($request, $location, $action, $version, $oneWay);
        }
        catch (Exception $e)
		{
            $debugData['result'] = array('error' => $e->getMessage(), 'code' => $e->getCode());
			$response = "Exception" . $e->getMessage();
        }

//        $debugData['result'] = $response;

//		var_dump($response);
        return $response;
    }
}
?>