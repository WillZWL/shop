<?php
namespace AtomV2\Dao;

class SiteConfigDao extends BaseDao
{
    private $tableName = 'site_config';
    private $voClassName = 'SiteConfigVo';

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
