<?php

class CronUpdatePriceMargin extends MY_Controller
{
    private $appId = 'CRN0030';
    const SCHEDULE_ID= "REFRESH_MARGIN";
    public function updateMargin($platform_id = '', $sku = '')
    {
        error_log(__METHOD__ . ":" . __LINE__ . ", Memory:" . memory_get_usage());
        set_time_limit(1200);
        ini_set('memory_limit', '1024M');
        $id = self::SCHEDULE_ID;
        $current_time = date("Y-m-d H:i:s");
        if ($platform_id === '') {
            $this->sc['PriceMargin']->getDao('PriceMargin')->db->save_queries = false;
            $platform_list = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList();
            foreach ($platform_list as $platform_obj) {
                $platform_id = $platform_obj->getSellingPlatformId();
                $command = "sh -c \"date >> /var/log/php/cron.log; echo 'php index.php cron/CronUpdatePriceMargin/updateMargin/$platform_id'>>/var/log/php/cron.log;cd /var/www/html/panther/admincentre/;/usr/bin/php index.php cron/CronUpdatePriceMargin/updateMargin/$platform_id>>/var/log/php/cron.log\"";
                error_log(__METHOD__ . ":" . __LINE__ . "Update PriceMargin ". $platform_id);
                exec($command);
                // $this->sc['PriceMargin']->refreshProfitAndMargin($platform_obj->getSellingPlatformId(););
            }
        } else {
            if ($sku === '') {
                $this->sc['PriceMargin']->refreshProfitAndMargin($platform_id);
            } else {
                $this->sc['PriceMargin']->refreshProfitAndMargin($platform_id, $sku);
            }
        }
        error_log(__METHOD__ . ":" . __LINE__ . ", Memory:" . memory_get_usage());
        $this->sc['PriceMargin']->updatLastTime($id, $current_time);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
