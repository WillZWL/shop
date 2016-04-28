<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CourierDao;
use ESG\Panther\Dao\CourierRegionDao;

class CourierService extends BaseService
{

    private $crcDao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new CourierDao);
        $this->setCrcDao(new CourierRegionDao);
    }

    public function getCrcDao()
    {
        return $this->crcDao;
    }

    public function setCrcDao($dao)
    {
        $this->crcDao = $dao;
    }

    public function saveCourierFeed($courier = "", $mawb = "", $so_no_str = "")
    {
        $courierFeedVo = $this->getDao('CourierFeed')->get();
        $obj = clone $courierFeedVo;
        $obj->setSoNoStr($so_no_str);
        $obj->setCourierId($courier);
        $obj->setMawb($mawb);
        $obj->setExec(0);

        $newObj = $this->getDao('CourierFeed')->insert($obj);
        $newObj->setBatchId($obj->getId());
        $this->getDao('CourierFeed')->update($newObj);
        $id = $newObj->getId();

        return $id;
    }
}
