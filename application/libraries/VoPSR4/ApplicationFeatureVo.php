<?php

class ApplicationFeatureVo extends \BaseVo
{
    private $app_feature_id;
    private $feature_name;
    private $status = '0';

    protected $primary_key = ['feature_name'];
    protected $increment_field = 'app_feature_id';

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

    public function setFeatureName($feature_name)
    {
        if ($feature_name !== null) {
            $this->feature_name = $feature_name;
        }
    }

    public function getFeatureName()
    {
        return $this->feature_name;
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
