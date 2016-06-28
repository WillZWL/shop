<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CourierDao;
use ESG\Panther\Dao\CourierRegionDao;
use ESG\Panther\Dao\InterfacePendingCourierDao;
use ESG\Panther\Dao\InterfaceCourierOrderDao;
use ESG\Panther\Dao\InterfaceCourierManifestDao;

class CourierService extends BaseService
{

    private $crcDao;
    private $interfacePendingCourierDao;
    private $interfaceCourierOrderDao;
    private $interfaceCourierManifestDao;
    

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new CourierDao);
        $this->setCrcDao(new CourierRegionDao);
        $this->setInterfacePendingCourierDao(new InterfacePendingCourierDao);
        $this->setInterfaceCourierOrderDao(new InterfaceCourierOrderDao);
        $this->setInterfaceCourierManifestDao(new InterfaceCourierManifestDao);
    }

    public function getCrcDao()
    {
        return $this->crcDao;
    }

    public function setCrcDao($dao)
    {
        $this->crcDao = $dao;
    }

    public function getInterfacePendingCourierDao()
    {
        return $this->interfacePendingCourierDao;
    }

    public function setInterfacePendingCourierDao($dao)
    {
        $this->interfacePendingCourierDao = $dao;
    }

    public function getInterfaceCourierOrderDao()
    {
        return $this->interfaceCourierOrderDao;
    }

    public function setInterfaceCourierOrderDao($dao)
    {
        $this->interfaceCourierOrderDao = $dao;
    }

    public function getInterfaceCourierManifestDao()
    {
        return $this->interfaceCourierManifestDao;
    }

    public function setInterfaceCourierManifestDao($dao)
    {
        $this->interfaceCourierManifestDao = $dao;
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
