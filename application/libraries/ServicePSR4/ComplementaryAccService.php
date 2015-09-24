<?php
namespace ESG\Panther\Service;

class ComplementaryAccService extends BaseService
{
    public $accessoryCatidArr;
    private $productDao;

    public function __construct()
    {
        parent::__construct();
        // sets the category id of complementary accessory
        $this->setAccessoryCatidArr();
    }

    public function setComplementaryAccDao($dao)
    {
        $this->complementaryAccDao = $dao;
    }

    public function setAccessoryCatidArr()
    {
        $this->accessoryCatidArr = $this->getAccessoryCatidArr();
    }

    public function getAccessoryCatidArr()
    {
        $accessoryCatidArr = $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr();
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

    public function setProductDao()
    {
        $this->productDao = $dao;
    }

    public function getMappedAccListWithName($where = array(), $option = array(), $active = true)
    {
        return $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where, $option, $active);
    }

    public function check_cat($sku = "", $is_ca = true)
    {
        $ret = $this->getDao('ProductComplementaryAcc')->checkCat($sku, $is_ca);
        return $ret;
    }
}