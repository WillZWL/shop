<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\UserDao;

class UserService extends BaseService
{

    public function __construct()
    {
        $this->setDao(new UserDao);
    }

    public function inactiveUser($userVo)
    {
        $userVo->setStatus(0);
        return $this->getDao()->update($userVo);
    }

    public function getMenuByGroup($appGroupId)
    {
        return $this->getDao()->getMenuByUserId($_SESSION["user"]["id"], $appGroupId);
    }

    public function isAllowedToCancelOrder()
    {
        return $this->getDao()->isAllowedToCancelOrderByRole($_SESSION["user"]["id"]);
    }

    public function menuItem($userId = "", $classname = "")
    {
        return $this->getDao()->getMenuItem($userId, $classname);
    }

    public function appRights($userId = "", $appId = "", $classname = "")
    {
        return $this->getDao()->getAppRights($userId, $appId, $classname);
    }

    public function checkAccess($userId = "", $appId = "", $rights = "")
    {
        return $this->getDao()->checkAccess($userId, $appId, $rights);
    }

    public function getListWRoles($where = array(), $option = array())
    {
        $data["userlist"] = $this->getDao()->getListWRoles($where, $option, "UserWRolesDto");
        $data["total"] = $this->getDao()->getListWRoles($where, array("num_rows" => 1));
        return $data;
    }

}
