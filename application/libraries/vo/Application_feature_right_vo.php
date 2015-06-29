<?php
include_once "base_vo.php";

class Application_feature_right_vo extends Base_vo
{

    private $app_id;

    //class variable
    private $app_feature_id;
    private $role_id;
    private $status = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $primary_key = array("app_id", "app_feature_id");

    //primary key
    private $increment_field = "";

    //auo increment

    public function __construct()
    {
        parent::Base_vo();
    }

    //instance method

    public function get_app_id()
    {
        return $this->app_id;
    }

    public function set_app_id($value)
    {
        $this->app_id = $value;
        return $this;
    }

    public function get_app_feature_id()
    {
        return $this->app_feature_id;
    }

    public function set_app_feature_id($value)
    {
        $this->app_feature_id = $value;
        return $this;
    }

    public function get_role_id()
    {
        return $this->role_id;
    }

    public function set_role_id($value)
    {
        $this->role_id = $value;
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


