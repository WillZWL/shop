<?php
namespace ESG\Panther\Dao;

class SoRefundScoreDao extends BaseDao
{
    private $tableName = "so_refund_score";
    private $voClassname = "SoRefundScoreVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}
