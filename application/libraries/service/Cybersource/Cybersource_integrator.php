<?php

include_once(APPPATH . "libraries/service/Cybersource/HOP.php");
include_once(APPPATH . "libraries/service/Cybersource/Cybersource_soap.php");

class Cybersource_integrator implements Cybersource_soap_interface
{
    const CYBER_SOURCE_PAYMENT_TEST_FORM = "https://orderpagetest.ic3.com/hop/orderform.jsp";
    const CYBER_SOURCE_PAYMENT_FORM = "https://orderpage.ic3.com/hop/orderform.jsp";
    const CYBER_SOURCE_DECISION_MANAGER_TEST_SITE = "https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.75.wsdl";
    const CYBER_SOURCE_DECISION_MANAGER_SITE = "https://ics2ws.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.75.wsdl";

    private $_dm_request;
    private $_dm_req_data = null;
    public $payment_attribute = array();
    public $payment_card_type = array("001", "002", "033", "003", "042");
    public $accountDetails = array(array("merchantId" => "eservices",
                                            "secret" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDD33DareKWADYC3wZflsVv5uXrryfhrgCX5G5jYLtFgiGLcI6TeWZ/abbcwLzaOUruW+qbjg68pIdWZ868ixsHMUqMV1oasKPzg3lHubaj3WxUm5VS0BIslfLkidiwHlezH9HlPRAFW+qamlo1lrEgO+/4M1tB4+1FeQdNabv2YQIDAQAB",
                                            "serialNumber" => "3466560486080176056166",
                                            "transaction_key" => "grEZVmqmiXheF3ujATr1RKkCQCjQR3S7tVbUylLlqr6ExiT+/coXxhjW0jq0IdJ3N20PGDIArOlv0isPq/DFxrqV9oqDXr94fuUf6/1ZK2QHVxNMtP7Q/D6FBTCxKVpW/7jAuo9TRBUoaCTNBfuKt7WMyisf1fLTDCaaXXjxdA8PgN/ZZHz3vu93RuEBOvVEqQJAKNBHdLu1VtTKUuWqvoTGJP79yhfGGNbSOrQh0nc3bQ8YMgCs6W/SKw+r8MXGupX2ioNev3h+5R/r/VkrZAdXE0y0/tD8PoUFMLEpWlb/uMC6j1NEFShoJM0F+4q3tYzKKx/V8tMMJppdePF0Dw=="),
                                    array("merchantId" => "eservice_01",
                                            "secret" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9k9vBJE5qIBmR3SQAW6KQXE9gPLRzUHfPwrrKoOqmiM5Xki97U0ocnZJR2w90N3fjvYDPqTzwRK2KNo6zdtGk/c3sf7vfsX9PwLtUdBiyRH3vQ/mKBiGwtxUOyXiu57if37kTipans/w01dVbC0t84LXy3xOFoiaAWZsuR9QvJQIDAQAB",
                                            "serialNumber" => "3492321334800176056166",
                                            "transaction_key" => "XZqMY3fs4fVTra1BpZShEq1TXHhhGV6wV9e7i73d54+pHPtmAoKtSnNK5o9kxPbb675jskMGAApqwxbV8hSNcpHCYdb9Yg5hYFlgAOkiW5aG13Weo6o6QXAs+sHlBKOL4rfpJK1ugsn8WRbUsmRvJeQ9/12j0x4fZMmnpiSQs5RZTzqvqmSWCjaXvyn9d5Zn550FeGEZXrBX17uLvd3nj6kc+2YCgq1Kc0rmj2TE9tvrvmOyQwYACmrDFtXyFI1ykcJh1v1iDmFgWWAA6SJblobXdZ6jqjpBcCz6weUEo4vit+kkrW6CyfxZFtSyZG8l5D3/XaPTHh9kyaemJJCzlA=="),
                                    array("merchantId" => "eservice_02",
                                            "secret" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDKic8NjHv5HSdz0+5mfgxlmn+yq8x6f3l1Pf0eGBIYNAn0yDVQXspcG7SR35Lvvcq8B/JLTOlRvjo9DvG4WojFLqBQlZTHbpDcigu49fEIwO+yR6w6S049bnAawXsZH3duW9SoLco22mE4PNbt5jwmhXd8BIvnoGtQatNnZDrNVQIDAQAB",
                                            "serialNumber" => "3492333448370176056166",
                                            "transaction_key" => "t/H8yn5rdt1YPVvnJFh5qYAQDyW0imzeIqkWJE+iKp9UjiVZrMGzUptN2aen7w00ExiRXX2pGa5E270wEB4oDI88X9IHKPRxJ5VO8YsAUnPeMX1Zzhp8ZUWbluULzHt/UNjjtaOvE6FpmyPaG9fMLciWgJPXhCizY2QdFJOqAOutkgkQgifELqHQRdMkWHmpgBAPJbSKbN4iqRYkT6Iqn1SOJVmswbNSm03Zp6fvDTQTGJFdfakZrkTbvTAQHigMjzxf0gco9HEnlU7xiwBSc94xfVnOGnxlRZuW5QvMe39Q2OO1o68ToWmbI9ob18wtyJaAk9eEKLNjZB0Uk6oA6w=="),
                                    array("merchantId" => "eservice_03",
                                            "secret" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDfAoT4xnzNjEEkK081+DU/+wJuB5zHY20IJ1NafgkbloTWOAKV/tXMpt06Me6hN6ukkwjrb3kNvsBT8jD3HXqkx8Kp6dIce42aCGx5BbnjyzF/uUyQt1V3tFbmIuiiUKxEnbEd5rAXtCqu8IVkOA5XtXnSFAcCVpWP5zNp6uoc2QIDAQAB",
                                            "serialNumber" => "3492366763810176056165",
                                            "transaction_key" => "Ob1PnVJptbucQ4G7aslOPGmwhr3AetlnFWUHQsi0MEXGckGBt+6BG7Du1Z1drNbHO3AU0oilrMmjLxQUsZlKPIv7HuWXyfjtl6qhj+87WJpcx92T89Ek3dUSREK16x9sAkBR5L5hzMzwtaVHMvnsn3ai/0+Tdyc1DtYkTaRpBCRjsXr9khROpMnMw7DedbpWLCqGvcB62WcVZQdCyLQwRcZyQYG37oEbsO7VnV2s1sc7cBTSiKWsyaMvFBSxmUo8i/se5ZfJ+O2XqqGP7ztYmlzH3ZPz0STd1RJEQrXrH2wCQFHkvmHMzPC1pUcy+eyfdqL/T5N3JzUO1iRNpGkEJA=="),
                                    array("merchantId" => "eservice_04",
                                            "secret" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDY8vd19i31s9RiEzkFcPNMDvtKQnxgxXBWhyMfKoxceFjewUKgmV9fQ1kDXsZSGrX38S3zNmyXFD/J56q0si/kV3AV3BMbqCv4Xj3daPOUFZBUJmjVg79s0H2jfkFXRkXDkNmmLGrzZbiL6U3md7e4l24sBEDF0JoSAOgWKCmbUQIDAQAB",
                                            "serialNumber" => "3492378018240176056165",
                                            "transaction_key" => "w5QbDjmR6E0++DQKfAS2OI5h3+xtW3TDVyak7rvpRbMKBYIUVrKnku91Toc0Ow/frlEuFyDd9Hinc1aMT2V/sgthbX9xCSxDtuFkGgj2NlVhTo06U6F1DGGCOMA+4hOHSGgufHkYvlRLwNWezjksZn02qFmrFDb+IA/Qv2lj25q1AqT1NKPhjv0PYop8BLY4jmHf7G1bdMNXJqTuu+lFswoFghRWsqeS73VOhzQ7D9+uUS4XIN30eKdzVoxPZX+yC2Ftf3EJLEO24WQaCPY2VWFOjTpToXUMYYI4wD7iE4dIaC58eRi+VEvA1Z7OOSxmfTaoWasUNv4gD9C/aWPbmg=="),
                                    array("merchantId" => "eservice_05",
                                            "secret" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC4SmF1M21TDkAh0K71N2/OI90Z2KyJ7eVOEHU7YkEauvyJ3QSQ6Kn2DG1QBnwWptZrR8r9MdJpUqLGtX8z1ydGZPp6dQoZN83ApATYL7SAIS59fAWl6W7Sq/I/R9u15Johx/i+lkqMFxiAKgWBbYwQG5imoCnkDbhgKsPhiOemhQIDAQAB",
                                            "serialNumber" => "3492379569740176056165",
                                            "transaction_key" => "Og4nT6TShl2+H6HjySeaVGeccDoPGk00tSzbrG2Isb+45Jc5tCBBpol/6Ck++uJf2QtOxP7jYwmEtjyTaRHrvTLuqLvLW8StaOpHapxb9LdlRkldZ6QARR8cj98z6BHwEKMk+SAhbzzrsPDsJC5DvZ345/WGeHqNNF+8WMhxGQ3bQduo/R8WEZC+XtTNTC1UZ5xwOg8aTTS1LNusbYixv7jklzm0IEGmiX/oKT764l/ZC07E/uNjCYS2PJNpEeu9Mu6ou8tbxK1o6kdqnFv0t2VGSV1npABFHxyP3zPoEfAQoyT5ICFvPOuw8OwkLkO9nfjn9YZ4eo00X7xYyHEZDQ==")
                                    );

