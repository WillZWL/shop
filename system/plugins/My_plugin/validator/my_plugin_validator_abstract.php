<?php

include_once "my_plugin_validator_interface.php";
include_once "my_plugin_validator_exception.php";

abstract class My_plugin_validator_abstract implements My_plugin_validator_interface
{
	protected $_value;
	protected $_errors = array();
	protected $_error_code = array();
	protected $_messages = array();
    protected $_message_variables = array();
    protected $_message_templates = array();

	public function __construct($option = array())
	{
		
	}

	public function get_value()
	{
		return $this->_value;
	}

	public function set_value($value)
	{
		$this->_value = $value;
	}

    public function getErrorCodes()
    {
        return $this->_error_code;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function getMessages()
    {
        return $this->_messages;
    }

    protected function _error($messageKey, $error_code, $value = null)
    {
        if ($messageKey === null)
		{
            $keys = array_keys($this->_message_templates);
            $messageKey = current($keys);
        }

        if ($value === null)
		{
            $value = $this->_value;
        }
        $this->_errors[] = $messageKey;
		$this->_error_code[] = $error_code;
        $this->_messages[$messageKey] = $this->_createMessage($messageKey, $value);
		return $error_code;
    }

    protected function _createMessage($messageKey, $value)
    {
        if (!isset($this->_message_templates[$messageKey]))
		{
            return null;
        }

        $message = $this->_message_templates[$messageKey];

        if (is_object($value))
		{
            if (!in_array('__toString', get_class_methods($value)))
			{
                $value = get_class($value) . ' object';
            }
			else
			{
                $value = $value->__toString();
            }
        }
		else
		{
            $value = (string)$value;
        }

        $message = str_replace('%value%', (string) $value, $message);
        foreach ($this->_message_variables as $ident => $property)
		{
            $message = str_replace("%$ident%", (string) $this->$property, $message);
        }

        return $message;
    }
}

?>
