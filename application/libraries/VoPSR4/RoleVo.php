<?php
class RoleVo extends \BaseVo
{
    private $id;
    private $role_name;
    private $description;
    private $status = '0';

    protected $increment_field = '';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRoleName($role_name)
    {
        if ($role_name !== null) {
            $this->role_name = $role_name;
        }
    }

    public function getRoleName()
    {
        return $this->role_name;
    }

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
