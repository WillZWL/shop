<?php

class CronUpdatePriceMargin extends MY_Controller
{
    private $appId = 'CRN0030';

    public function __construct()
    {
        parent::__construct();
        set_time_limit(900);
    }

    public function processGoogleApiRequest() {
        do {
            $this->sc['GoogleShopping']->sendRequestToGoogle();
            sleep(300);
        } while (date("H") != "15");
    }

    public function updateMargin($platform_id = '', $sku = '')
    {
        if ($platform_id === '') {
            $platform_list = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList();
            foreach ($platform_list as $platform_obj) {
                $this->sc['Price']->refreshMargin($platform_obj->getSellingPlatformId());
            }
        } else {
            if ($sku === '') {
                $this->sc['Price']->refreshMargin($platform_id);
            } else {
                $this->sc['Price']->refreshMargin($platform_id, $sku);
            }
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
