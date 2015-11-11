<?php
include_once 'Base_vo.php';

class Customer_service_info_vo extends Base_vo
{

    //class variable
    private $id;
    private $lang_id;
    private $platform_id;
    private $type = 'WEBSITE';
    private $title;
    private $content;
    private $short_text;
    private $long_text;
    private $short_text_status = '1';
    private $long_text_status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "id";

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

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($value)
    {
        $this->type = $value;
        return $this;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function set_title($value)
    {
        $this->title = $value;
        return $this;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function set_content($value)
    {
        $this->content = $value;
        return $this;
    }

    public function get_short_text()
    {
        return $this->short_text;
    }

    public function set_short_text($value)
    {
        $this->short_text = $value;
        return $this;
    }

    public function get_long_text()
    {
        return $this->long_text;
    }

    public function set_long_text($value)
    {
        $this->long_text = $value;
        return $this;
    }

    public function get_short_text_status()
    {
        return $this->short_text_status;
    }

    public function set_short_text_status($value)
    {
        $this->short_text_status = $value;
        return $this;
    }

    public function get_long_text_status()
    {
        return $this->long_text_status;
    }

    public function set_long_text_status($value)
    {
        $this->long_text_status = $value;
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