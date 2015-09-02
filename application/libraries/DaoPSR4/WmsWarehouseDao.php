<?php
namespace AtomV2\Dao;

class WmsWarehouseDao extends BaseDao
{
    private $tableName = "wms_warehouse";
    private $voClassName = "WmsWarehouseVo";

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


