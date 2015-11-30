<?php
namespace ESG\Panther\Dao;

class DeliveryTypeDao extends BaseDao
{
    private $tableName = "delivery_type";
    private $voClassName = "DeliveryTypeVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}


