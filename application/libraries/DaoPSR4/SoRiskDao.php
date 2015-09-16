<?php
namespace ESG\Panther\Dao;

class SoRiskDao extends BaseDao
{
    private $tableName = "so_risk";
    private $voClassname = "SoRiskVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}


