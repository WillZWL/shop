<?php
namespace AtomV2\Dao;

class DeliverySurchargeDao extends BaseDao
{
    private $tableName = "delivery_surcharge";
    private $voClassName = "deliverySurchargeVo";

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


