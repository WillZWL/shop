<?php
namespace ESG\Panther\Dao;

class ExchangeRateApprovalDao extends BaseDao
{
    private $tableName = "exchange_rate_approval";
    private $voClassName = "ExchangeRateApprovalVo";

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


