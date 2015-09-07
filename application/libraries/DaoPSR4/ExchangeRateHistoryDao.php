<?php
namespace AtomV2\Dao;

class ExchangeRateHistoryDao extends BaseDao
{
    private $tableName = "exchange_rate_history";
    private $voClassName = "ExchangeRateHistoryVo";

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
