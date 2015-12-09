<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\FuncOptionDao;

class FuncOptionService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new FuncOptionDao);
    }

    public function textOf($funcId, $langId = "en")
    {
        return $this->getDao()->textOf($funcId, $langId);
    }

    public function getListWithKey($where = [], $option = [])
    {
        $data = [];
        if ($objList = $this->getDao('FuncOption')->getList($where, $option)) {
            foreach ($objList as $obj) {
                $data[$obj->getLangId()][$obj->getFuncId()] = $obj;
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
}
