<?php
namespace ESG\Panther\Dao;

class WarehouseDao extends BaseDao
{
    private $tableTame = "Warehouse";
    private $voClassName = "WarehouseVo";

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


