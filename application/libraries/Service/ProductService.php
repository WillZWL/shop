<?php
namespace AtomV2\Service;

use AtomV2\Dao\ProductDao;

class ProductService extends BaseService
{

    public function __construct()
    {
        $this->setDao(new ProductDao);
    }

    public function getHomeProduct($where, $option)
    {
        return $this->getDao()->getHomeProduct($where, $option);
    }
}
