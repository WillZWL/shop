<?php
namespace ESG\Panther\Form;

abstract class InputFilter {
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
    abstract public function isValidForm($input);
}
