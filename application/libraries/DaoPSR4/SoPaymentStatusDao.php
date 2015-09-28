<?php
namespace ESG\Panther\Dao;

class SoPaymentStatusDao extends BaseDao
{
    private $tableName = "so_payment_status";
    private $voClassName = "SoPaymentStatusVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}


