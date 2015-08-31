<?php
namespace AtomV2\Service;

use AtomV2\Dao\LanguageDao;

class LanguageService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new LanguageDao());
    }

    public function getNameWIdKey()
    {
        $llist = $this->getDao()->getList(["status" => 1], ["limit" => -1]);
        $ret = [];
        foreach ($llist as $lobj) {
            $ret[$lobj->getId()] = $lobj->getName();
        }

        return $ret;
    }
}
