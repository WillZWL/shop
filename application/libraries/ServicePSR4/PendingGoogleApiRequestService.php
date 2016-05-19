<?php
namespace ESG\Panther\Service;

class PendingGoogleApiRequestService extends BaseService
{
    public function __construct() {
    }

    public function insertGoogleShoppingDataForProductUpdateInAllPlatform($skus = []) {
        $siteObj = $this->getService("SiteConfig")->getDao("SiteConfig")->getList(["(api_implemented > 0)" => null], ["limit" => -1]);
        foreach ($siteObj as $site) {
            if ((($site->getApiImplemented() >> 1) & 1) == 1)
                $this->getDao("PendingGoogleApiRequest")->insertGoogleShoppingDataForProductUpdate($siteObj->getPlatform(), $sku);
        }
    }
}