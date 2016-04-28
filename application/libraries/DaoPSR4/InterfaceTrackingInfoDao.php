<?php
namespace ESG\Panther\Dao;

class InterfaceTrackingInfoDao extends BaseDao
{
    private $tableName = "interface_tracking_info";
    private $voClassName = "InterfaceTrackingInfoVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
