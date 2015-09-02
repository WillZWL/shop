<?php
namespace AtomV2\Dao;

class ColourExtendDao extends BaseDao
{
    private $table_name = 'colour_extend';
    private $vo_class_name = 'ColourExtendVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }
}
