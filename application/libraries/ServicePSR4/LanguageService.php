<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\LanguageDao;

class LanguageService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        log_message('info', 'RAPHALEInitialized');
        parent::__construct($dao);
        // $this->setDao(new LanguageDao);
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
