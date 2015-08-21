<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\UserService;
use AtomV2\Service\UserRoleService;
use AtomV2\Service\RoleService;

class UserModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService;
        $this->userRoleService = new UserRoleService;
        $this->roleService = new RoleService;
    }

    public function getListWRoles($where = array(), $option = array())
    {
        return $this->userService->getListWRoles($where, $option);
    }

    public function getUser($where = array())
    {
        return $this->userService->getDao()->get($where);
    }

    public function getUserRole($where = array())
    {
        return $this->userRoleService->getDao()->get($where);
    }

    public function getUserRoleList($where = array())
    {
        return $this->userRoleService->getDao()->getList($where);
    }

    public function inactiveUser($userVo)
    {
        return $this->userService->inactiveUser($userVo);
    }

    public function delUserRole($where)
    {
        if ($objlist = $this->userRoleService->getDao()->getList($where)) {
            foreach ($objlist as $obj) {
                $this->userRoleService->getDao()->delete($obj);
            }
            return true;
        }
        return false;
    }

    public function includeUserVo()
    {
        return $this->userService->getDao()->includeVo();
    }

    public function include_user_role_vo()
    {
        return $this->userRoleService->getDao()->includeVo();
    }

    public function getRoleList($where = array())
    {
        return $this->roleService->getDao()->getList();
    }

    public function addUser($obj)
    {
        return $this->userService->getDao()->insert($obj);
    }

    public function addUserRole($obj)
    {
        return $this->userRoleService->getDao()->insert($obj);
    }

    public function update_user($obj)
    {
        return $this->userService->getDao()->update($obj);
    }

}
