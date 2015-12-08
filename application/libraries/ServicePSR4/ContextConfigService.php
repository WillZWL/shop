<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ConfigDao;

class ContextConfigService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function valueOf($variable = "")
    {
        return $this->getDao('Config')->valueOf($variable);
    }
}
