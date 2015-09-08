<?php
namespace AtomV2\Service;

use AtomV2\Dao\CustomClassificationDao;
use AtomV2\Dao\CustomClassificationMappingDao;
use AtomV2\Dao\ProductCustomClassificationDao;

class CustomClassService extends BaseService
{

    function __construct()
    {
        parent::__construct();
        $this->setDao(new CustomClassificationDao);
        $this->setProductCustomClassificationDao(new ProductCustomClassificationDao);
        $this->setCustomClassificationMappingDao(new CustomClassificationMappingDao);
    }

    public function setProductCustomClassificationDao($dao)
    {
        $this->pcc_dao = $dao;
    }

    public function setCustomClassificationMappingDao($dao)
    {
        $this->ccm_dao = $dao;
    }

    public function getOption($where = [])
    {
        return $this->getDao()->getOption($where);
    }

    public function getProductCustomClass($where = [])
    {
        return $this->getProductCustomClassificationDao()->get($where);
    }

    public function getProductCustomClassificationDao()
    {
        return $this->pcc_dao;
    }

    public function updateProductCustomClass($obj)
    {
        return $this->getProductCustomClassificationDao()->update($obj);
    }

    public function includeProductCustomClass_vo()
    {
        return $this->getProductCustomClassificationDao()->get();
    }

    public function addProductCustomClass($obj)
    {
        return $this->getProductCustomClassificationDao()->insert($obj);
    }

    public function getProductCustomClassList($where = [], $option = [])
    {
        $data["pcclist"] = $this->getProductCustomClassificationDao()->getProductCustomClassList($where, $option);
        $option["limit"] = -1;
        $allRecord = $this->getProductCustomClassificationDao()->getProductCustomClassList($where, $option);
        $data["total"] = count((array) $allRecord);
        return $data;
    }

    public function getCustomClassMappingList($where = [], $option = [])
    {
        $data["pcclist"] = $this->getCustomClassificationMappingDao()->getList($where, $option);
        $data["total"] = $this->getCustomClassificationMappingDao()->getNumRows($where);
        return $data;
    }

    public function getCustomClassificationMappingDao()
    {
        return $this->ccm_dao;
    }

    public function getFullProductCustomClassBySku($where = [], $option = [])
    {
        return $this->getProductCustomClassificationDao()->getAllProductCustomClassList($where, $option);
    }

    public function getHsBySubcatAndCountry($where = [], $option = [])
    {
        return $this->getCustomClassificationMappingDao()->getHsBySubcatAndCountry($where, $option);
    }

    public function update($obj)
    {
        return $this->getDao()->update($obj);
    }

    public function insert($obj)
    {
        return $this->getDao()->insert($obj);
    }
}


