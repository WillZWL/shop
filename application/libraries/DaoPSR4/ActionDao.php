<?php
namespace ESG\Panther\Dao;

class ActionDao extends BaseDao
{
    private $tableName = "action";
    private $voClassName = "ActionVo";

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
