<?php

include_once "my_plugin_validator_abstract.php";

class Email_validator extends My_plugin_validator_abstract
{
	const ERROR_EMAIL_NOT_VALID = -1;
	const ERROR_STRING_EMPTY = -2;
    const EMAIL_NOT_VALID   = 'notEmail';
    const STRING_EMPTY = 'emailEmpty';

	private $_allow_empty = false;

    protected $_message_templates = array(
        self::EMAIL_NOT_VALID   => "Invalid email",
        self::STRING_EMPTY => "Input is empty"
    );

	public function __construct($option = array())
	{
		parent::__construct($option);
	}

    public function is_valid($value)
    {
        $this->set_value((string) $value);

        if ('' === $this->_value)
		{
			$this->_error(self::STRING_EMPTY, self::ERROR_STRING_EMPTY);
            return false;
        }

		if (!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', $value))
		{
			$this->_error(self::EMAIL_NOT_VALID, self::ERROR_EMAIL_NOT_VALID);
            return false;
        }
        return true;
    }
}

?>
