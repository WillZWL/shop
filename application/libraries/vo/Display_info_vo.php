<?php
include_once 'Base_vo.php';

class Display_info_vo extends Base_vo
{

    //class variable
    private $display_id;
    private $lang_id;
    private $page_title;
    private $meta_title;
    private $meta_description;
    private $meta_keyword;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("display_id", "lang_id");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_display_id()
    {
        return $this->display_id;
    }

    public function set_display_id($value)
    {
        $this->display_id = $value;
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

    public function get_page_title()
    {
        return $this->page_title;
    }

    public function set_page_title($value)
    {
        $this->page_title = $value;
        return $this;
    }

    public function get_meta_title()
    {
        return $this->meta_title;
    }

    public function set_meta_title($value)
    {
        $this->meta_title = $value;
        return $this;
    }

    public function get_meta_description()
    {
        return $this->meta_description;
    }

    public function set_meta_description($value)
    {
        $this->meta_description = $value;
        return $this;
    }

    public function get_meta_keyword()
    {
        return $this->meta_keyword;
    }

    public function set_meta_keyword($value)
    {
        $this->meta_keyword = $value;
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