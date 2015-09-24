<?php
namespace ESG\Panther\Dao;

class SoPriorityScoreHistoryDao extends BaseDao
{
    private $tableName = "so_priority_score_history";
    private $voClassname = "SoPriorityScoreHistoryVo";

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


