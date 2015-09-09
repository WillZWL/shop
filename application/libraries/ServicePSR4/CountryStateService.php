<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CountryStateDao;

class CountryStateService extends BaseService
{

    function __construct()
    {
        parent::__construct();
        $this->setDao(new CountryStateDao);
    }
}


