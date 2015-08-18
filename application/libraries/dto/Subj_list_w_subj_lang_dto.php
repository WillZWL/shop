<?php
include_once 'Base_dto.php';

class Subj_list_w_subj_lang_dto extends Base_dto
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
    public function get_subject()
    {
        return $this->subject;
    }

    public function set_subject($value)
    {
        $this->subject = $value;
    }

    public function get_subject_description()
    {
        return $this->subject_description;
    }

    public function set_subject_description($value)
    {
        $this->subject_description = $value;
    }

    public function get_subject_value()
    {
        return $this->subject_value;
    }

    public function set_subject_value($value)
    {
        $this->subject_value = $value;
    }

    public function get_subkey()
    {
        return $this->subkey;
    }

    public function set_subkey($value)
    {
        $this->subkey = $value;
    }

    public function get_subkey_description()
    {
        return $this->subkey_description;
    }

    public function set_subkey_description($value)
    {
        $this->subkey_description = $value;
    }

    public function get_subkey_value()
    {
        return $this->subkey_value;
    }

    public function set_subkey_value($value)
    {
        $this->subkey_value = $value;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
    }

    public function get_subkey_value_w_lang()
    {
        return $this->subkey_value_w_lang;
    }

    public function set_subkey_value_w_lang($value)
    {
        $this->subkey_value_w_lang = $value;
    }
}

?>