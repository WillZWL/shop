<?php
namespace AtomV2\Service;

use AtomV2\Dao\PriceDao;

class PriceService extends BaseService
{
    public function __construct()
    {
        $this->setDao(new PriceDao);
    }
}
