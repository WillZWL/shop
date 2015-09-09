<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ConfigDao;

class ContextConfigService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new ConfigDao);
    }

    public function valueOf($variable = "")
    {
        return $this->getDao()->valueOf($variable);
    }
}
