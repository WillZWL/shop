<?php
namespace ESG\Panther\Dao;

class AutoRefundDao extends BaseDao
{
    private $tableName = "auto_refund";
    private $voClassname = "AutoRefundVo";

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
        return $this->voClassname;
    }
}


