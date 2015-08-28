<?php
namespace AtomV2\Dao;

class ProductTypeDao extends BaseDao
{
    private $tableName = "product_type";
    private $voClassName = "ProductTypeVo";

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
