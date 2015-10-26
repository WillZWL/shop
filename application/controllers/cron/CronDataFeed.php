<?php

class CronDataFeed extends MY_Controller
{
    private $appId = "CRN0009";

    public function __construct()
    {
        parent::__construct();
    }

    public function genSearchspringProductFeed($platformId = 'WEBGB')
    {
        $this->sc['dataFeedModel']->genSearchspringProductFeed(strtoupper($platformId));
    }

    public function genSearchspringAllProductFeed()
    {
        $allPlatform = $this->sc['dataFeedModel']->getAllActivePlatform();
        foreach($allPlatform as $platformObj) {
            $this->sc['dataFeedModel']->genSearchspringProductFeed($platformObj->getSellingPlatformId());
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}


