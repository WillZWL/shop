<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Role_app_dto extends Base_dto
{

    //class variable
    private $id;
    private $app_name;
    private $url;
    private $parent_app_id;
    private $description;
    private $display_order;
    private $role_id;

    function __construct()
    {
        parent::__construct();
    }

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_url()
    {
        return $this->url;
    }

    public function set_url($value)
    {
        $this->url = $value;
    }

    public function get_app_name()
    {
        return $this->app_name;
    }

    public function set_app_name($value)
    {
        $this->app_name = $value;
    }

    public function get_parent_app_id()
    {
        return $this->parent_app_id;
    }

    public function set_parent_app_id($value)
    {
        $this->parent_app_id = $value;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($value)
    {
        $this->description = $value;
    }

    public function get_display_order()
    {
        return $this->display_order;
    }

    public function set_display_order($value)
    {
        $this->display_order = $value;
    }

    public function get_role_id()
    {
        return $this->role_id;
    }

    public function set_role_id($value)
    {
        $this->role_id = $value;
    }

}

/* End of file role_app_dto.php */
/* Location: ./system/application/libraries/dto/role_app_dto.php */