<?php
include_once "Base_vo.php";

class Ra_group_vo extends Base_vo
{
    private $group_id;

    //class variable
    private $group_name;
    private $status = '1';
    private $warranty = '0';
    private $ignore_qty_bundle = 0;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $primary_key = array("group_id");

    //primary key
    private $increment_field = "group_id";

    //instance method

    public function get_group_id()
    {
        return $this->group_id;
    }

    public function set_group_id($value)
    {
        $this->group_id = $value;
        return $this;
    }

    public function get_group_name()
    {
        return $this->group_name;
    }

    public function set_group_name($value)
    {
        $this->group_name = $value;
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

    public function get_warranty()
    {
        return $this->warranty;
    }

    public function set_warranty($value)
    {
        $this->warranty = $value;
    }

    public function get_ignore_qty_bundle()
    {
        return $this->ignore_qty_bundle;
    }

    public function set_ignore_qty_bundle($ignore_qty_bundle)
    {
        $this->ignore_qty_bundle = $ignore_qty_bundle;
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