    public function __construct()
    {

    }

    public function cybersource_set_attribute($input_attr)
    {
        if (isset($input_attr["card_type"]))
        {
            $this->payment_attribute["card_cardType"] = $input_attr["card_type"];
        }
        if (isset($input_attr["payment_button"]))
        {
            $this->payment_attribute["orderPage_buyButtonText"] = $input_attr["payment_button"];
        }
/*
        if (isset($input_attr["payment_response"]))
        {
            $this->payment_attribute["orderPage_receiptResponseURL"] = $input_attr["payment_response"];
        }
        if (isset($input_attr["payment_cancel"]))
        {
            $this->payment_attribute["orderPage_cancelResponseURL"] = $input_attr["payment_cancel"];
        }
        if (isset($input_attr["payment_decline"]))
        {
            $this->payment_attribute["orderPage_declineResponseURL"] = $input_attr["payment_decline"];
        }
*/
    }

    public function form_payment_request_array($input_value)
    {
        $post_arr = InsertSignature3($input_value["amount"], strtolower($input_value["currency"]), "authorization", $this->get_merchant_Id($input_value["countryCode"], $input_value["currency"]));
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

        return $post_arr + $this->payment_attribute;
    }

    public function get_merchant_Id($country, $currency)
    {
//we enable DM only, so using 1 acct
        return $this->accountDetails[0];
/*
        if ($currency == "AUD")
        {
            $account = $this->accountDetails[0];
        }
        elseif ($currency == "EUR")
        {
            $account = $this->accountDetails[1];
        }
        elseif ($currency == "GBP")
        {
            $account = $this->accountDetails[2];
        }
        elseif ($currency == "HKD")
        {
            $account = $this->accountDetails[3];
        }
        elseif ($currency == "USD")
        {
            $account = $this->accountDetails[4];
        }
        elseif ($currency == "NZD")
        {
            $account = $this->accountDetails[5];
        }
        else
            $account = $this->accountDetails[3];

        return $account;
*/
    }

