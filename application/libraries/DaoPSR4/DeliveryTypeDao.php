<?php
namespace ESG\Panther\Dao;

class DeliveryTypeDao extends BaseDao
{
    private $tableName = "delivery_type";
    private $voClassName = "DeliveryTypeVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}


