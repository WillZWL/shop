<?php
namespace ESG\Panther\Dao;

class PriceExtendDao extends BaseDao
{
    private $tableName = "price_extend";
    private $voClassname = "PriceExtendVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}


