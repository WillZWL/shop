<?php
namespace ESG\Panther\Dao;

class EntityDao extends BaseDao
{
    private $tableName = "entity";
    private $voClassName = "EntityVo";

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
        return $this->tableName;
    }
}

