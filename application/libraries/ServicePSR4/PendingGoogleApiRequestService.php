<?php
namespace ESG\Panther\Service;

class PendingGoogleApiRequestService extends BaseService
{
    public function __construct() {
    }

    public function insertGoogleShoppingDataForProductUpdateInAllPlatform($skus = []) {
        $siteObj = $this->getService("SiteConfig")->getDao("SiteConfig")->get(["((api_implemented >> 0) & 1) = 1" => null]);
        foreach ($siteObj as $site) {
            $this->getDao("PendingGoogleApiRequest")->insertGoogleShoppingDataForProductUpdate($siteObj->getPlatform(), $sku);
        }
    }
}