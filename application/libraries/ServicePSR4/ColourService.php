<?php
namespace AtomV2\Service;

use AtomV2\Dao\ColourDao;

class ColourService extends BaseService
{
    public function __construct()
    {
        $this->setDao(new ColourDao);
    }

    public function getListWithLang($where, $option)
    {
        $this->getDao()->getListWithLang($where, $option);
    }
}
