<?php

class CronUpdateSeller extends MY_Controller
{
    private $appId = "CRN0010";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($type = 'BS')
    {
        return $this->sc['LandpageListing']->cronUpdateSeller($type);
    }

    public function getAppId()
    {
        return $this->appId;
    }

}