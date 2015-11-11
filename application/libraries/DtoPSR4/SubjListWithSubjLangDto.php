<?php
class SubjListWithSubjLangDto
{
    private $subject;
    private $subject_description;
    private $subject_value;
    private $subkey;
    private $subkey_description;
    private $subkey_value;
    private $lang_id;
    private $subkey_value_w_lang;

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubjectDescription($subject_description)
    {
        $this->subject_description = $subject_description;
    }

    public function getSubjectDescription()
    {
        return $this->subject_description;
    }

    public function setSubjectValue($subject_value)
    {
        $this->subject_value = $subject_value;
    }

    public function getSubjectValue()
    {
        return $this->subject_value;
    }

    public function setSubkey($subkey)
    {
        $this->subkey = $subkey;
    }

    public function getSubkey()
    {
        return $this->subkey;
    }

    public function setSubkeyDescription($subkey_description)
    {
        $this->subkey_description = $subkey_description;
    }

    public function getSubkeyDescription()
    {
        return $this->subkey_description;
    }

    public function setSubkeyValue($subkey_value)
    {
        $this->subkey_value = $subkey_value;
    }

    public function getSubkeyValue()
    {
        return $this->subkey_value;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setSubkeyValueWLang($subkey_value_w_lang)
    {
        $this->subkey_value_w_lang = $subkey_value_w_lang;
    }

    public function getSubkeyValueWLang()
    {
        return $this->subkey_value_w_lang;
    }

}
