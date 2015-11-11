<?php
namespace ESG\Panther\Dao;

class RoleDao extends BaseDao
{
    private $tableTame = "role";
    private $voClassName = "RoleVo";

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
