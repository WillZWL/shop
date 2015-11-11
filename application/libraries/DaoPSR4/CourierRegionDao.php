<?php
namespace ESG\Panther\Dao;

class CourierRegionDao extends BaseDao
{
    private $tableName = "courier_region";
    private $voClassName = "CourierRegionVo";

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


