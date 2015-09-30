<?php
namespace ESG\Panther\Dao;

class SubCatPlatformVarDao extends BaseDao
{
    private $tableName = "sub_cat_platform_var";
    private $voClassName = "SubCatPlatformVarVo";

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