<?php
namespace ESG\Panther\Dao;

class LanguageDao extends BaseDao
{
    private $tableName = "language";
    private $voClassName = "LanguageVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
