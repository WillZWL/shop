<?php
namespace ESG\Panther\Dao;

class ProductSpecDao extends BaseDao
{
    private $tableName = "product_spec";
    private $voClassName = "ProductSpecVo";

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
