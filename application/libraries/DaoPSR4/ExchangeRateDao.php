<?php
namespace ESG\Panther\Dao;

class ExchangeRateDao extends BaseDao
{
    private $tableName = "exchange_rate";
    private $voClassName = "ExchangeRateVo";

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


