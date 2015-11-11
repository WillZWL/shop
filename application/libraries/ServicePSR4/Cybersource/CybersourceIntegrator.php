<?php
namespace ESG\Panther\Service\Cybersource;

include_once(APPPATH . "libraries/ServicePSR4/Cybersource/HOP.php");
include_once(APPPATH . "libraries/ServicePSR4/Cybersource/CybersourceSoap.php");

class CybersourceIntegrator implements CybersourceSoapInterface
{
    const CYBER_SOURCE_PAYMENT_TEST_FORM = "https://orderpagetest.ic3.com/hop/orderform.jsp";
    const CYBER_SOURCE_PAYMENT_FORM = "https://orderpage.ic3.com/hop/orderform.jsp";
    const CYBER_SOURCE_DECISION_MANAGER_TEST_SITE = "https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.75.wsdl";
    const CYBER_SOURCE_DECISION_MANAGER_SITE = "https://ics2ws.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.75.wsdl";
    public $paymentAttribute = [];
    public $paymentCardType = ["001", "002", "033", "003", "042"];
    public $accountDetails = [["merchantId" => "eservices",
											"secret" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDD33DareKWADYC3wZflsVv5uXrryfhrgCX5G5jYLtFgiGLcI6TeWZ/abbcwLzaOUruW+qbjg68pIdWZ868ixsHMUqMV1oasKPzg3lHubaj3WxUm5VS0BIslfLkidiwHlezH9HlPRAFW+qamlo1lrEgO+/4M1tB4+1FeQdNabv2YQIDAQAB",
											"serialNumber" => "3466560486080176056166",
											"transaction_key" => "QcE5V7JpFfln/xHh6cXVEVNsaMlD9fw1E9NKGR6oQUaOI/CYbYq6zGVJG/B+l7+8z0eFb5xNX/D90PxZ4kcggVGpcpLamILRi1c3r6tQmxX/rCKfz0LVeNKJFPsEykQSo/RBledzFfdAG/mY/YQzFEmkethoG+1LZwWCwfDuprnN08xHjjrCPP8FDHpD4+xZU0l26nAzdWumL4zU4IOr36eW1ug8bVFZRnZexfM8biE0DGRQn7pI1bNsKy60q5k8W/emoIXGaJDR3BlckQcBTq6431Yd9r4Enb9s0ViDDPRnsY3g731P02Rqb6NXZFSsVvhv4CYJ25wZ+eQ2sGLZkw=="]
                                    ];
    private $_dmRequest;
    private $_dmReqData = null;

    public function __construct()
    {

    }

    public function cybersourceSetAttribute($input_attr)
    {
        if (isset($input_attr["card_type"])) {
            $this->paymentAttribute["card_cardType"] = $input_attr["card_type"];
        }
        if (isset($input_attr["payment_button"])) {
            $this->paymentAttribute["orderPage_buyButtonText"] = $input_attr["payment_button"];
        }
    }

    public function formPaymentRequestArray($input_value)
    {
        $post_arr = InsertSignature3($input_value["amount"], strtolower($input_value["currency"]), "authorization", $this->getMerchantId($input_value["countryCode"], $input_value["currency"]));
        $post_arr["orderNumber"] = $input_value["orderId"];
        $post_arr["billTo_company"] = $input_value["company"];
        $post_arr["billTo_firstName"] = $input_value["firstName"];
        $post_arr["billTo_lastName"] = $input_value["lastName"];
        $post_arr["billTo_country"] = $input_value["countryCode"];
        $post_arr["billTo_city"] = $input_value["city"];
        $post_arr["billTo_postalCode"] = $input_value["postalCode"];
        $post_arr["billTo_street1"] = $input_value["address1"];
        $post_arr["billTo_street2"] = $input_value["address2"];
        $post_arr["billTo_state"] = $input_value["state"];
        $post_arr["billTo_phoneNumber"] = $input_value["tel"];
        $post_arr["billTo_email"] = $input_value["email"];

        $post_arr["shipTo_company"] = $input_value["del_company"];
        $post_arr["shipTo_firstName"] = $input_value["del_firstName"];
        $post_arr["shipTo_lastName"] = $input_value["del_lastName"];
        $post_arr["shipTo_country"] = $input_value["del_countryCode"];
        $post_arr["shipTo_city"] = $input_value["del_city"];
        $post_arr["shipTo_postalCode"] = $input_value["del_postalCode"];
        $post_arr["shipTo_street1"] = $input_value["del_address1"];
        $post_arr["shipTo_street2"] = $input_value["del_address2"];
        $post_arr["shipTo_state"] = $input_value["del_state"];
        $post_arr["shipTo_phoneNumber"] = $input_value["del_tel"];

        $post_arr["amount"] = $input_value["amount"];
        $post_arr["currency"] = strtolower($input_value["currency"]);
        $post_arr["domain"] = strtolower($input_value["domain"]);

        return $post_arr + $this->paymentAttribute;
    }

    public function getMerchantId($country, $currency)
    {
        return $this->accountDetails[0];
    }
/*
    public function send_notification_to_pt($data, &$server_result, &$server_error, &$server_info)
    {
        $cpt = curl_init("http://dev.digitaldiscount.co.uk/checkout_redirect_method/payment_notification.php?payment_type=cybersource");

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
*/
    public function setDmRequestData($dm_request)
    {
        $this->_dmReqData = $dm_request;
    }

