<?php
namespace ESG\Panther\Dao;

class ScheduleJobDao extends BaseDao
{
    private $tableName = "schedule_job";
    private $voClassname = "ScheduleJobVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}


