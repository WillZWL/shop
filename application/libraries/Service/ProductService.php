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

    public function getProductInfo($skuObjList)
    {
        foreach ($skuObjList as $skuObj) {
            $sku[] = $skuObj->getSku();
        }

        $this->getDao()->getProductInfo($sku);
    }
}
