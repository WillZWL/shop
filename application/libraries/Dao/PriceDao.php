<?php
namespace AtomV2\Dao;

class PriceDao extends BaseDao
{
    private $tableName = 'price';
    private $voClassName = 'PriveVo';

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