    public function send_notification_to_vb($data, &$server_result, &$server_error, &$server_info)
    {
        $cpt = curl_init("http://dev.valuebasket.com/checkout_redirect_method/payment_notification.php?payment_type=cybersource");

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

    public function set_dm_request_data($dm_request)
    {
        $this->_dm_req_data = $dm_request;
    }

    public function send_dm_request($isTestingSite, $order, &$request, &$response)
    {
        $this->_dm_req_data = null;
        if ($isTestingSite == 1)
            $wsdl = Cybersource_integrator::CYBER_SOURCE_DECISION_MANAGER_TEST_SITE;
        else
            $wsdl = Cybersource_integrator::CYBER_SOURCE_DECISION_MANAGER_SITE;

        $cybersource_soap = new Cybersource_soap($wsdl, array());
        $cybersource_soap->addRequestListener($this);
//      var_dump($order);
/* prepare the data */
        $merchantInfo = $this->get_merchant_Id($order->get_del_country_id(), $order->get_currency_id());
        $cybersource_soap->set_merchantId($merchantInfo);
        $this->_dm_request = new stdClass();
        $this->_dm_request->merchantID = $merchantInfo["merchantId"];
        $this->_dm_request->merchantReferenceCode = $order->get_so_no();
        $this->_dm_request->clientLibrary = "PHP";
        $this->_dm_request->clientLibraryVersion = phpversion();
        $this->_dm_request->clientEnvironment = php_uname();
        $this->_dm_request->deviceFingerprintID = $order->get_fingerprintId();

/* billing info */
        $this->_add_billing_info($order);

/* shipping info */
        $this->_add_shipping_info($order);

/* so item */
        $this->_add_product($order->so_item);
/* total amount */
        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = $order->get_currency_id();
        $purchaseTotals->grandTotalAmount = $order->get_amount();
        $this->_dm_request->purchaseTotals = $purchaseTotals;

/* payment gateway information */
        $this->_additional_information($order);

/* service */
        $afsService = new stdClass();
        $afsService->run = "true";
        $this->_dm_request->afsService = $afsService;

        $result = $cybersource_soap->runTransaction($this->_dm_request);

        $request = $this->_dm_req_data;
        $response = $result;
    }

    private function _add_product($so_item = array())
    {
        $items = array();
        foreach($so_item as $item)
        {
            $item_obj = new stdClass();
            $item_obj->quantity = $item->get_qty();
            $item_obj->id = $item->get_line_no();
            $item_obj->productName = $item->get_prod_name();
            $item_obj->productSKU = $item->get_prod_sku();
            $item_obj->unitPrice = $item->get_unit_price();
            array_push($items, $item_obj);
        }
        $this->_dm_request->item = $items;
    }

    private function _add_billing_info($order)
    {
        $billTo = new stdClass();
        $billTo->firstName = $order->get_forename();
        $billTo->lastName = $order->get_surname();
        $billTo->company = $order->get_companyname();
        $billTo->street1 = $order->get_address_1();
        $billTo->street2 = $order->get_address_2();
        $billTo->street3 = $order->get_address_3();
        $billTo->city = $order->get_city();
        if (($order->get_country_id() == "US")
            || ($order->get_country_id() == "CA"))
            $billTo->state = $order->get_state();
        $billTo->postalCode = $order->get_postcode();
        $billTo->country = $order->get_country_id();
        $billTo->phoneNumber = "";
        if ($order->get_tel_1() != "")
            $billTo->phoneNumber = $order->get_tel_1();
        if ($order->get_tel_2() != "")
            $billTo->phoneNumber .= $order->get_tel_2();
        if ($order->get_tel_3() != "")
            $billTo->phoneNumber .= $order->get_tel_3();

        $billTo->phoneNumber = str_replace(" ", "", $billTo->phoneNumber);
        $billTo->phoneNumber = str_replace("-", "", $billTo->phoneNumber);
        $billTo->phoneNumber = str_replace("(", "", $billTo->phoneNumber);
        $billTo->phoneNumber = str_replace(")", "", $billTo->phoneNumber);

        if (strlen($billTo->phoneNumber) < 6)
        {
            $billTo->phoneNumber = date("mdHis");
        }

        $billTo->email = $order->get_email();
        $billTo->ipAddress = $order->get_create_at();
        $this->_dm_request->billTo = $billTo;
    }

    private function _add_shipping_info($order)
    {
        $shipTo = new stdClass();
        $shipTo->name = $order->get_del_name();
        $shipTo->company = $order->get_del_company();
        $shipTo->street1 = $order->get_del_address_1();
        $shipTo->street2 = $order->get_del_address_2();
        $shipTo->street3 = $order->get_del_address_3();
        $shipTo->city = $order->get_del_city();
        if (($order->get_del_country_id() == "US")
            || ($order->get_del_country_id() == "CA"))
            $shipTo->state = $order->get_del_state();
        $shipTo->postalCode = $order->get_del_postcode();
        $shipTo->country = $order->get_del_country_id();

        $this->_dm_request->shipTo = $shipTo;
    }

    private function _additional_information($order)
    {
        $merchantDefinedData = new stdClass();
        $merchantDefinedData->field1 = "VB" . $order->get_payment_gateway_id();

        if ($order->get_payment_gateway_id() == 'paypal')
        {
            $merchantDefinedData->field2 = $order->get_risk_ref3();
            $merchantDefinedData->field3 = $order->get_risk_ref4();
        }
        $this->_dm_request->merchantDefinedData = $merchantDefinedData;
    }

    public function get_fingerprint_org_id($debug = 0)
    {
        if ($debug == 1)
            return "1snn5n9w";
        else
            return "k8vif92e";
    }
}
