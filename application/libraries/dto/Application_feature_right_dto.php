<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Application_feature_right_dto extends Base_dto
{
    protected $app_feature_id;
    protected $feature_name;
    protected $status;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_app_feature_id()
    {
        return $this->app_feature_id;
    }

    public function set_app_feature_id($value)
    {
        $this->app_feature_id = $value;
    }

    public function get_feature_name()
    {
        return $this->feature_name;
    }

    public function set_feature_name($value)
    {
        $this->feature_name = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }
}
