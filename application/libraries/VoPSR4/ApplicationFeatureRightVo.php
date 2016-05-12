<?php

class ApplicationFeatureRightVo extends \BaseVo
{
    private $app_id;
    private $app_feature_id;
    private $role_id;
    private $status = '0';

    protected $primary_key = ['app_id', 'app_feature_id', 'role_id'];
    protected $increment_field = '';

    public function setAppId($app_id)
    {
        if ($app_id !== null) {
            $this->app_id = $app_id;
        }
    }

    public function getAppId()
    {
        return $this->app_id;
    }

    public function setAppFeatureId($app_feature_id)
    {
        if ($app_feature_id !== null) {
            $this->app_feature_id = $app_feature_id;
        }
    }

    public function getAppFeatureId()
    {
        return $this->app_feature_id;
    }

    public function setRoleId($role_id)
    {
        if ($role_id !== null) {
            $this->role_id = $role_id;
        }
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
