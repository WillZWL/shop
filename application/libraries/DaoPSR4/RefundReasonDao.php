<?php
namespace ESG\Panther\Dao;

class RefundReasonDao extends BaseDao
{
    private $tableName = "refund_reason";
    private $voClassName = "RefundReasonVo";

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