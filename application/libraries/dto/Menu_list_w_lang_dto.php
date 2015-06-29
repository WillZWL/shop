<?php
include_once 'Base_dto.php';

class Menu_list_w_lang_dto extends Base_dto
{
    //class variable
    private $id;
    private $name;
    private $lang_id;
    private $level;
    private $parent_cat_id;

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
    }

    public function get_level()
    {
        return $this->level;
    }

    public function set_level($value)
    {
        $this->level = $value;
    }

    public function get_parent_cat_id()
    {
        return $this->parent_cat_id;
    }

    public function set_parent_cat_id($value)
    {
        $this->parent_cat_id = $value;
    }
}
?>