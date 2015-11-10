<?php
namespace ESG\Panther\Dao;

class SoItemDao extends BaseDao
{
    private $tableName = "so_item";
    private $voClassName = "SoItemVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}


