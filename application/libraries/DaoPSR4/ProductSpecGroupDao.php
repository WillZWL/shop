<?php
namespace ESG\Panther\Dao;

class ProductSpecGroupDao extends BaseDao
{
    private $tableName = "product_spec_group";
    private $voClassName = "ProductSpecGroupVo";

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
