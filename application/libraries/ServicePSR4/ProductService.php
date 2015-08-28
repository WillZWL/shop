<?php
namespace AtomV2\Service;

use AtomV2\Dao\ProductDao;
use AtomV2\Dao\ProductTypeDao;

class ProductService extends BaseService
{

    public function __construct()
    {
        $this->setDao(new ProductDao);
        $this->setProductTypeDao(new ProductTypeDao);
    }

    public function getHomeProduct($where, $option)
    {
        return $this->getDao()->getHomeProduct($where, $option);
    }

    public function getListedProductList($platform_id = 'WSGB', $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao()->getListedProductList($platform_id, $classname);
    }

    public function getProductWPriceInfo($platform_id = 'WEBGB', $sku = "", $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao()->getProductWPriceInfo($platform_id, $sku, $classname);
    }

    public function getProductWMarginReqUpdate($where = [], $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao()->getProductWMarginReqUpdate($where, $classname);
    }

    public function setProductTypeDao($dao)
    {
        $this->productTypeDao = $dao;
    }

    public function getProductTypeDao()
    {
        return $this->productTypeDao;
    }
}
