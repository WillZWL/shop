<?php
namespace ESG\Panther\Form;

use Zend\I18n\Validator\PostCode;
use Zend\Validator\ValidatorChain;
use Zend\Validator\Regex;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;
use Zend\Validator\EmailAddress;
/***************
**  class to do filter + validation
*****************/
abstract class InputFilter {
    public $validInput = true;
    public $gatewayList = ["paypal", "moneybookers"];

    public function __construct() {
    }
/*******************************************
**        return array("validInput" => $validInput,
**                    "errorMessage" => $message,
**                    "value" => $value);
**  validInput: true or false
**  errorMessage: errorMessage
**  value: value after filter
********************************************/
    abstract public function isValidForm($input, $siteInfo = [], $option = []);

    public function isValidEuropean($input, $length) {
        if ((!is_null($input)) && (trim($input) != ""))
        {
            $validatorChain = new ValidatorChain();
            $validatorChain->attach(new Regex("/^[ \x{00C0}-\x{01FF}a-zA-Z0-9'\-]+$/u"))
                            ->attach(new StringLength(array('min' => 1,
                                                         'max' => $length)));
            $result = $validatorChain->isValid($input);
            (!$result) ? ($this->validInput = false) : "";
            return $result;
        }

        return $this->validInput = false;
    }

    public function isValidFirstName($firstName) {
        return $this->isValidEuropean($firstName, 50);
    }

    public function isValidLastName($lastName) {
        return $this->isValidEuropean($lastName, 50);
    }

    public function isValidCompany($company) {
        if (trim($company) == "") {
            return true;
        }
        elseif (!is_null($company))
            return $this->isValidEuropean($company, 50);

        return $this->validInput = false;
    }

    public function isValidAddress($address)
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new Regex("/^[ \x{00C0}-\x{01FF}a-zA-Z0-9,'\-\/#]+$/u"))
                        ->attach(new StringLength(array('min' => 1,
                                                     'max' => 1024)));
        $result = $validatorChain->isValid($address);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }

    public function isValidAddress1($address)
    {
        if (trim($address) == "")
        {
            return false;
        }
        elseif (!is_null($address))
        {
            return $this->isValidAddress($address);
        }
        return $this->validInput = false;
    }
    
    public function isValidAddress2($address)
    {
        if (trim($address) == "")
        {
            return true;
        }
        elseif (!is_null($address))
        {
            return $this->isValidAddress($address);
        }
        return $this->validInput = false;
    }

    public function isValidCity($city)
    {
        return $this->isValidEuropean($city, 80);
    }
    
    public function isValidPostCode($billPostal, $langId, $countryId)
    {
        $validator = new PostCode();
        $validator->setLocale(strtolower($langId) . "-" . strtoupper($countryId));
        $result = $validator->isValid($billPostal);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }
    
    public function isValidStateId($state)
    {
//we only verify the format of the stateId
        if (trim($state) == "") {
            return true;
        }
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new Regex("/^[a-zA-Z0-9\-]+$/"))
                        ->attach(new StringLength(array('min' => 1,
                                                     'max' => 6)));
        $result = $validatorChain->isValid($state);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }

    public function isValidPhone($countryCode, $areaCode, $number)
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new Regex("/^\+?(\(?\+?(\s*)?\d{1,3}\)?\s)?\(?\d{1,3}\)?[\s\d.-]{1,20}\d*$/"))
                        ->attach(new StringLength(array('min' => 1,
                                                     'max' => 32)));
        $result = $validatorChain->isValid($countryCode . $areaCode . $number);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }

    public function isValidEmail($email)
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new EmailAddress())
                        ->attach(new StringLength(array('min' => 3,
                                                     'max' => 255)));
        $result = $validatorChain->isValid($email);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }

    public function isValidFingerprint($fingerprint)
    {
        return $this->isValidSession($fingerprint);
    }

    public function isValidSession($session)
    {
        if (trim($session) == "")
        {
            return true;
        } else {
            $validator = new Regex("/^[a-zA-Z0-9]{26,40}$/");
            $result = $validator->isValid($session);
            (!$result) ? ($this->validInput = false) : "";
            return $result;
        }
    }

    public function isValidPaymentGateway($gateway)
    {
        if (in_array($gateway, $this->gatewayList)) {
            return true;
        } else {
            $this->validInput = false;
            return false;
        }
    }

    public function isValidCard($card)
    {
        $validator = new Regex("/^[a-zA-Z0-9_]+$/");
        $result = $validator->isValid($card);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }

    public function isValidCardId($cardId)
    {
        $validator = new Regex("/^[a-zA-Z0-9]+$/");
        $result = $validator->isValid($cardId);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }
    
    public function isPoBox($address)
    {
        $validator = new Regex("/\bP(ost|ostal)?([ \.]*O(ffice)?)?([ \.]*Box)?\b/i");
        $result = $validator->isValid($address);
        return $result;    
    }

    public function isValidSku($sku)
    {
        $validator = new Regex("/^99[0-9]+$/");
        $result = $validator->isValid($sku);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }
    
    public function isValidQty($qty)
    {
        $validator = new Digits();
        $result = $validator->isValid($qty);
        (!$result) ? ($this->validInput = false) : "";
        return $result;
    }
}
