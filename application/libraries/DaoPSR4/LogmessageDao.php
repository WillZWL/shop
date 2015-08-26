<?php
namespace AtomV2\Dao;

Class LogmessageDao extends BaseDao
{
    private $tableName = "logmessage";
    private $voClassName = "LogmessageVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }
}
