<?php
namespace ESG\Panther\Dao;

class SoPriorityScoreDao extends BaseDao
{
    private $tableName = "so_priority_score";
    private $voClassname = "SoPriorityScoreVo";

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


