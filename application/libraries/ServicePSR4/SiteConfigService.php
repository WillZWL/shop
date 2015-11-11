<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\SiteConfigDao;

class SiteConfigService extends BaseService
{
    public function __construct()
    {
        $this->setDao(new SiteConfigDao);
    }
}
