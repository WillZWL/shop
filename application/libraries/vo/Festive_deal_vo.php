<?php
include_once 'Base_vo.php';

class Festive_deal_vo extends Base_vo
{

    //class variable
    private $id;
    private $link_name;
    private $display_name;
    private $display = 'Y';
    private $start_date = '0000-00-00 00:00:00';
    private $end_date = '0000-00-00 00:00:00';
    private $banner_file;
    private $banner_link;
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

    public function get_link_name()
    {
        return $this->link_name;
    }

    public function set_link_name($value)
    {
        $this->link_name = $value;
        return $this;
    }

    public function get_display_name()
    {
        return $this->display_name;
    }

    public function set_display_name($value)
    {
        $this->display_name = $value;
        return $this;
    }

    public function get_display()
    {
        return $this->display;
    }

    public function set_display($value)
    {
        $this->display = $value;
        return $this;
    }

    public function get_start_date()
    {
        return $this->start_date;
    }

    public function set_start_date($value)
    {
        $this->start_date = $value;
        return $this;
    }

    public function get_end_date()
    {
        return $this->end_date;
    }

    public function set_end_date($value)
    {
        $this->end_date = $value;
        return $this;
    }

    public function get_banner_file()
    {
        return $this->banner_file;
    }

    public function set_banner_file($value)
    {
        $this->banner_file = $value;
        return $this;
    }

    public function get_banner_link()
    {
        return $this->banner_link;
    }

    public function set_banner_link($value)
    {
        $this->banner_link = $value;
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