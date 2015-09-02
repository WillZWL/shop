<?php
namespace AtomV2\Service;

use AtomV2\Dao\DeliveryTypeDao;

class DeliveryTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new DeliveryTypeDao);
    }

    public function getDeliveryTypeList()
    {
        if ($list = $this->getDao()->getList()) {
            foreach ($list as $obj) {
                $rs[$obj->getDeliveryTypeId()] = $obj->getName();
            }
        }
        return $rs;
    }
}
