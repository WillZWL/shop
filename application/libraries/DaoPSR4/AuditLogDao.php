<?php
namespace AtomV2\Dao;

class AuditLogDao extends BaseDao
{
    private $tableName = "audit_log";
    private $voClassName = "AuditLogVo";

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
