<?php
namespace AtomV2\Service;

use AtomV2\Dao\ShiptypeDao;

class ShiptypeService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new ShiptypeDao);
    }

}
