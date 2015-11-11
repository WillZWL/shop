<?php

include_once "my_plugin_validator_abstract.php";

class Digits_validator extends My_plugin_validator_abstract
{
	const ERROR_NOT_DIGITS = -1;
	const ERROR_STRING_EMPTY = -2;
	const ERROR_DIGITS_INVALID = -3;
    const NOT_DIGITS   = 'notDigits';
    const STRING_EMPTY = 'digitsStringEmpty';
    const INVALID      = 'digitsInvalid';

	private $_allow_empty = false;

    protected $_message_templates = array(
        self::INVALID   => "Invalid digit",
        self::STRING_EMPTY => "Input is empty",
        self::NOT_DIGITS  => "'%value%' is not a digit",
    );

/**************************************
**	options - allow_empty
**	accepted parameter - ture/false
***************************************/

	public function __construct($option = array())
	{
		if (isset($option["allow_empty"]))
		{
			$this->_allow_empty = $option["allow_empty"];
		}
		parent::__construct($option);
	}

    public function is_valid($value)
    {
        if (!is_string($value) && !is_int($value))
		{
			$this->_error(self::INVALID, self::ERROR_DIGITS_INVALID);
            return false;
        }

        $this->set_value((string) $value);

        if (('' === $this->_value) && (!$this->_allow_empty))
		{
			$this->_error(self::STRING_EMPTY, self::ERROR_STRING_EMPTY);
            return false;
        }

		if (!preg_match('/^\d+$/', $value))
		{
			$this->_error(self::NOT_DIGITS, self::ERROR_NOT_DIGITS);
            return false;
        }

        return true;
    }
}

?>
