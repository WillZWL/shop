<?php
namespace ESG\Panther\Dao;

class PaymentGatewayDao extends BaseDao
{
    private $tableName = "payment_gateway";
    private $voClassName = "PaymentGatewayVo";

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


