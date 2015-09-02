<?php
namespace AtomV2\Service;

use AtomV2\Dao\InventoryDao;

class InventoryService extends BaseService
{
    private $VProdInventoryDao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new InventoryDao);
    }

    public function getInventory($where = array())
    {
        return $this->getDao()->get_inventory_list($where);
    }

    public function getStockValuation($where = array())
    {
        return $this->getDao()->getStockValuation($where);
    }

    public function setSurplusQuantity($sku, $qty)
    {
        return $this->getDao()->setSurplusQuantity($sku, $qty);
    }
}
