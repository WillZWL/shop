<?php
namespace ESG\Panther\Dao;

class PlatformWarrantyDao extends BaseDao
{
    private $tableName = "platform_warranty";
    private $voClassName = "PlatformWarrantyVo";

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