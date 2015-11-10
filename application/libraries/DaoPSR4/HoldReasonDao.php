<?php
namespace ESG\Panther\Dao;

class HoldReasonDao extends BaseDao
{
    private $tableName = "hold_reason";
    private $voClassName = "HoldReasonVo";

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
