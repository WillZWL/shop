<?php
namespace AtomV2\Dao;

class SkuMappingDao extends BaseDao
{
    private $table_name = 'sku_mapping';
    private $vo_class_name = 'SkuMappingVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }
}
