<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\UserService;
use ESG\Panther\Service\UserRoleService;
use ESG\Panther\Service\RoleService;

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
        return $this->userService->getDao('User')->get($where);
    }

    public function getUserRole($where = array())
    {
        return $this->userRoleService->getDao('UserRole')->get($where);
    }

    public function getUserRoleList($where = array())
    {
        return $this->userRoleService->getDao('UserRole')->getList($where);
    }

    public function inactiveUser($userVo)
    {
        return $this->userService->inactiveUser($userVo);
    }

    public function delUserRole($where)
    {
        if ($objlist = $this->userRoleService->getDao('UserRole')->getList($where)) {
            foreach ($objlist as $obj) {
                $this->userRoleService->getDao('UserRole')->delete($obj);
            }
            return true;
        }
        return false;
    }

    public function includeUserVo()
    {
        return $this->userService->getDao('User')->includeVo();
    }

    public function include_user_role_vo()
    {
        return $this->userRoleService->getDao('UserRole')->includeVo();
    }

    public function getRoleList($where = array())
    {
        return $this->roleService->getDao('Role')->getList();
    }

    public function addUser($obj)
    {
        return $this->userService->getDao('User')->insert($obj);
    }

    public function addUserRole($obj)
    {
        return $this->userRoleService->getDao('UserRole')->insert($obj);
    }

    public function update_user($obj)
    {
        return $this->userService->getDao('User')->update($obj);
    }

}
