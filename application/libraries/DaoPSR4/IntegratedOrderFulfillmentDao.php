<?php
namespace ESG\Panther\Dao;

class IntegratedOrderFulfillmentDao extends BaseDao
{
    private $tableName = "integrated_order_fulfillment";
    private $voClassName = "IntegratedOrderFulfillmentVo";

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
