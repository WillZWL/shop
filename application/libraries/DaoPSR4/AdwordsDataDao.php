<?php
namespace ESG\Panther\Dao;

class AdwordsDataDao extends BaseDao
{
    private $tableName = "adwords_data";
    private $voClassName = "AdwordsDataVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
