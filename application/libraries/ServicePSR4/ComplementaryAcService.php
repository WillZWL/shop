<?php
namespace ESG\Panther\Service;
use ESG\Panther\Dao\ProductDao;
use ESG\Panther\Dao\ProductComplementaryAccDao;


class ComplementaryAccService extends Base_service
{
    public $accessoryCatidArr;
    private $productDao;

    public function __construct()
    {
        parent::__construct();
        $this->setProductDao(new ProductDao());
        $this->setComplementaryAccDao(new ProductComplementaryAccDao());

        // sets the category id of complementary accessory
        $this->setAccessoryCatidArr();
    }

    public function setComplementaryAccDao(Base_dao $dao)
    {
        $this->complementaryAccDao = $dao;
    }

    private function setAccessoryCatidArr()
    {
        $this->accessoryCatidArr = $this->getAccessoryCatidArr();
    }

    public function getAccessoryCatidArr()
    {
        $accessoryCatidArr = $this->getComplementaryAccDao()->getAccessoryCatidArr();
        return $accessoryCatidArr;
    }

    public function getComplementaryAccDao()
    {
        return $this->complementaryAccDao;
    }

    public function getProductDao()
    {
        return $this->productDao;
    }

    public function setProductDao(Base_dao $dao)
    {
        $this->productDao = $dao;
    }

    public function getMappedAccListWithName($where = array(), $option = array(), $active = true)
    {
        return $this->getComplementaryAccDao()->getMappedAccListWithName($where, $option, $active);
    }

    public function check_cat($sku = "", $is_ca = true)
    {
        $ret = $this->getComplementaryAccDao()->checkCat($sku, $is_ca);
        return $ret;
    }
}