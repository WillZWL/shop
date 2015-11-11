<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\UserDao;

class UserService extends BaseService
{
    public function inactiveUser($userVo)
    {
        $userVo->setStatus(0);
        return $this->getDao('User')->update($userVo);
    }

    public function getMenuByGroup($appGroupId)
    {
        return $this->getDao('User')->getMenuByUserId($_SESSION["user"]["id"], $appGroupId);
    }

    public function isAllowedToCancelOrder()
    {
        return $this->getDao('User')->isAllowedToCancelOrderByRole($_SESSION["user"]["id"]);
    }

    public function menuItem($userId = "", $classname = "")
    {
        return $this->getDao('User')->getMenuItem($userId, $classname);
    }

    public function appRights($userId = "", $appId = "", $classname = "")
    {
        return $this->getDao('User')->getAppRights($userId, $appId, $classname);
    }

    public function checkAccess($userId = "", $appId = "", $rights = "")
    {
        return $this->getDao('User')->checkAccess($userId, $appId, $rights);
    }

    public function getListWRoles($where = array(), $option = array())
    {
        $data["userlist"] = $this->getDao('User')->getListWRoles($where, $option, "UserWRolesDto");
        $data["total"] = $this->getDao('User')->getListWRoles($where, array("num_rows" => 1));
        return $data;
    }

}
