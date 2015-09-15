<?php
namespace ESG\Panther\Dao;

class VProdInventoryDao extends BaseDao
{
    private $tableName = "v_prod_inventory";
    private $voClassName = "VProdInventoryVo";

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


