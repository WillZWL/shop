<?php
namespace AtomV2\Dao;

class InterfaceExchangeRateDao extends BaseDao
{
    private $tableName = "interface_exchange_rate";
    private $voClassName = "InterfaceExchangeRateVo";

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


