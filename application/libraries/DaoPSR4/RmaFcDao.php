<?php
namespace AtomV2\Dao;

class RmaFcDao extends BaseDao
{
    private $tableName = "rma_fc";
    private $voClassName = "RmaFcVo";

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
