<?php
namespace AtomV2\Service;

use AtomV2\Dao\ProductDao;

class ProductService extends BaseService
{
    public function __construct()
    {
        $this->setDao(new ProductDao);
    }

    public function getLandPageSku($where, $option)
    {
        return $this->getDao()->getLandPageSku($where, $option);
    }

    public function getProductInfo($where = [], $option = [])
    {
        return $this->getDao()->getProductInfo($where, $option);
    }
}
