<?php
namespace AtomV2\Dao;

class PriceDao extends BaseDao
{
    private $table_name = 'price';
    private $vo_class_name = 'PriveVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }
}
