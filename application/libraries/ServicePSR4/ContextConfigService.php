<?php
namespace AtomV2\Service;

use AtomV2\Dao\ConfigDao;

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
