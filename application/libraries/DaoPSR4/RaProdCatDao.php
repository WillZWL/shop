<?php
namespace ESG\Panther\Dao;

class RaProdCatDao extends BaseDao
{
    private $tableName = "Ra_prod_cat";
    private $voClassName = "RaProdCatVo";

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