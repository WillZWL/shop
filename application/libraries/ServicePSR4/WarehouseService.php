<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\WarehouseDao;

class WarehouseService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new WarehouseDao);
    }

}
