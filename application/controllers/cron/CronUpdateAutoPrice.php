<?php

class CronUpdateAutoPrice extends MY_Controller
{
    private $appId = 'CRN0039';

    public function updateAutoPrice()
    {
        $price_obj_list = $this->sc['Price']->getDao('Price')->getList(['auto_price' => 'Y'], ['limit' => -1]);

        foreach ($price_obj_list as $price_obj) {
            $resutl = json_decode($this->sc['Price']->getProfitMarginJson($price_obj->getPlatformId(), $price_obj->getSku()));
            $auto_price = $resutl->get_price;
            $profit = $resutl->get_profit;
            $margin = $resutl->get_margin;

            $price_obj->setPrice($auto_price);

            $this->sc['Price']->getDao('Price')->update($price_obj);
            $this->sc['PriceMargin']->refreshProfitAndMargin($price_obj->getPlatformId(), $price_obj->getSku());
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
