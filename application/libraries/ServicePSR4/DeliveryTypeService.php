<?php
namespace ESG\Panther\Service;

class DeliveryTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDeliveryTypeList()
    {
        if ($list = $this->getDao('DeliveryType')->getList()) {
            foreach ($list as $obj) {
                $rs[$obj->getDeliveryTypeId()] = $obj->getName();
            }
        }
        return $rs;
    }
}
