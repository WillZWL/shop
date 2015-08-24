<?php
namespace AtomV2\Service;

use AtomV2\Dao\RoleDao;

class RoleService extends BaseService
{

    function __construct()
    {
        $this->setDao(new RoleDao);
    }
}
