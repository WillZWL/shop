<?php
namespace ESG\Panther\Form;

use Zend\I18n\Validator\PostCode;
use Zend\Validator\ValidatorChain;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Zend\Validator\EmailAddress;

abstract class InputFilter {
    public $validInput = true;

    public function __construct()
    {
    }
/*******************************************
**        return array("validInput" => $validInput,
**                    "errorMessage" => $message,
**                    "value" => $value);
**  validInput: true or false
**  errorMessage: errorMessage
**  value: value after filter
********************************************/
    abstract public function isValidForm($input, $siteInfo = []);

    public function isValidEuropean($input, $length) {
        if ((!is_null($input)) && (trim($input) != ""))
        {
            $validatorChain = new ValidatorChain();
            $validatorChain->attach(new Regex("/^[ \x{00C0}-\x{01FF}a-zA-Z0-9'\-]+$/u"))
                            ->attach(new StringLength(array('min' => 1,
                                                         'max' => $length)));
            return $this->validInput = $validatorChain->isValid($input);
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
        return $this->validInput = $validatorChain->isValid($address);
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
        return $this->validInput = $validator->isValid($billPostal);
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
        return $this->validInput = $validatorChain->isValid($state);    
    }

    public function isValidPhone($countryCode, $areaCode, $number)
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new Regex("/^\+?(\(?\+?(\s*)?\d{1,3}\)?\s)?\(?\d{1,3}\)?[\s\d.-]{1,20}\d*$/"))
                        ->attach(new StringLength(array('min' => 1,
                                                     'max' => 32)));
        return $this->validInput = $validatorChain->isValid($countryCode . $areaCode . $number);    
    }
    
    public function isValidEmail($email)
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new EmailAddress())
                        ->attach(new StringLength(array('min' => 3,
                                                     'max' => 255)));
        return $this->validInput = $validatorChain->isValid($email);
    }
}
