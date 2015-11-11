<?php
namespace ESG\Panther\Dao;

class RmaDao extends BaseDao
{
    private $tableName = "rma";
    private $voClassName = "RmaVo";

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
