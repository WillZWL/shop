<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\UserRoleDao;

class UserRoleService extends BaseService
{

    function __construct()
    {
        $this->setDao(new UserRoleDao);
    }
}
