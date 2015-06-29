<?php
include_once 'Base_vo.php';

class Display_category_banner_vo extends Base_vo
{

    //class variable
    private $id;
    private $catid;
    private $display_banner_config_id;
    private $display_id;
    private $position_id = '1';
    private $slide_id = '0';
    private $country_id;
    private $lang_id;
    private $time_interval = '0';
    private $image_id;
    private $flash_id;
    private $lytebox_content;
    private $height;
    private $width;
    private $usage = 'PV';
    private $link_type = 'E';
    private $link;
    private $priority = '9';
    private $status = '1';
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

    public function get_catid()
    {
        return $this->catid;
    }

    public function set_catid($value)
    {
        $this->catid = $value;
        return $this;
    }

    public function get_display_banner_config_id()
    {
        return $this->display_banner_config_id;
    }

    public function set_display_banner_config_id($value)
    {
        $this->display_banner_config_id = $value;
        return $this;
    }

    public function get_display_id()
    {
        return $this->display_id;
    }

    public function set_display_id($value)
    {
        $this->display_id = $value;
        return $this;
    }

    public function get_position_id()
    {
        return $this->position_id;
    }

    public function set_position_id($value)
    {
        $this->position_id = $value;
        return $this;
    }

    public function get_slide_id()
    {
        return $this->slide_id;
    }

    public function set_slide_id($value)
    {
        $this->slide_id = $value;
        return $this;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
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

    public function get_time_interval()
    {
        return $this->time_interval;
    }

    public function set_time_interval($value)
    {
        $this->time_interval = $value;
        return $this;
    }

    public function get_image_id()
    {
        return $this->image_id;
    }

    public function set_image_id($value)
    {
        $this->image_id = $value;
        return $this;
    }

    public function get_flash_id()
    {
        return $this->flash_id;
    }

    public function set_flash_id($value)
    {
        $this->flash_id = $value;
        return $this;
    }

    public function get_lytebox_content()
    {
        return $this->lytebox_content;
    }

    public function set_lytebox_content($value)
    {
        $this->lytebox_content = $value;
        return $this;
    }

    public function get_height()
    {
        return $this->height;
    }

    public function set_height($value)
    {
        $this->height = $value;
        return $this;
    }

    public function get_width()
    {
        return $this->width;
    }

    public function set_width($value)
    {
        $this->width = $value;
        return $this;
    }

    public function get_usage()
    {
        return $this->usage;
    }

    public function set_usage($value)
    {
        $this->usage = $value;
        return $this;
    }

    public function get_link_type()
    {
        return $this->link_type;
    }

    public function set_link_type($value)
    {
        $this->link_type = $value;
        return $this;
    }

    public function get_link()
    {
        return $this->link;
    }

    public function set_link($value)
    {
        $this->link = $value;
        return $this;
    }

    public function get_priority()
    {
        return $this->priority;
    }

    public function set_priority($value)
    {
        $this->priority = $value;
        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
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