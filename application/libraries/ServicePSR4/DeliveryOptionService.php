<?php
namespace AtomV2\Service;

use AtomV2\Dao\DeliveryOptionDao;

class DeliveryOptionService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new DeliveryOptionDao);
    }

    public function displayNameOf($courier_id, $lang_id = "en")
    {
        return $this->getDao()->displayNameOf($courier_id, $lang_id);
    }

    public function getListWithKey($where = [], $option = [])
    {
        $data = [];
        if ($objList = $this->getList($where, $option)) {
            foreach ($objList as $obj) {
                $data[$obj->getLangId()][$obj->getCourierId()] = $obj;
            }
        }
        return $data;
    }

    public function update($obj, $where = []) {
        return $this->getDao()->update($obj, $where);
    }

    public function delete($obj) {
        return $this->getDao()->delete($obj);
    }

    public function insert($obj) {
        return $this->getDao()->insert($obj);
    }

    // public function get($where = []) {
    //     return $this->getDao()->get($where);
    // }
}
