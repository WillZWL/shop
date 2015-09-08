<?php
namespace AtomV2\Service;

use AtomV2\Dao\CourierDao;
use AtomV2\Dao\CourierRegionDao;

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
}
