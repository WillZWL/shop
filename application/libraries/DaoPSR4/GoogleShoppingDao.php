<?php
namespace ESG\Panther\Dao;

class GoogleShoppingDao extends BaseDao
{
    private $tableName = "google_shopping";
    private $voClassName = "GoogleShoppingVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
