<?php
class SubjectDomainVo extends \BaseVo
{
    private $subject;
    private $description;
    private $value;

    protected $primary_key = ['subject'];
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
