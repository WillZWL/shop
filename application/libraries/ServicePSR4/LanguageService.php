<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\LanguageDao;

class LanguageService extends BaseService
{
    public function __construct()
    {
        log_message('info', 'RAPHALEInitialized');
        parent::__construct();
        $this->setDao(new LanguageDao);
    }

    public function getNameWIdKey()
    {
        $llist = $this->getDao()->getList(["status" => 1], ["limit" => -1]);
        $ret = [];
        foreach ($llist as $lobj) {
            $ret[$lobj->getLangId()] = $lobj->getLangName();
        }

        return $ret;
    }
}
