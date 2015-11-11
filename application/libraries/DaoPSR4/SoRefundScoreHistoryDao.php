<?php
namespace ESG\Panther\Dao;

class SoRefundScoreHistoryDao extends BaseDao
{
    private $tableName = "so_refund_score_history";
    private $voClassname = "SoRefundScoreHistoryVo";

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
