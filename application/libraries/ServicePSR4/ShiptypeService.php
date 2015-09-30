<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ShiptypeDao;

class ShiptypeService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new ShiptypeDao);
    }

}
