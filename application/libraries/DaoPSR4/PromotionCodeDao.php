<?php
namespace ESG\Panther\Dao;

class PromotionCodeDao extends BaseDao
{
    private $tableName = "promotion_code";
    private $voClassName = "PromotionCodeVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
