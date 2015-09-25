<?php
namespace ESG\Panther\Dao;

class SoHoldStatusHistoryDao extends BaseDao
{
    private $tableName = "so_hold_status_history";
    private $voClassName = "SoHoldStatusHistoryVo";

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


