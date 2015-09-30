<?php
namespace ESG\Panther\Form;

class CheckoutFormFilter extends InputFilter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function isValidForm($input)
    {
        $validInput = true;
        $message = array();
        $value = array();

        $value["formSalt"] = trim($input->post("formSalt"));
        $value["billFirstName"] = trim($input->post("billingFirstName"));
        $value["billLastName"] = trim($input->post("billingLastName"));
        $value["billCompany"] = trim($input->post("billingCompany"));
        $value["billCountry"] = trim($input->post("billingCountry"));
        $value["billAddress1"] = trim($input->post("billingAddress1"));
        $value["billAddress2"] = trim($input->post("billingAddress2"));
        $value["billCity"] = trim($input->post("billingCity"));
        $value["billPostal"] = trim($input->post("billingPostal"));
        if ($input->post("billingState"))
            $value["billState"] = trim($input->post("billingState"));
        else
            $value["billState"] = null;
        $value["billTelCountryCode"] = trim($input->post("billingTelCountryCode"));
        $value["billTelAreaCode"] = trim($input->post("billingTelAreaCode"));
        $value["billTelNumber"] = trim($input->post("billingTelNumber"));       
        $value["email"] = trim($input->post("billingEmail"));
        if ($input->post("billingPassword"))
            $value["billPassword"] = trim($input->post("billingPassword"));
        else
            $value["billPassword"] = null;
        if ($input->post("billingPassword"))
            $value["billConfirmPassword"] = trim($input->post("billingConfirmPassword"));
        else
            $value["billConfirmPassword"] = null;
        
/*
        $value["shipFirstName"] = trim($input->post("shipFirstName"));
        $value["shipLastName"] = trim($input->post("shipLastName"));
        $value["shipCompany"] = trim($input->post("shipCompany"));
        $value["shipAddress1"] = trim($input->post("shipAddress1"));
        $value["shipAddress2"] = trim($input->post("shipAddress2"));
        $value["shipCity"] = trim($input->post("shipCity"));
        $value["shipPostal"] = trim($input->post("shipPostal"));
        $value["shipTelCountryCode"] = trim($input->post("shipTelCountryCode"));
        $value["shipTelAreaCode"] = trim($input->post("shipTelAreaCode"));
        $value["shipTelNumber"] = trim($input->post("shipTelNumber"));
//        $value["shipCountry"] = trim($input->post("shipCountry"));
        $value["shipCountry"] = $value["billCountry"];
*/
        $value["shipFirstName"] = $value["billFirstName"];
        $value["shipLastName"] = $value["billLastName"];
        $value["shipCompany"] = $value["billCompany"];
        $value["shipAddress1"] = $value["billAddress1"];
        $value["shipAddress2"] = $value["billAddress2"];
        $value["shipCity"] = $value["billCity"];
        $value["shipPostal"] = $value["billPostal"];
        $value["shipState"] = $value["billState"];
        $value["shipTelCountryCode"] = $value["billTelCountryCode"];
        $value["shipTelAreaCode"] = $value["billTelAreaCode"];
        $value["shipTelNumber"] = $value["billTelNumber"];
        $value["shipCountry"] = $value["billCountry"];
        $value["paymentMethod"] = trim($input->post("paymentCard"));
        $paymentGateway = explode("%%", $value["paymentMethod"]);
        $value["paymentCardCode"] = $paymentGateway[0];
        if (sizeof($paymentGateway) > 1)
            $value["paymentCardId"] = $paymentGateway[1];
        else
            $value["paymentCardId"] = null;
        if (sizeof($paymentGateway) > 2)
            $value["paymentGatewayId"] = $paymentGateway[2];
        else
            $value["paymentGatewayId"] = null;
        $encrypt = new \CI_Encrypt();
        if ($encrypt->decode($value["formSalt"]) != PLATFORM)
        {
            $validInput = false;
            $message["salt"] = "The salt is not salty!";
        }
/*
        if (!$clientService->isValidFirstName($value["billFirstName"]))
        {
            $validInput = false;
            $message["billFirstName"] = _("Not a valid billing first name");
        }
        if (!$clientService->isValidLastName($value["billLastName"]))
        {
            $validInput = false;
            $message["billLastName"] = $this->translator->translate("Not a valid billing last name");
        }
        if (!$clientService->isValidCompany($value["billCompany"]))
        {
            $validInput = false;
            $message["billCompany"] = $this->translator->translate("Not a valid billing company name");
        }
        if (!$clientService->isValidCountry($value["billCountry"]))
        {
            $validInput = false;
            $message["billCountry"] = $this->translator->translate("Not a valid billing country");
        }
        if (!$clientService->isValidAddress1($value["billAddress1"]))
        {
            $validInput = false;
            $message["billAddress1"] = $this->translator->translate("Not a valid billing address line 1");
        }
        if (!$clientService->isValidAddress2($value["billAddress2"]))
        {
            $validInput = false;
            $message["billAddress2"] = $this->translator->translate("Not a valid billing address line 2");
        }
        if (!$clientService->isValidCity($value["billCity"]))
        {
            $validInput = false;
            $message["billCity"] = $this->translator->translate("Not a valid billing city");
        }
        if (!$clientService->isValidPostCode($value["billPostal"], $value["billCountry"]))
        {
            $validInput = false;
            $message["billPostal"] = $this->translator->translate("Not a valid billing postal");
        }
        if (!$clientService->isValidPhone($value["billTelCountryCode"], $value["billTelAreaCode"], $value["billTelNumber"], $value["billCountry"]))
        {
            $validInput = false;
            $message["billNumber"] = $this->translator->translate("Not a valid billing phone number");        
        }
        if (!$clientService->isValidEmail($value["email"]))
        {
            $validInput = false;
            $message["email"] = $this->translator->translate("Not a valid email address");        
        }
        if (($value["billPassword"] != "")
            || ($value["billConfirmPassword"] != ""))
        {
            if ($value["billPassword"] != $value["billConfirmPassword"])
            {
//            $validInput = false;
//                $message["email"] = $this->translator->translate("Not a valid email address");                  
                $value["billPassword"] = "ignore" . rand(1000, 2000);
            }
        }

//shipping 
        if (!$clientService->isValidFirstName($value["shipFirstName"]))
        {
            $validInput = false;
            $message["shipFirstName"] = $this->translator->translate("Not a valid shipping first name");
        }
        if (!$clientService->isValidLastName($value["shipLastName"]))
        {
            $validInput = false;
            $message["shipLastName"] = $this->translator->translate("Not a valid shipping last name");
        }
        if (!$clientService->isValidCompany($value["shipCompany"]))
        {
            $validInput = false;
            $message["shipCompany"] = $this->translator->translate("Not a valid shipping company name");
        }
*/
/*
        if (!$clientService->isValidCountry($value["shipCountry"]))
        {
            $validInput = false;
            $message["shipCountry"] = $this->translator->translate("Not a valid shipping country");
        }
*/
/*
        if (!$clientService->isValidAddress1($value["shipAddress1"]))
        {
            $validInput = false;
            $message["shipAddress1"] = $this->translator->translate("Not a valid shipping address line 1");
        }
        if (!$clientService->isValidAddress2($value["shipAddress2"]))
        {
            $validInput = false;
            $message["shipAddress2"] = $this->translator->translate("Not a valid shipping address line 2");
        }
        if (!$clientService->isValidCity($value["shipCity"]))
        {
            $validInput = false;
            $message["shipCity"] = $this->translator->translate("Not a valid shipping city");
        }
        if (!$clientService->isValidPostCode($value["shipPostal"], $value["billCountry"]))
        {
            $validInput = false;
            $message["shipPostal"] = $this->translator->translate("Not a valid shipping postal");
        }
        if (!$clientService->isValidPhone($value["shipTelCountryCode"], $value["shipTelAreaCode"], $value["shipTelNumber"], $value["shipCountry"]))
        {
            $validInput = false;
            $message["shipNumber"] = $this->translator->translate("Not a valid shipping phone number");        
        }
        if ($value["gatewayId"] != "Paypal")
        {
            $validInput = false;
            $message["gatewayId"] = $this->translator->translate("Not a valid gateway ID");
        }
*/
        return array("validInput" => $validInput,
                    "errorMessage" => $message,
                    "value" => $value);
    }
}
