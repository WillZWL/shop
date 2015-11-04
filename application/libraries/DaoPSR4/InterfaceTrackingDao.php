<?php
namespace ESG\Panther\Dao;

class InterfaceTrackingDao extends BaseDao
{
    private $tableName = "interface_tracking";
    private $voClassName = "InterfaceTrackingVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
