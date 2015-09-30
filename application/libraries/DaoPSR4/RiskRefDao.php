<?php
namespace ESG\Panther\Dao;

class RiskRefDao extends BaseDao
{
    private $tableName = "risk_ref";
    private $voClassName = "RiskRefVo";

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


