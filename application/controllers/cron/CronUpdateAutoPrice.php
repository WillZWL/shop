<?php

class CronUpdateAutoPrice extends MY_Controller
{
    private $appId = 'CRN0039';

    public function updateAutoPrice($platform_id = '')
    {
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
        set_time_limit(1800);
        ini_set('memory_limit', '1024M');
        $price_obj_list = $this->sc['Price']->getDao('Price')->getList(['auto_price' => 'Y', 'listing_status' => 'L', 'platform_id' => $platform_id], ['orderby'=>'modify_on asc', 'limit' => '500']);
        foreach ($price_obj_list as $price_obj) {
            // $time_start = microtime();
            $resutl = json_decode($this->sc['Price']->getProfitMarginJson($price_obj->getPlatformId(), $price_obj->getSku()));
            $auto_price = $resutl->get_price;
            $profit = $resutl->get_profit;
            $margin = $resutl->get_margin;

            $price_obj->setPrice($auto_price);
            $this->sc['Price']->getDao('Price')->update($price_obj);
            $this->sc['PriceMargin']->refreshProfitAndMargin($price_obj->getPlatformId(), $price_obj->getSku());
            $this->sc["PriceUpdateTrigger"]->triggerGoogleApi($price_obj->getSku(), $price_obj->getPlatformId());
            // $time_end = microtime();
            // error_log($time_end - $time_start);
        }
        unset($price_obj_list);
    }

    public function getSpendTime($time_start, $time_end)
    {
        list($t1, $t2) = explode(' ', $time_start);
        list($t3, $t4) = explode(' ', $time_end);
        $time = (floatval($t3) + floatval($t4)) - (floatval($t1) + floatval($t2));
        error_log("Pre SKU Spend :".$time);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
