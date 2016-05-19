<?php
namespace ESG\Panther\Dao;

class AutoRestockLogDao extends BaseDao
{
    private $tableName = "auto_restock_log";
    private $voClassName = "AutoRestockLogVo";

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
