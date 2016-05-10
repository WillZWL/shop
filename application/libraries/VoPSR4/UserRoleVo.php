<?php
class UserRoleVo extends \BaseVo
{
    private $user_id;
    private $role_id;

    protected $primary_key = ['user_id', 'role_id'];
    protected $increment_field = '';

    public function setUserId($user_id)
    {
        if ($user_id !== null) {
            $this->user_id = $user_id;
        }
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setRoleId($role_id)
    {
        if ($role_id !== null) {
            $this->role_id = $role_id;
        }
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

}
