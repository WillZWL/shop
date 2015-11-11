<?php
class SubjListWSubjLangDto
{
    //class variable
    private $subject;
    private $subject_description;
    private $subject_value;
    private $subkey;
    private $subkey_description;
    private $subkey_value;
    private $lang_id;
    private $subkey_value_w_lang;

    //instance method
    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($value)
    {
        $this->subject = $value;
    }

    public function getSubjectDescription()
    {
        return $this->subject_description;
    }

    public function setSubjectDescription($value)
    {
        $this->subject_description = $value;
    }

    public function getSubjectValue()
    {
        return $this->subject_value;
    }

    public function setSubjectValue($value)
    {
        $this->subject_value = $value;
    }

    public function getSubkey()
    {
        return $this->subkey;
    }

    public function setSubkey($value)
    {
        $this->subkey = $value;
    }

    public function getSubkeyDescription()
    {
        return $this->subkey_description;
    }

    public function setSubkeyDescription($value)
    {
        $this->subkey_description = $value;
    }

    public function getSubkeyValue()
    {
        return $this->subkey_value;
    }

    public function setSubkeyValue($value)
    {
        $this->subkey_value = $value;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setLangId($value)
    {
        $this->lang_id = $value;
    }

    public function getSubkeyValueWLang()
    {
        return $this->subkey_value_w_lang;
    }

    public function setSubkeyValueWLang($value)
    {
        $this->subkey_value_w_lang = $value;
    }
}
