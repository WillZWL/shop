<?php
namespace AtomV2\Service;

use AtomV2\Dao\UserRoleDao;

class UserRoleService extends BaseService
{

    function __construct()
    {
        $this->setDao(new UserRoleDao);
    }
}
