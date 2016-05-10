<?php
class SubjectDomainDetailLabelVo extends \BaseVo
{
    private $id;
    private $subject;
    private $subkey;
    private $lang_id;
    private $value;

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

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

    public function setLangId($lang_id)
    {
        if ($lang_id !== null) {
            $this->lang_id = $lang_id;
        }
    }

    public function getLangId()
    {
        return $this->lang_id;
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
