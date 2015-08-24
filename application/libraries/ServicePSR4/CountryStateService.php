<?php
namespace AtomV2\Service;

use AtomV2\Dao\CountryStateDao;

class CountryStateService extends BaseService
{

    function __construct()
    {
        parent::__construct();
        $this->setDao(new CountryStateDao);
    }
}


