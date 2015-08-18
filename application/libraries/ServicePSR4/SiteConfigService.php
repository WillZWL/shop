<?php
namespace AtomV2\Service;

use AtomV2\Dao\SiteConfigDao;

class SiteConfigService extends BaseService
{
    public function __construct()
    {
        $this->setDao(new SiteConfigDao);
    }
}
