<?php
namespace ESG\Panther\Dao;

class UnitDao extends BaseDao
{
    private $tableTame = "Unit";
    private $voClassName = "UnitVo";

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


