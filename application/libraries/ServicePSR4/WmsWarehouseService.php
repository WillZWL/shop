<?php
namespace AtomV2\Service;

use AtomV2\Dao\WmsWarehouseDao;

class WmsWarehouseService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new WmsWarehouseDao);
    }

    public function get_retailer_list()
    {
        return $this->getDao()->getList(['type' => 'R'], ['orderby' => 'warehouse_id', 'limit' => -1]);
    }

    public function get_warehouse_list()
    {
        return $this->getDao()->getList(['type' => 'W'], ['orderby' => 'warehouse_id', 'limit' => -1]);
    }
}


