<?php
class RoleAccessDto
{
    private $rights_id;
    private $rights;

    public function setRightsId($rights_id)
    {
        $this->rights_id = $rights_id;
    }

    public function getRightsId()
    {
        return $this->rights_id;
    }

    public function setRights($rights)
    {
        $this->rights = $rights;
    }

    public function getRights()
    {
        return $this->rights;
    }

}
