<?php

class ApplicationGroupVo extends \BaseVo
{
    private $app_group_id;
    private $app_group_name;

    protected $primary_key = ['app_group_id'];
    protected $increment_field = 'app_group_id';

    public function setAppGroupId($app_group_id)
    {
        if ($app_group_id !== null) {
            $this->app_group_id = $app_group_id;
        }
    }

    public function getAppGroupId()
    {
        return $this->app_group_id;
    }

    public function setAppGroupName($app_group_name)
    {
        if ($app_group_name !== null) {
            $this->app_group_name = $app_group_name;
        }
    }

    public function getAppGroupName()
    {
        return $this->app_group_name;
    }

}
