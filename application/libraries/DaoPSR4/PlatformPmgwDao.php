<?php
namespace ESG\Panther\Dao;

class PlatformPmgwDao extends BaseDao
{
    private $table_name = 'platform_pmgw';
    private $vo_class_name = 'PlatformPmgwVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }
}


