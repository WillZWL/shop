<?php

class CronUpdateAutoPrice extends MY_Controller
{
    private $appId = 'CRN0039';

    public function updateAutoPrice($platform_id = '')
    {
        set_time_limit(1200);
        ini_set('memory_limit', '1024M');
        if ($platform_id === '') {
            $platform_list = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList();
            foreach ($platform_list as $platform_obj) {
                $platform_id = $platform_obj->getSellingPlatformId();
                $command = "sh -c \"date >> /var/log/php/cron.log; echo 'php index.php cron/CronUpdateAutoPrice/updateAutoPrice/$platform_id'>>/var/log/php/cron.log;cd /var/www/html/panther/admincentre/;/usr/bin/php index.php cron/CronUpdateAutoPrice/updateAutoPrice/$platform_id>>/var/log/php/cron.log\"";
                error_log(__METHOD__ . ": updateAutoPrice ". $platform_id);
                sleep(60);
                exec($command);
            }
        } else {
            $this->updatePlatformAutoPrice($platform_id);
        }
    }

    public function updatePlatformAutoPrice($platform_id = '')
    {
        $price_obj_list = $this->sc['Price']->getDao('Price')->getList(['auto_price' => 'Y', 'platform_id' => $platform_id], ['limit' => -1]);
        foreach ($price_obj_list as $price_obj) {
            $resutl = json_decode($this->sc['Price']->getProfitMarginJson($price_obj->getPlatformId(), $price_obj->getSku()));
            $auto_price = $resutl->get_price;
            $profit = $resutl->get_profit;
            $margin = $resutl->get_margin;

            $price_obj->setPrice($auto_price);

            $this->sc['Price']->getDao('Price')->update($price_obj);
            $this->sc['PriceMargin']->refreshProfitAndMargin($price_obj->getPlatformId(), $price_obj->getSku());
        }
        unset($price_obj_list);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
