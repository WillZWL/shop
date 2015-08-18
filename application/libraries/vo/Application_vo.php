<?php
include_once 'Base_vo.php';

class Application_vo extends Base_vo
{

    //class variable
    private $id;
    private $app_name;
    private $parent_app_id;
    private $description;
    private $display_order = '0';
    private $status = '0';
    private $display_row = '0';
    private $url;
    private $app_group_id;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

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

    public function get_app_name()
    {
        return $this->app_name;
    }

    public function set_app_name($value)
    {
        $this->app_name = $value;
        return $this;
    }

    public function get_parent_app_id()
    {
        return $this->parent_app_id;
    }

    public function set_parent_app_id($value)
    {
        $this->parent_app_id = $value;
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

    public function get_display_order()
    {
        return $this->display_order;
    }

    public function set_display_order($value)
    {
        $this->display_order = $value;
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

    public function get_display_row()
    {
        return $this->display_row;
    }

    public function set_display_row($value)
    {
        $this->display_row = $value;
        return $this;
    }

    public function get_url()
    {
        return $this->url;
    }

    public function set_url($value)
    {
        $this->url = $value;
        return $this;
    }

    public function get_app_group_id()
    {
        return $this->app_group_id;
    }

    public function set_app_group_id($value)
    {
        $this->app_group_id = $value;
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