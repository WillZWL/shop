<?php
namespace ESG\Panther\Service;

class DeliveryOptionService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function displayNameOf($courier_id, $lang_id = "en")
    {
        return $this->getDao('DeliveryOption')->displayNameOf($courier_id, $lang_id);
    }

    public function getListWithKey($where = [], $option = [])
    {
        $data = [];
        if ($objList = $this->getDao('DeliveryOption')->getList($where, $option)) {
            foreach ($objList as $obj) {
                $data[$obj->getLangId()][$obj->getCourierId()] = $obj;
            }
        }
        return $data;
    }

    public function update($obj, $where = []) {
        return $this->getDao('DeliveryOption')->update($obj, $where);
    }

    public function delete($obj) {
        return $this->getDao('DeliveryOption')->delete($obj);
    }

    public function insert($obj) {
        return $this->getDao('DeliveryOption')->insert($obj);
    }
}
