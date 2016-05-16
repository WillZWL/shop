<?php

class CronUpdatePriceMargin extends MY_Controller
{
    private $appId = 'CRN0030';

    public function updateMargin($platform_id = '', $sku = '')
    {
        set_time_limit(1200);
        ini_set('memory_limit', '1024M');
        if ($platform_id === '') {
            $this->sc['PriceMargin']->getDao('PriceMargin')->db->save_queries = false;
            $platform_list = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList();
            foreach ($platform_list as $platform_obj) {
                $this->sc['PriceMargin']->refreshProfitAndMargin($platform_obj->getSellingPlatformId());
            }
        } else {
            if ($sku === '') {
                $this->sc['PriceMargin']->refreshProfitAndMargin($platform_id);
            } else {
                $this->sc['PriceMargin']->refreshProfitAndMargin($platform_id, $sku);
            }
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
