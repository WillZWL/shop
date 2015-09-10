<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\SubCatPlatformVarDao;

class SubCatPlatformVarService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new SubCatPlatformVarDao);
    }

    public function loadVo()
    {
        $this->getDao()->get();
    }
}
