<?php
namespace ESG\Panther\Form;

class CheckoutFormFilter extends InputFilter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function isValidForm($input, $siteInfo = [])
    {
        $message = [];
        $value = [];

        $value["formSalt"] = trim($input->post("formSalt"));
        $value["billFirstName"] = trim($input->post("billingFirstName"));
        if (!$this->isValidFirstName($value["billFirstName"])) {
            $message["billFirstName"] = _("Not a valid billingFirstName");
        }
        $value["billLastName"] = trim($input->post("billingLastName"));
        if (!$this->isValidLastName($value["billLastName"])) {
            $message["billLastName"] = _("Not a valid billingLastName");
        }
        $value["billCompany"] = trim($input->post("billingCompany"));
        if (!$this->isValidCompany($value["billCompany"])) {
            $message["billCompany"] = _("Not a valid billingCompany");
        }
        $value["billCountry"] = trim($input->post("billingCountry"));
        if ($value["billCountry"] != $siteInfo->getPlatformCountryId()) {
            $message["billCompany"] = _("Not a valid billingCompany");
        }
        $value["billAddress1"] = trim($input->post("billingAddress1"));
        if (!$this->isValidAddress1($value["billAddress1"])) {
            $message["billAddress1"] = _("Not a valid billing address line 1");
        }
        $value["billAddress2"] = trim($input->post("billingAddress2"));
        if (!$this->isValidAddress2($value["billAddress2"])) {
            $message["billAddress2"] = _("Not a valid billing address line 2");
        }
        $value["billCity"] = trim($input->post("billingCity"));
        if (!$this->isValidCity($value["billCity"])) {
            $message["billCity"] = _("Not a valid billingCity");
        }
        $value["billPostal"] = trim($input->post("billingPostal"));
        if (!$this->isValidPostCode($value["billPostal"], $siteInfo->getLangId(), $value["billCountry"])) {
            $message["billPostal"] = _("Not a valid billingPostal");
        }

        if ($input->post("billingState"))
            $value["billState"] = trim($input->post("billingState"));
        else
            $value["billState"] = null;
        if (!$this->isValidStateId($value["billState"])) {
            $message["billState"] = _("Not a valid billState");
        }

        $value["billTelCountryCode"] = trim($input->post("billingTelCountryCode"));
        $value["billTelAreaCode"] = trim($input->post("billingTelAreaCode"));
        $value["billTelNumber"] = trim($input->post("billingTelNumber"));

        if (!$this->isValidPhone($value["billTelCountryCode"], $value["billTelAreaCode"], $value["billTelNumber"])) {
            $message["billNumber"] = _("Not a valid billing phone number");
        }

        $value["email"] = trim($input->post("billingEmail"));
        if (!$this->isValidEmail($value["email"])) {
            $message["email"] = _("Not a valid email address");
        }
        if ($input->post("billingPassword"))
            $value["billPassword"] = trim($input->post("billingPassword"));
        else
            $value["billPassword"] = null;
        if ($input->post("billingConfirmPassword"))
            $value["billConfirmPassword"] = trim($input->post("billingConfirmPassword"));
        else
            $value["billConfirmPassword"] = null;
        if (($value["billPassword"] != null)
            || ($value["billConfirmPassword"] != null)) {
            if ($value["billPassword"] != $value["billConfirmPassword"]) {
                $this->validInput = false;
                $message["billPassword"] = _("Password not match");
            }
        }

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
            $this->validInput = false;
            $message["salt"] = "The salt is not salty!";
        }

        return array("validInput" => $this->validInput,
                    "errorMessage" => $message,
                    "value" => $value);
    }
}
