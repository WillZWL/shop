<?php
namespace ESG\Panther\Service;

class PriceUpdateTriggerService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function triggerGoogleApi($sku = [], $platformId = "") {
        if ($sku) {
            $this->getService("PendingGoogleApiRequest")->getDao("PendingGoogleApiRequest")->insertGoogleShoppingDataForProductUpdate($platformId, $sku);
        }
    }
}