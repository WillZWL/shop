<?php
namespace AtomV2\Dao;

class BrandRegionDao extends BaseDao
{
    private $tableName = "brand_region";
    private $voClassName = "BrandRegionVo";

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
