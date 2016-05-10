<?php
class SubjectDomainDetailVo extends \BaseVo
{
    private $subject;
    private $subkey;
    private $description;
    private $value;

    protected $primary_key = ['subject', 'subkey'];
    protected $increment_field = '';

    public function setSubject($subject)
    {
        if ($subject !== null) {
            $this->subject = $subject;
        }
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubkey($subkey)
    {
        if ($subkey !== null) {
            $this->subkey = $subkey;
        }
    }

    public function getSubkey()
    {
        return $this->subkey;
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

}
