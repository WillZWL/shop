<?php
namespace ESG\Panther\Dao;

class UserRoleDao extends BaseDao
{
    private $tableTame = "user_role";
    private $voClassName = "UserRoleVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableTame;
    }
}
