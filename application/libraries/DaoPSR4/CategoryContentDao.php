<?php
namespace AtomV2\Dao;

class CategoryContentDao extends BaseDao
{
    private $tableName = "category_content";
    private $voClassName = "CategoryContentVo";

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


