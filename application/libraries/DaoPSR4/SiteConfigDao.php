<?php
namespace ESG\Panther\Dao;

class SiteConfigDao extends BaseDao
{
    private $table_name = 'site_config';
    private $vo_class_name = 'SiteConfigVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }
}
