<?php
namespace ESG\Panther\Dao;

class TransmissionLogDao extends BaseDao
{
    private $tableName = "transmission_log";
    private $voClassName = "TransmissionLogVo";

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


