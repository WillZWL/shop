<?php
include_once 'Base_vo.php';

class Template_vo extends Base_vo
{

    //class variable
    private $id;
    private $lang_id = 'en';
    private $name;
    private $description;
    private $tpl_file;
    private $tpl_alt_file;
    private $message_alt;
    private $message_html;
    private $subject;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id", "lang_id");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    public function get_message_alt()
    {
        return $this->message_alt;
    }

    public function set_message_alt($value)
    {
        $this->message_alt = $value;
        return $this;
    }

    public function get_message_html()
    {
        return $this->message_html;
    }

    public function set_message_html($value)
    {
        $this->message_html = $value;
        return $this;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
        return $this;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($value)
    {
        $this->description = $value;
        return $this;
    }

    public function get_tpl_file()
    {
        return $this->tpl_file;
    }

    public function set_tpl_file($value)
    {
        $this->tpl_file = $value;
        return $this;
    }

    public function get_tpl_alt_file()
    {
        return $this->tpl_alt_file;
    }

    public function set_tpl_alt_file($value)
    {
        $this->tpl_alt_file = $value;
        return $this;
    }

    public function get_subject()
    {
        return $this->subject;
    }

    public function set_subject($value)
    {
        $this->subject = $value;
        return $this;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

}
?>