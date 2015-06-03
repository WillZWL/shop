<?php

include_once "my_plugin_validator_abstract.php";

class Regex_validator extends My_plugin_validator_abstract
{
	const REGX_SKU_FORMAT = "/^\d{5}-[A-Z]{2}-[A-Z]{2}$/";

	const ERROR_REGEX_INVALID = -1;
	const ERROR_REGEX_NOT_MATCH = -2;
	const ERROR_REGEX_ERROR = -3;
    const INVALID   = 'regexInvalid';
    const NOT_MATCH = 'regexNotMatch';
    const ERROROUS  = 'regexErrorous';

    protected $_message_templates = array(
        self::INVALID   => "Invalid type given, value should be string, integer or float",
        self::NOT_MATCH => "'%value%' does not match against pattern '%pattern%'",
        self::ERROROUS  => "There was an internal error while using the pattern '%pattern%'",
    );

    protected $_message_variables = array('pattern' => '_pattern');
	protected $_pattern;

    public function __construct($pattern)
    {
		try
		{
			$this->setPattern($pattern);
		}
		catch(My_plugin_validator_exception $mpve)
		{
			throw new My_plugin_validator_exception("Internal error while using the pattern");
		}
    }

    public function setPattern($pattern)
    {
        $this->_pattern = (string) $pattern;
        $status = @preg_match($this->_pattern, "Test");

        if (false === $status)
		{
            throw new My_plugin_validator_exception("Internal error while using the pattern '$this->_pattern'");
        }
        return $this;
    }

    public function is_valid($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value))
		{
            $this->_error(self::INVALID, self::ERROR_REGEX_INVALID);
        }

        $this->set_value($value);

        $status = @preg_match($this->_pattern, $value);

        if (false === $status)
		{
            $this->_error(self::ERROROUS, self::ERROR_REGEX_ERROR);
        }

        if (!$status)
		{
            $this->_error(self::NOT_MATCH, self::ERROR_REGEX_NOT_MATCH);
        }

		if (sizeof($this->getErrors()) == 0)
			return true;
		else
			return false;
    }
}

?>
