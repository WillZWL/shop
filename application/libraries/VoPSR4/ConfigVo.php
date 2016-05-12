<?php

class ConfigVo extends \BaseVo
{
    private $variable;
    private $value;
    private $description;

    protected $primary_key = ['variable'];
    protected $increment_field = '';

    public function setVariable($variable)
    {
        if ($variable !== null) {
            $this->variable = $variable;
        }
    }

    public function getVariable()
    {
        return $this->variable;
    }

    public function setValue($value)
    {
        if ($value !== null) {
            $this->value = $value;
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

}
