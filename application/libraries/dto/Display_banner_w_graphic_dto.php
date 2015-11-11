<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Display_banner_w_graphic_dto extends Base_dto
{

    //class variable
    private $id;
    private $display_banner_config_id;
    private $display_id;
    private $position_id;
    private $slide_id;
    private $country_id;
    private $lang_id;
    private $time_interval;
    private $image_id;
    private $flash_id;
    private $height;
    private $width;
    private $usage;
    private $link_type;
    private $link;
    private $status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $graphic_id;
    private $graphic_type;
    private $graphic_location;
    private $graphic_file;
    private $banner_type;
    private $priority;

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_display_banner_config_id()
    {
        return $this->display_banner_config_id;
    }

    public function set_display_banner_config_id($value)
    {
        $this->display_banner_config_id = $value;
    }

    public function get_display_id()
    {
        return $this->display_id;
    }

    public function set_display_id($value)
    {
        $this->display_id = $value;
    }

    public function get_position_id()
    {
        return $this->position_id;
    }

    public function set_position_id($value)
    {
        $this->position_id = $value;
    }

    public function get_slide_id()
    {
        return $this->slide_id;
    }

    public function set_slide_id($value)
    {
        $this->slide_id = $value;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
    }

    public function get_time_interval()
    {
        return $this->time_interval;
    }

    public function set_time_interval($value)
    {
        $this->time_interval = $value;
    }

    public function get_image_id()
    {
        return $this->image_id;
    }

    public function set_image_id($value)
    {
        $this->image_id = $value;
    }

    public function get_flash_id()
    {
        return $this->flash_id;
    }

    public function set_flash_id($value)
    {
        $this->flash_id = $value;
    }

    public function get_height()
    {
        return $this->height;
    }

    public function set_height($value)
    {
        $this->height = $value;
    }

    public function get_width()
    {
        return $this->width;
    }

    public function set_width($value)
    {
        $this->width = $value;
    }

    public function get_usage()
    {
        return $this->usage;
    }

    public function set_usage($value)
    {
        $this->usage = $value;
    }

    public function get_link_type()
    {
        return $this->link_type;
    }

    public function set_link_type($value)
    {
        $this->link_type = $value;
    }

    public function get_link()
    {
        return $this->link;
    }

    public function set_link($value)
    {
        $this->link = $value;
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

    public function get_graphic_id()
    {
        return $this->graphic_id;
    }

    public function set_graphic_id($value)
    {
        $this->graphic_id = $value;
    }

    public function get_graphic_type()
    {
        return $this->graphic_type;
    }

    public function set_graphic_type($value)
    {
        $this->graphic_type = $value;
    }

    public function get_graphic_location()
    {
        return $this->graphic_location;
    }

    public function set_graphic_location($value)
    {
        $this->graphic_location = $value;
    }

    public function get_graphic_file()
    {
        return $this->graphic_file;
    }

    public function set_graphic_file($value)
    {
        $this->graphic_file = $value;
    }

    public function get_banner_type()
    {
        return $this->banner_type;
    }

    public function set_banner_type($value)
    {
        $this->banner_type = $value;
    }

    public function get_priority()
    {
        return $this->priority;
    }

    public function set_priority($value)
    {
        $this->priority = $value;
    }
}


