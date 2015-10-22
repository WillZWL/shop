<?php
namespace ESG\Panther\Dao;

class CompensationReasonDao extends BaseDao
{
    private $tableName = "compensation_reason";
    private $voClassname = "CompensationReasonVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}


