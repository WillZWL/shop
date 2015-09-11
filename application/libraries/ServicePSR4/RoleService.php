<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\RoleDao;

class RoleService extends BaseService
{

    function __construct()
    {
        $this->setDao(new RoleDao);
    }
}