    public function sendDmRequest($isTestingSite, $order, &$request, &$response)
    {
        $this->_dmReqData = null;
        if ($isTestingSite == 1)
            $wsdl = CybersourceIntegrator::CYBER_SOURCE_DECISION_MANAGER_TEST_SITE;
        else
            $wsdl = CybersourceIntegrator::CYBER_SOURCE_DECISION_MANAGER_SITE;

        $cybersourceSoap = new CybersourceSoap($wsdl, []);
        $cybersourceSoap->addRequestListener($this);
//      var_dump($order);
        /* prepare the data */
        $merchantInfo = $this->getMerchantId($order->getDelCountryId(), $order->getCurrencyId());
        $cybersourceSoap->set_merchantId($merchantInfo);
        $this->_dmRequest = new \stdClass();
        $this->_dmRequest->merchantID = $merchantInfo["merchantId"];
        $this->_dmRequest->merchantReferenceCode = $order->getSoNO();
        $this->_dmRequest->clientLibrary = "PHP";
        $this->_dmRequest->clientLibraryVersion = phpversion();
        $this->_dmRequest->clientEnvironment = php_uname();
        $this->_dmRequest->deviceFingerprintID = $order->getFingerprintId();

        /* billing info */
        $this->_addBillingInfo($order);

        /* shipping info */
        $this->_addShippingInfo($order);

        /* so item */
        $this->_addProduct($order->so_item_detail);
        /* total amount */
        $purchaseTotals = new \stdClass();
        $purchaseTotals->currency = $order->getCurrencyId();
        $purchaseTotals->grandTotalAmount = $order->getAmount();
        $this->_dmRequest->purchaseTotals = $purchaseTotals;

        /* payment gateway information */
        $this->_additionalInformation($order);

        /* service */
        $afsService = new \stdClass();
        $afsService->run = "true";
        $this->_dmRequest->afsService = $afsService;

        $result = $cybersourceSoap->runTransaction($this->_dmRequest);

        $request = $this->_dmReqData;
        $response = $result;
    }

    private function _addBillingInfo($order)
    {
        $billTo = new \stdClass();
        $billTo->firstName = $order->getForename();
        $billTo->lastName = $order->getSurname();
        $billTo->company = $order->getCompanyname();
        $billTo->street1 = $order->getAddress1();
        $billTo->street2 = $order->getAddress2();
        $billTo->street3 = $order->getAddress3();
        $billTo->city = $order->getCity();
        if (($order->getCountryId() == "US")
            || ($order->getCountryId() == "CA")
        )
            $billTo->state = $order->getState();
        $billTo->postalCode = $order->getPostcode();
        $billTo->country = $order->getCountryId();
        $billTo->phoneNumber = "";
        if ($order->getTel1() != "")
            $billTo->phoneNumber = $order->getTel1();
        if ($order->getTel2() != "")
            $billTo->phoneNumber .= $order->getTel2();
        if ($order->getTel3() != "")
            $billTo->phoneNumber .= $order->getTel3();

        $billTo->phoneNumber = str_replace(" ", "", $billTo->phoneNumber);
        $billTo->phoneNumber = str_replace("-", "", $billTo->phoneNumber);
        $billTo->phoneNumber = str_replace("(", "", $billTo->phoneNumber);
        $billTo->phoneNumber = str_replace(")", "", $billTo->phoneNumber);

        if (strlen($billTo->phoneNumber) < 6) {
            $billTo->phoneNumber = date("mdHis");
        }

        $billTo->email = $order->getEmail();
        $billTo->ipAddress = $order->getCreateAt();
        $this->_dmRequest->billTo = $billTo;
    }

    private function _addShippingInfo($order)
    {
        $shipTo = new \stdClass();
        $shipTo->name = $order->getDelName();
        $shipTo->company = $order->getDelCompany();
        $shipTo->street1 = $order->getDelAddress1();
        $shipTo->street2 = $order->getDelAddress2();
        $shipTo->street3 = $order->getDelAddress3();
        $shipTo->city = $order->getDelCity();
        if (($order->getDelCountryId() == "US")
            || ($order->getDelCountryId() == "CA")
        )
            $shipTo->state = $order->getDelState();
        $shipTo->postalCode = $order->getDelPostcode();
        $shipTo->country = $order->getDelCountryId();

        $this->_dmRequest->shipTo = $shipTo;
    }

    private function _addProduct($so_item_detail = array())
    {
        $items = array();
        foreach ($so_item_detail as $item) {
            $item_obj = new \stdClass();
            $item_obj->quantity = $item->getQty();
            $item_obj->id = $item->getLineNo();
            $item_obj->productName = $item->getProdName();
            $item_obj->productSKU = $item->getProdSku();
            $item_obj->unitPrice = $item->getUnitPrice();
            array_push($items, $item_obj);
        }
        $this->_dmRequest->item = $items;
    }

    private function _additionalInformation($order)
    {
        $merchantDefinedData = new \stdClass();
        $merchantDefinedData->field1 = "VB" . $order->getPaymentGatewayId();

        if ($order->getPaymentGatewayId() == 'paypal') {
            $merchantDefinedData->field2 = $order->getRiskRef3();
            $merchantDefinedData->field3 = $order->getRiskRef4();
        }
        $this->_dmRequest->merchantDefinedData = $merchantDefinedData;
    }

    public function getFingerprintOrgId($debug = 0)
    {
        if ($debug == 1)
            return "1snn5n9w";
        else
            return "k8vif92e";
    }
}
