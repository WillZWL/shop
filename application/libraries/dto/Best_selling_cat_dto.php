<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Best_selling_cat_dto extends Base_dto
{
    private $id;
    private $name;
    private $parent_cat_id;
    private $level;
    private $add_colour_name;
    private $priority;
    private $lang_id;
    private $image;
    private $flash;
    private $text;
    private $latest_news;
    private $status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

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

    public function get_parent_cat_id()
    {
        return $this->parent_cat_id;
    }

    public function set_parent_cat_id($value)
    {
        $this->parent_cat_id = $value;
    }

    public function get_level()
    {
        return $this->level;
    }

    public function set_level($value)
    {
        $this->level = $value;
    }

    public function get_add_colour_name()
    {
        return $this->add_colour_name;
    }

    public function set_add_colour_name($value)
    {
        $this->add_colour_name = $value;
    }

    public function get_priority()
    {
        return $this->priority;
    }

    public function set_priority($value)
    {
        $this->priority = $value;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
    }

    public function get_flash()
    {
        return $this->flash;
    }

    public function set_flash($value)
    {
        $this->flash = $value;
    }

    public function get_text()
    {
        return $this->text;
    }

    public function set_text($value)
    {
        $this->text = $value;
    }

    public function get_latest_news()
    {
        return $this->latest_news;
    }

    public function set_latest_news($value)
    {
        $this->latest_news = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
    }
}

?>