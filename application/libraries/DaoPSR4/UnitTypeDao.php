<?php
namespace ESG\Panther\Dao;

class UnitTypeDao extends BaseDao
{
    private $tableTame = "unit_type";
    private $voClassName = "UnitTypeVo";

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
        return $this->tableTame;
    }
}



