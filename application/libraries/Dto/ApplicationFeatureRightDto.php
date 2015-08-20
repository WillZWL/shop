<?php
class ApplicationFeatureRightDto
{
    protected $app_feature_id;
    protected $feature_name;
    protected $status;

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
