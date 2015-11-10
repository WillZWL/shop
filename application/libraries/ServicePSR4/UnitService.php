<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\UnitDao;
use ESG\Panther\Dao\UnitTypeDao;

class UnitService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new UnitDao);
        $this->setUnitTypeDao(new UnitTypeDao);
    }

    public function setUnitTypeDao($dao)
    {
        $this->ut_dao = $dao;
    }

    public function getUnitList($where, $option)
    {
        return $this->getDao()->getList($where, $option);
    }

    public function getUnitTypeList($where, $option)
    {
        return $this->getUnitTypeDao()->getList($where, $option);
    }

    public function getUnitTypeDao()
    {
        return $this->ut_dao;
    }
}


